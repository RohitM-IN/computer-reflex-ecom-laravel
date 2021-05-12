<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\OrderAddress;
use App\Models\AffiliateOrderItem;
use App\Mail\OrderPlacedMail;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{

    public function CheckoutView(Request $req)
    {
        $addresses = Address::where('user_id', Auth()->user()->id)->get();

        foreach ($req->product_id as $key => $value) {
            $data[] = Product::with('images')->where('id', $value)->first();
            $qty[] = $req->product_qty[$key];
        }

        return view('checkout-form', [
            'data'      => $data,
            'qty'       => $qty,
            'addresses' => $addresses,
        ]);
    }

    public function CheckoutSubmit(Request $req)
    {   
        $address = Address::where('user_id', Auth()->user()->id)->where('id', $req->address_id)->first();
           
        if (!isset($address)) {  // Check if address is invalid then abort 
            abort(500); 
        }

        // Calculate the MRP & Price 
        $mrp        = 0; // Default MRP
        $price      = 0; // Default Price
        $itemCount  = 0; // Default Item Count
        foreach ($req->product_id as $i => $pid) {
            $product = Product::where('id', $pid)->first();
            if ($product->product_stock >= $req->product_qty[$i]) {
                $mrp        += $product->product_mrp * $req->product_qty[$i];
                $price      += $product->product_price * $req->product_qty[$i];
                $itemCount  += 1;
            } else {
                return redirect()->route('cart');
            }
        }

        // Abort if no items for checkout 
        if ($itemCount <= 0) {
            abort(500);
        }

        // Create new order entry
        $order = new Order;
        $order->user_id         = Auth()->user()->id;
        $order->address_id      = $req->address_id;
        $order->mrp             = $mrp;
        $order->price           = $price;
        $order->payment_method  = $req->payment_method;
        $order->status          = 'checkout_pending';
        $order->save();

        // Save User Address For Order Address
        $OrderAddress = new OrderAddress;
        $OrderAddress->order_id     = $order->id;
        $OrderAddress->name         = $address->name;
        $OrderAddress->house_no     = $address->house_no;
        $OrderAddress->locality     = $address->locality;
        $OrderAddress->city         = $address->city;
        $OrderAddress->district     = $address->district;
        $OrderAddress->state        = $address->state;
        $OrderAddress->pin_code     = $address->pin_code;
        $OrderAddress->mobile       = $address->mobile;
        $OrderAddress->alt_mobile   = $address->alt_mobile;
        $OrderAddress->save();

        // Add items for order
        foreach ($req->product_id as $key => $pid) {
            $prod = Product::where('id', $pid)->first();
            $orderItem = new OrderItem;
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $pid;
            $orderItem->qty = $req->product_qty[$key];
            $orderItem->unit_price = $prod->product_price;
            $orderItem->unit_mrp = $prod->product_mrp;
            $orderItem->total_price = $prod->product_price * $req->product_qty[$key];
            $orderItem->status = 'checkout_pending';
            $orderItem->save();
        }

        // Send user to paytm for payment
        if ($req->payment_method == 'paytm') {
            $paytmParam = [ 
                'ORDER_ID' => $order->id,
                'CUST_ID' => Auth()->user()->id,
                'TXN_AMOUNT' => $price,
                'MOBILE_NO' => Auth()->user()->mobile ?? $OrderAddress->mobile,
                'EMAIL' => Auth()->user()->email,
                'CALLBACK_URL' => route('checkout-paytm-response'),
            ];
    
            $payment = Indipay::gateway('Paytm')->prepare($paytmParam);
            return Indipay::process($payment);
        }

        // Send user to PayU for payment
        else if ($req->payment_method == 'payu') 
        {
            $payuParam = [ 
                'txnid' => $order->id,
                'amount' => $price,
                'productinfo' => 'Order on ComputerReflex',
                'firstname' => Auth()->user()->name,
                'phone' => Auth()->user()->mobile ?? $OrderAddress->mobile,
                'email' => Auth()->user()->email,
                'surl' => route('checkout-payu-response'),
                'furl' => route('checkout-payu-response'),
            ];
    
            $payment = Indipay::gateway('PayUMoney')->prepare($payuParam);
            return Indipay::process($payment);
        } 

        // Process COD Order
        elseif ($req->payment_method == 'cod') 
        {
            Order::where('id', $order->id)->update([
                'status' => 'order_placed',
            ]);
            OrderItem::where('order_id', $order->id)->update([
                'status' => 'order_placed',
            ]);

            return $this->AfterPayment($order->id);
        }

    }


    public function AfterPayment($order_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', Auth()->user()->id)->with('OrderItems.product.comission')->with('Address')->with('Address')->first();

        if (!isset($order)) {
            abort(500);
        }

        $data = [
            'order'         => $order,
            'items'         => $order->OrderItems,
            'address'       => $order->Address,
        ];
    
        if ($order->status == 'order_placed') {
           
        // Add item to Affiliate Order Items table if eligible for Affiliate Comission 
        foreach ($order->OrderItems as $key => $OrderItem) {
            $prod = $OrderItem->product;
            if (isset($prod->comission->comission) && isset(Auth()->user()->affiliate->associate_id)) {
                if ($prod->comission->comission > 0) {
                    $affiliateOrderItem = new AffiliateOrderItem;
                    $affiliateOrderItem->associate_id = Auth()->user()->affiliate->associate_id;
                    $affiliateOrderItem->order_item_id = $OrderItem->id;
                    $affiliateOrderItem->comission = CalcPerc($prod->comission->comission, $prod->product_price) * $OrderItem->qty;
                    $affiliateOrderItem->status = 'pending';
                    $affiliateOrderItem->save();
                }
            }
        }
          
            
            mail::to(Auth()->user()->email)->send(new OrderPlacedMail($data)); 
        }
    
        return redirect()->route('checkout-order-confirmation', $order->id);
    }




    // Process after payment
    public function CheckoutOrderConfirmation($order_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', Auth()->user()->id)->with('OrderItems')->with('Address')->first();

        // Abort if order is invalid or not right user.
        if (!isset($order)) {
            abort(500);
        }

        $data = [
            'order'         => $order,
            'items'         => $order->OrderItems,
            'address'       => $order->Address,
        ];

        // Process the order as Placed
        if ($order->status == 'order_placed') 
        {
            return view('checkout.success', [
                'data' => $data,
            ]);
        }
        // Process the order as Payment Failed
        else if ($order->status == 'payment_failed') 
        {
            return view('checkout.failed', [
                'data' => $data,
            ]);
        } 
        // Process the order as Payment Pending
        else if ($order->status == 'payment_pending')
        {
            return view('checkout.pending', [
                'data' => $data,
            ]);
        } 
        else {
            abort(500);
        }
        
    }











    public function PaytmResponse(Request $req)
    {
        $response = Indipay::gateway('Paytm')->response($req);
        if ($response['STATUS'] == 'TXN_SUCCESS') {
            Order::where('id', $response['ORDERID'])->update([
                'status' => 'order_placed'
            ]);

            OrderItem::where('order_id', $response['ORDERID'])->update([
                'status' => 'order_placed'
            ]);

        }

        else if ($response->STATUS == 'TXN_FAILURE') {
            Order::where('id', $response['ORDERID'])->update([
                'status' => 'payment_failed'
            ]);

            OrderItem::where('order_id', $response['ORDERID'])->update([
                'status' => 'payment_failed'
            ]);

        }

        else {
            Order::where('id', $response['ORDERID'])->update([
                'status' => 'payment_pending'
            ]);

            OrderItem::where('order_id', $response['ORDERID'])->update([
                'status' => 'payment_pending'
            ]);

        }
        
        return $this->AfterPayment($response['ORDERID']);

    }
    
    public function PayuResponse(Request $req)
    { 
        if ($req->status == 'success') {
            Order::where('id', $req->txnid)->update([
                'status' => 'order_placed'
            ]);

            OrderItem::where('order_id', $req->txnid)->update([
                'status' => 'order_placed'
            ]);
        }

        else if ($req->status == 'failure') {
            Order::where('id', $req->txnid)->update([
                'status' => 'payment_failed'
            ]);

            OrderItem::where('order_id', $req->txnid)->update([
                'status' => 'payment_failed'
            ]);

        }

        else {
            Order::where('id', $req->txnid)->update([
                'status' => 'payment_pending'
            ]);

            OrderItem::where('order_id', $req->txnid)->update([
                'status' => 'payment_pending'
            ]);
        }

        return $this->AfterPayment($req->txnid);
    }


}
