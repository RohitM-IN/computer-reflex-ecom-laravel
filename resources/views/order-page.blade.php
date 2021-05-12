@if (isMobile())

    @include('mobile.order-page')

{{ die }}
@endif


@extends('layouts.common')

@section('title', 'Order')

@section('modals')
    <!-- Modal -->

    <form action="{{ route('cancel-order.request') }}" method="post"> @csrf <input type="hidden" name="order_id" value="{{ $order->id }}">
        <div class="modal fade" id="OrderCancelModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Ordered Items</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100" >
                            @if (!isset($order->PendingCancelRequest))
                                <div class="form-group">
                                    <label>Cancellation Reason</label>
                                    <textarea required name="reason" cols="30" rows="10" placeholder="Why you want to cancel the order?"></textarea>
                                </div>
                            @else
                                @if ($order->status == 'order_shipped')
                                    <div>
                                        <span>
                                            Can't cancel the order now. <br>
                                            The order has already been handed over to courier partner. <br>
                                            If you don't want this order, simply don't accept the parcel when delivery executive attempts the delivery.
                                        </span>
                                    </div>
                                @else
                                <div>
                                    <span>
                                        Can't create a new cancellation request. <br>
                                        This order already has a pending cancellation request.
                                    </span>
                                </div>
                                @endif
                            @endif
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if (!isset($order->PendingCancelRequest))
                        <button type="submit" class="btn btn-primary" >Request Cancellation</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('content')
