<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
  private function excutePayment($url, $data)
  {
    $client = Http::timeout(5)->post($url, $data);

    $data = $client->body();

    return $data;
  }

  public function momoPay(Request $request)
  {
    $data = $request->all();

    $orderInfo = "Thanh toán qua MoMo";
    //thông tin thanh toán đơn hàng

    $amount = "10000";
    // giá trị đơn hàng

    // order id tạo tự động từ time (sửa thành order id của đơn hàng)
    $orderId = time() . "";

    $redirectUrl = "http://localhost:8000/payment-success";
    //url url chuyển hướng khi người dùng thanh toán thành công

    $ipnUrl = "http://localhost:8000/payment-success";
    // url truy van ket qua giao dịch

    $extraData = "";
    //các giữ liệu thêm khác

    if (!$data) {
      //api config information
      $endpoint = config('payment.momo.apiUrl');
      $partnerCode = config('payment.momo.partnerCode');
      $accessKey = config('payment.momo.accessKey');
      $serectkey = config('payment.momo.secretKey');

      $orderId = $orderId; // Mã đơn hàng

      $orderInfo = $orderInfo;

      $amount = $amount;

      $ipnUrl = $ipnUrl;

      $redirectUrl = $redirectUrl;

      $extraData = $extraData;

      $requestId = time() . "";

      //payment type
      $requestType = "captureWallet";

      //before sign HMAC SHA256 signature
      $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData="
        . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo="
        . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl
        . "&requestId=" . $requestId . "&requestType=" . $requestType;

      $signature = hash_hmac("sha256", $rawHash, $serectkey);

      $callData = array(
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestType' => $requestType,
        'ipnUrl' => $ipnUrl,
        'redirectUrl' => $redirectUrl,
        'orderId' => $orderId, // id đơn hàng
        'amount' => $amount, // số lượng
        'lang' => 'vi',
        'orderInfo' => $orderInfo,// thông tin thanh toán
        'requestId' => $requestId,
        'extraData' => $extraData,
        'signature' => $signature,
      );

      $result = $this->excutePayment($endpoint, $callData);

      $jsonResult = json_decode($result, true);

      return redirect()->to($jsonResult['payUrl']);
    }
  }

  // when payment success
  public function paymentSuccess(Request $request)
  {
    dd($request->all());
  }
}



