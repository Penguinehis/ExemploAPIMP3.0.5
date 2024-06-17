<?php
require __DIR__ . '/mercadopagopingu.php';
require __DIR__ . '/database.php';

$id = $_GET['id'];
function checkpag($id)
{
    if (checkpayment($id) == "pago") {
        setpago($id);
        return ['pago' => 1];
    } else if (checkpayment($id) == "cancelado"){
        return ['pago' => 9];
    }
    else {
        return ['pago' => 0];
    }
}

header('Content-Type: application/json');
echo json_encode(checkpag($id));
