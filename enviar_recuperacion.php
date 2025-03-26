<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado con Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo existe en la base de datos
    $conn = new mysqli("localhost", "root", "", "sistema_aulas");
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario) {
        // Generar un token único
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar el token en la base de datos
        $sql = "UPDATE usuarios SET reset_token = ?, reset_expira = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        // Enviar el correo
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.tudominio.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tuemail@tudominio.com';
            $mail->Password = 'tucontraseña';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tuemail@tudominio.com', 'Sistema de Aulas');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Recuperación de contraseña";
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: 
                <a href='http://localhost/sistema_aulas/reset_password.php?token=$token'>Recuperar contraseña</a>";

            $mail->send();
            echo "Correo enviado. Revisa tu bandeja de entrada.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    } else {
        echo "Correo no registrado.";
    }
    $conn->close();
}
?>
