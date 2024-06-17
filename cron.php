<?php
require __DIR__ . '/mercadopagopingu.php';
require __DIR__ . '/database.php';


foreach (getids() as $id) {
    if (checkpayment($id) == "pago") {
        setpago($id);
    } else if (checkpayment($id) == "cancelado") {
        setcancel($id);
    }
}
echo "Script OK";