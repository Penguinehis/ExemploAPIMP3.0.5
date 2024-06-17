<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/mercadopagopingu.php';
require_once __DIR__ . '/database.php';
use chillerlan\QRCode\QRCode;

//Gerar um pagamento aleatorio so para teste
$_SESSION['idmp'] = payment("10", "Teste", "bolinho@cerouna.com");
insertmp($_SESSION['idmp'], "Teste");
// Preguiça de codar isso então VLW geminimi Advanced haha
function qr2code($id)
{
  $qrCode = (new QRCode)->render($id);
  return $qrCode;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pix QR Code</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        img {
            /* Target the image tag */
            width: 512px;
            height: 512px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function checkPaymentStatus() {
            $.ajax({
                url: 'checkpag.php?id=<?php echo $_SESSION['idmp']?>',
                method: 'GET',
                success: function(data) {
                    if (data.pago === 1) {
                        $('body').html('<h1>Agradecemos seu pagamento</h1>');
                    }else if (data.pago === 9){
                        $('body').html('<h1>QrCode Venceu!</h1>');
                    }
                }
            });
        }

        $(document).ready(function() {
            setInterval(checkPaymentStatus, 5000); // Check every 5 seconds
        });
    </script>
</head>
<body>
    <img src="<?php echo qr2code(getqr($_SESSION['idmp'])); ?>" alt="Pix QR Code">
    <span><?php echo getqr($_SESSION['idmp']); ?></span>
</body>
</html>