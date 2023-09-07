<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{

    //ham thực thi
    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momoPay(Request $request)
    {
        $data = $request->all();

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        //key từ api momo
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        //thông tin thanh toán đơn hàng
        $amount = "10000";
        // giá trị đơn hàng

        // order id tạo tự động từ time (sửa thành order id của đơn hàng)
        $orderId = time() ."";

        $redirectUrl = "http://localhost:8000/payment-success";
        //url url chuyển hướng khi người dùng thanh toán thànhc ông

        $ipnUrl = "http://localhost:8000/payment-success";
        // url truy van ket qua giao dịch

        $extraData = "";
        //các giữ liệu thêm khác


        if ($data) {
            $partnerCode = $partnerCode;
            $accessKey = $accessKey;
            $serectkey = $secretKey;

            $orderId = $orderId; // Mã đơn hàng

            $orderInfo = $orderInfo;

            $amount = $amount;

            $ipnUrl = $ipnUrl;

            $redirectUrl = $redirectUrl;

            $extraData = $extraData;

            $requestId = time() . "";

            $requestType = "captureWallet";

            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

            $signature = hash_hmac("sha256", $rawHash, $serectkey);

            $callData = array(
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            );

            $result = $this->execPostRequest($endpoint, json_encode($callData));

            $jsonResult = json_decode($result, true);  // decode json

           return redirect()->to($jsonResult['payUrl']);
        }
    }

    public function paymentSuccess(Request $request)
    {
        dd($request->all());
    }
}



