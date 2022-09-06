<?php

namespace Core\Services;

use Core\Services\FlashService;
use PHPMailer\PHPMailer\PHPMailer;

include_once('src/config/mail.php');

class MailService
{
    public function __construct()
    {
        $this->flashServices = new FlashService();
        $this->owner = htmlentities(MB_NAME);
        $this->mail = $this->getConfig();
    }

    private function getConfig()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();                                 //Send using SMTP
        $mail->Host       = MB_HOST;                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                        //Enable SMTP authentication
        $mail->Username   = MB_USER;                     //SMTP username
        $mail->Password   = MB_PASS;                     //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port       = 465;                         //TCP port
        $mail->setFrom(MB_USER, utf8_decode(MB_NAME));   //Set who the message is to be sent from
        return $mail;
    }

    public function sendEmail(array $config, array $mail_images=[], bool $signature = true, string $alt_body = null)
    {
        $this->mail->addReplyTo($config['reply_to']['email'], $config['reply_to']['name']);                 //Set an alternative reply-to address
        $this->mail->addAddress($config['recipient']['email'], utf8_decode($config['recipient']['name']));  //Set who the message is to be sent to
        $this->mail->Subject = utf8_decode($config['subject']);                                             //Set the subject line
        // template preparation //
        $msg = file_get_contents('src/config/templates_mail/partials/header.html');                         // Read and store the header
        $msg .= file_get_contents('src/config/templates_mail/'. $config['template'] .'.html');              // Read and store the body
        if ($signature) {
            $msg .= file_get_contents('src/config/templates_mail/partials/signature.html');                 // Read and store the signature
            // todo: dynamic signature
            $msg = str_replace('{{owner}}', htmlentities("Jérémy Monlouis"), $msg);
            $msg = str_replace('{{owner_mail}}', "contact@jeremy-monlouis.fr", $msg);
        }
        $msg .= file_get_contents('src/config/templates_mail/partials/footer.html');                        // Read and store the footer
        // Dynamise the message //
        $msg = str_replace('{{title}}', $config['subject'], $msg);
        foreach ($config["template_data"] as $key => $value) {
            $msg = str_replace('{{' . $key . '}}', htmlentities($value), $msg);
        }
        $this->mail->msgHTML($msg, __DIR__);                                                                // Convert HTML into a basic plain-text alternative body
        $this->mail->AltBody = $alt_body ?: 
        "Si vous ne parvenez pas à lire ce message, veuillez utiliser un navigateur compatible HTML.";      // Replace the plain text body with one created manually
        // Attach images
        foreach ($mail_images as $image) {
            $this->mail->addAttachment('src/config/templates_mail/images/' . $image);
        }
        $this->mail->addAttachment('src/config/templates_mail/images/logo.png');
        // Send the message, check for errors
        if (!$this->mail->send()) {
            throw new \Exception('Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo, 500);
        } else {
            $this->flashServices->success('E-mail envoyé', $config['success_message']);
        }
    }

    public function getOwnerMail()
    {
                // todo: dynamise getOwnerMail
        return ['name' => 'JM Projets','email' => "contact@jeremy-monlouis.fr"];
    }
}
