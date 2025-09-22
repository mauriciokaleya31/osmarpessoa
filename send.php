<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "info@onlyvibes.online";
    $subject = "Nova Inscrição - Webinar CX";
    
    $nome = $_POST['nome'];
    $whatsapp = $_POST['whatsapp'];
    $email = $_POST['email'];
    $pais = $_POST['pais'];
    $objetivo = $_POST['objetivo'];

    $message = "Nova inscrição no Webinar CX:\n\n";
    $message .= "Nome: $nome\n";
    $message .= "WhatsApp: $whatsapp\n";
    $message .= "Email: $email\n";
    $message .= "País: $pais\n";
    $message .= "Objetivo: $objetivo\n\n";

    $headers = "From: noreply@onlyvibes.online";

    // upload do comprovativo
    if (isset($_FILES['comprovativo']) && $_FILES['comprovativo']['error'] == 0) {
        $file_tmp = $_FILES['comprovativo']['tmp_name'];
        $file_name = $_FILES['comprovativo']['name'];
        $file_size = $_FILES['comprovativo']['size'];
        $file_type = $_FILES['comprovativo']['type'];

        $handle = fopen($file_tmp, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));

        $boundary = md5("random");

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: noreply@onlyvibes.online\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($message));

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
        $body .= $encoded_content;

        $sentMail = mail($to, $subject, $body, $headers);
    } else {
        $sentMail = mail($to, $subject, $message, $headers);
    }

    if ($sentMail) {
        // 👉 Redireciona direto para a página de obrigado
        header("Location: obrigado.html");
        exit();
    } else {
        echo "<script>alert('Erro ao enviar. Tente novamente.'); window.history.back();</script>";
    }
}
?>
