<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$phpmailer_path = './lib/phpmailer/';

$mailer_ready = false;
if (file_exists($phpmailer_path . 'Exception.php')) {
    require_once $phpmailer_path . 'Exception.php';
    require_once $phpmailer_path . 'PHPMailer.php';
    require_once $phpmailer_path . 'SMTP.php';
    $mailer_ready = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? strip_tags($_POST['nombre']) : 'Anónimo';
    $asistencia = isset($_POST['asistencia']) ? $_POST['asistencia'] : 'Desconocido';
    $motivo = isset($_POST['motivo']) ? strip_tags($_POST['motivo']) : '';
    $fecha = date('Y-m-d H:i:s');

    // 1. Guardar en CSV
    $archivo = 'confirmaciones.csv';
    $existe = file_exists($archivo);
    $fp = fopen($archivo, 'a');
    if (!$existe) {
        fputcsv($fp, ['Fecha', 'Nombre', 'Asistencia', 'Motivo']);
    }
    fputcsv($fp, [$fecha, $nombre, $asistencia, $motivo]);
    fclose($fp);

    // 2. Enviar por Correo
    $email_enviado = false;
    $error_info = '';

    if ($mailer_ready) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username = 'tu-correo@gmail.com';
            $mail->Password = 'tu-contraseña-de-aplicación';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('stevenchavez801@gmail.com', 'Confirmación de Retiro - Grupo Angelus');
            $mail->addAddress('stevenchavez801@gmail.com', 'Steven Chavez');

            $mail->isHTML(true);
            $mail->Subject = "RSVP: {$nombre} ha respondido a la invitación";
            
            $color = ($asistencia === 'Sí') ? '#003399' : '#dc3545';
            $motivo_html = !empty($motivo) ? "<p style='margin-top:20px; padding:15px; background:#f8f9fa; border-left:4px solid #dc3545;'><strong>Motivo de falta:</strong><br>{$motivo}</p>" : "";
            
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 25px; border: 2px solid #003399; border-radius: 15px;'>
                    <h2 style='color: #003399;'>Respuesta del Grupo Angelus</h2>
                    <p><strong>Líder:</strong> {$nombre}</p>
                    <p><strong>¿Asistirá?:</strong> <span style='color: {$color}; font-size: 1.2em; font-weight: bold;'>{$asistencia}</span></p>
                    {$motivo_html}
                    <hr>
                    <p style='font-size: 0.85em; color: #777;'>Confirmado el: {$fecha}</p>
                </div>";

            $mail->send();
            $email_enviado = true;
        } catch (Exception $e) {
            $error_info = $mail->ErrorInfo;
        }
    }

    echo json_encode([
        'status' => 'success',
        'email' => $email_enviado ? 'enviado' : 'error',
        'error_info' => $error_info
    ]);
}
?>
