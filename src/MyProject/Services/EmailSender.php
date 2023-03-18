<?php

namespace MyProject\Services;

use MyProject\Models\Users\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailSender {

    public static function send(
            User $receiver,
            string $subject,
            string $templateName,
            array $templateVars = []
    ): string {
        extract($templateVars);

        ob_start();
        require __DIR__ . '/../../template/mail/' . $templateName; //эта разметка отправится письмом 
        $body = ob_get_contents();
        ob_end_clean();
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->CharSet = "utf-8";
            $mail->isSMTP();
            $mail->Host = '';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465; //если работать не будет, но можно поменять на 25 порт и закоменить $mail->SMTPSecure

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Recipients
            $mail->setFrom('', 'Пицца и роллы');
            $mail->addAddress($email, 'Joe User');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Подтверждение регистрации';
            $mail->Body = $body;

            $mail->send();

            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

}
