<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;



function payment($stringNumber, $descricao, $email)
{
    $preco = intval($stringNumber);
    global $acess;
    MercadoPagoConfig::setAccessToken($acess);
    $client = new PaymentClient();
    try {

        $request = [
            "transaction_amount" => $preco,
            "description" => $descricao,
            "payment_method_id" => "pix",
            "payer" => [
                'email' => $email
            ]
        ];

        $payment = $client->create($request);
        $id = $payment->id;
        return $id;
    } catch (MPApiException $e) {
        echo "Status code: " . $e->getApiResponse()->getStatusCode() . "\n";
        echo "Content: " . $e->getApiResponse()->getContent() . "\n";

    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
function getqr($id)
{

    global $acess;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $acess",
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response, true);
    $qrCodeData = $data['point_of_interaction']['transaction_data']['qr_code'];
    return $qrCodeData;
}
function checkpayment($stringNumber)
{
    $id = intval($stringNumber);
    global $acess;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/$id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $acess",
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response, true);
    $status = $data['status'];
    if ($status == "approved") {
        return "pago";
    } else if ($status == "pending") {
        return "pendente";
    } else {
        return "cancelado";
    }

}