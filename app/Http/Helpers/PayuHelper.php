<?php

namespace App\Http\Helpers;

class PayuHelper {

    static public function fetchToken()
    {
        
        // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://test.payumoney.com/treasury/merchant/refundPayment?merchantKey=JL3Gfrb4&paymentId=250916181&refundAmount=20');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Authorization: ZJyQG66q0vZS3YQZRpR1VzQxzNaicJtG4qeDlOvpbeg=';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Cache-Control: no-cache';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        
        curl_close($ch);
        $result = json_decode($result);
        dd($result);

    }


}