<div class="body-container" style="padding-bottom: 14px;">
    <div class="container">
        <div class="account-details-container" style="margin-bottom: 10px;">
            <div style="padding: 10px 32px;">
                <div class="account-details-title" style="padding-bottom: 0px;">
                    <img src="{{ asset('/img/svg/happy-birth-day.svg') }}" width="50" alt="" srcset="">
                    <span style="padding-right: 0;">Order Details</span> #{{ date_format($order->created_at,"Y-mdHis").'-'.$order->id }}
                </div>
            </div>
        </div>

        <div class="account-details-container">
            <div class="right-wishlist-container" style="min-height: 0;">
        
                <div class="wishlist-basic-padding">
                    <div class="account-details-title" style="padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-6">
                                <span>Delivery Address</span>
                            </div>
                            <div class="col-6">
                                <span>Order Summary</span>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="account-menu-break"></div>   
                                                    
                    <div class="wishlist-container">   
    
                            <div class="account-menu-break"></div>     
                             
                                <div class="row wishlist-basic-padding" id="CartItem2" style="padding-bottom: 0;">
    
        
                                    <div class="col-6">
                                        <p>
                                            <span style="font-weight: 500;">{{$order->Address->name}}</span> <br>
                                            {{$order->Address->house_no}}, <br>
                                            {{$order->Address->locality}}, <br>
                                            {{$order->Address->city}}, <br>
                                            {{$order->Address->district}}, <br>
                                            {{$order->Address->state}}, {{$order->Address->pin_code}} <br>
                                        </p>
                                        <p>
                                            <span style="font-weight: 500;">Contact information</span><br>
                                            {{$order->Address->mobile}}@if (isset($order->Address->alt_mobile)), {{$order->Address->alt_mobile}}@endif
                                            
                                        </p>
                                    </div>
    
                                    <div class="col-6">
                                        <table style="width: 100%;">
                                            <tr style="width: 100%">
                                                <td style="width: 50%; font-weight: 500;">Payment Method:</td>    
                                                <td style="width: 50%; text-align: right;">@if ($order->payment_method == 'cod') Cash On Delivery @elseif($order->payment_method == 'paytm') PayTM @elseif($order->payment_method == 'payu') PayU @endif</td>    
                                            </tr>    
                                            <tr style="width: 100%">
                                                <td style="width: 50%; font-weight: 500;">Items Ordered:</td>    
                                                <td style="width: 50%; text-align: right;">{{ $order->OrderItems->count() }}</td>    
                                            </tr>    
                                            <tr style="width: 100%">
                                                <td style="width: 50%; font-weight: 500;">Order MRP:</td>    
                                                <td style="width: 50%; text-align: right;"><font class="rupees">₹</font>{{ moneyFormatIndia($order->mrp) }}</td>    
                                            </tr>    
                                            <tr style="width: 100%">
                                                <td style="width: 50%; font-weight: 500;">Discount:</td>    
                                                <td style="width: 50%; text-align: right; color: #388e3c;">-<font class="rupees">₹</font>{{ moneyFormatIndia($order->mrp - $order->price) }}</td>    
                                            </tr>    
                                            <tr style="width: 100%; font-size: 16px; ">
                                                <td style="width: 50%; font-weight: 500; padding-top: 10px;">Grand Total:</td>    
                                                <td style="width: 50%; font-weight: 500; color: black; text-align: right; padding-top: 10px;"><font class="rupees">₹</font>{{ moneyFormatIndia($order->price) }}</td>    
                                            </tr>   
                                           
                                        </table>
            
                                        @if ($order->payment_method != 'cod')
                                        <div style="padding-top: 10px; text-align: right;">
                                            <span style="font-size: 16px; color: #388e3c;">
                                                Payment Completed <i class="fa fa-check" aria-hidden="true"></i>
                                            </span>
                                        </div>
                                        @endif

                                        <div style="padding-top: 10px; text-align: right;">
                                            <button class="btn btn-dark btn-block" data-toggle="modal" data-target="#OrderCancelModal">CANCEL ORDER</button>
                                        </div>
                                        
                                      
                                                                       
                                    </div>
                                    
                                </div>
    
                                <div class="row wishlist-basic-padding" style="padding-top: 0;"></div>
    
                            <div class="account-menu-break" id="CartBreak2"></div>      
                             

                        </div> 


                        
                    </div>
            
                </div>

                
                @if ($order->CancelRequest->count() > 0)
                <div class="account-details-container">
                    <div class="wishlist-basic-padding" style="padding-bottom: 0;">
                   
                        @foreach ($order->CancelRequest as $cancelReq)
                            <div class="collapse-item mb-4">
                                <div class="collapse-btn" style="padding: 7px 10px; transition: all 200ms; background-color: rgba(212, 212, 212, 0.781);">
                                    <span style="font-weight: 600">Cancel Request #{{ $cancelReq->id }} - 
                                        @if ($cancelReq->status == 'requested')
                                        <span class="btn btn-sm btn-warning">Requested</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="collapse-content bg-light" style="">
                                    <div style="padding-top: 6px; padding-bottom: 6px;">
                                        <p>
                                            @if ($cancelReq->status == 'requested')
                                                <span>We have received your cancellation request, our team will verify your request shortly, and update the status accordingly. <br> Reference #{{ $cancelReq->id }} <br> Thank you for your patience. </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="account-menu-break"></div>  
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="account-menu-break"></div> 
                </div>       
                @endif



                

                           
                @foreach ($order->OrderItems as $item)
                <div class="account-details-container" style="margin-bottom: 0;" >
                    <div class="wishlist-basic-padding" >
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{route('product-index', $item->product->id)}}" target="_blank">
                                    <div class="wish-product-image-container">
                                        <img src="{{asset('storage/images/products/'.$item->image->image)}}" alt="">
                                    </div>
                                </a>
                            </div>
    
                            <div class="col-md-5">
                                <a href="{{route('product-index', $item->product->id)}}" target="_blank">
                                    <span class="wish-product-title font-weight-500 color-0066c0">{{$item->product->product_name}}</span>
                                </a>
                            </div>
    
                            <div class="col-3">
                                <table style="width: 100%">
                                    <tr style="width: 100%">
                                        <td style="width: 50%">Unit Price:</td>
                                        <td style="width: 50%"><font class="rupees">₹</font>{{ moneyFormatIndia($item->unit_price) }}</td>
                                    </tr>
                                    <tr style="width: 100%">
                                        <td style="width: 50%">Qty:</td>
                                        <td style="width: 50%">{{ moneyFormatIndia($item->qty) }}</td>
                                    </tr>
                                    <tr style="width: 100%">
                                        <td style="width: 50%">Total:</td>
                                        <td style="width: 50%"><font class="rupees">₹</font>{{ moneyFormatIndia($item->total_price) }}</td>
                                    </tr>
                                </table>
                            </div>
    
                            <div class="col-md-2">
                                @if ($item->status == 'order_placed')
                                <span style="color: #2874f0">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Order Placed.
                                </span>
                                @elseif($item->status == 'packing_started')
                                <span style="color: #2874f0">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Packing Started.
                                </span>
                                @elseif($item->status == 'packing_completed')
                                <span style="color: #2874f0">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Packing Completed.
                                </span>
                                @elseif($item->status == 'shipment_created')
                                <span style="color: #2874f0">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Shipment Created, Waiting For Pickup.
                                </span>
                                @elseif($item->status == 'item_shipped')
                                <span style="color: #2874f0">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Product Shipped Via <b>{{ $item->shipment->courier_name }}</b>.
                                  
                                
                                
                                </span>
                                @elseif($item->status == 'item_delivered')
                                <span style="color: #28a745">
                                    <span style="">
                                        <i class="fa fa-circle" aria-hidden="true"></i>
                                    </span>
                                    Delivered On: <b>{{date_format(new DateTime($item->shipment->delivery_date), "dS M,(D)")}}</b>
                                </span>
                                @endif
                            </div>

                        </div>
    
                    </div>
                </div>
                <div class="account-menu-break"></div>    
                {{-- <div class="row wishlist-basic-padding" style="padding-top: 0;"></div> --}}
                      
                @endforeach


    </div>
</div>
@endsection
    

