<?php

namespace Core\Services\Mail;

use Core\Services\Flash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    public function __construct()
    {
        $this->flash = new Flash();
        $this->owner = htmlentities("Jérémy Monlouis");
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();                                         //Send using SMTP
        $this->mail->Host       = 'mail49.lwspanel.com';               //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                //Enable SMTP authentication
        $this->mail->Username   = 'contact@jeremy-monlouis.fr';        //SMTP username
        $this->mail->Password   = 'Aaliyah_19';                        //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         //Enable implicit TLS encryption
        $this->mail->Port       = 465;
        //Set who the message is to be sent from
        $this->mail->setFrom(
            'contact@jeremy-monlouis.fr', 
            'Mailer'
        );
    }

    public function sendConfirmationEmail(string $email, string $name, string $token)
    {
        //Set an alternative reply-to address
        $this->mail->addReplyTo('contact@jeremy-monlouis.fr', $this->owner); 
        //Set who the message is to be sent to
        $this->mail->addAddress($email, 'Nouvel utilisateur');
        //Set the subject line
        $this->mail->Subject = 'Validation de votre compte';
        //Read an HTML message body from an external file
        $msg = file_get_contents('core/Services/Mail/models/activate.html');
        //Personalize the message
        $name = htmlentities($name);
        $msg = str_replace('{{name}}', $name, $msg);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/confirmation/$token";
        $msg = str_replace('{{url}}', $url, $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($msg, __DIR__);
        //Replace the plain text body with one created manually
        $this->mail->AltBody = "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url";
        //send the message, check for errors
        if (!$this->mail->send()) {
            $this->flash->danger('Erreur', 'Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo);
        } else {
            $this->flash->success('Succès', 'Un mail de confirmation vous a été envoyé.');
        }
    }

    public function sendContactEmail($post)
    {
        //Set an alternative reply-to address
        $this->mail->addReplyTo($post['email'], $post['name']); 
        //Set who the message is to be sent to
        $this->mail->addAddress('contact@jeremy-monlouis.fr', 'JM Projets');
        //Set the subject line
        $this->mail->Subject = 'Message de ' . $post['name'];
        //Read an HTML message body from an external file
        $msg = file_get_contents('core/Services/Mail/models/contact.html');
        //Personalize the message
        $msg = str_replace('{{name}}', htmlentities($post['name']), $msg);
        $msg = str_replace('{{mail}}', $post['email'], $msg);
        $msg = str_replace('{{phone}}', $post['phone'], $msg);
        $msg = str_replace('{{message}}', htmlentities($post['message']), $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($msg, __DIR__);
        //send the message, check for errors
        if (!$this->mail->send()) {
            throw new \Exception('Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo, 500);
        } else {
            $this->flash->success('Succès', 'Votre message a bien été envoyé.');
        }
    }

    public function sendForgetPasswordEmail(string $email, string $name, string $token)
    {
        //Set an alternative reply-to address
        $this->mail->addReplyTo('contact@jeremy-monlouis.fr', $this->owner); 
        //Set who the message is to be sent to
        $this->mail->addAddress($email, utf8_decode($name));
        //Set the subject line
        $this->mail->Subject = utf8_decode('Mot de passe oublié');
        //Read an HTML message body from an external file
        $msg = file_get_contents('core/Services/Mail/models/forget.html');
        //Personalize the message
        $msg = str_replace('{{name}}', htmlentities($name), $msg);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/reset_password/$token";
        $msg = str_replace('{{url}}', $url, $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($msg, __DIR__);
        //Replace the plain text body with one created manually
        $this->mail->AltBody = "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url";
        //send the message, check for errors
        if (!$this->mail->send()) {
            throw new \Exception('Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo, 500);
        } else {
            $this->flash->success('Succès', 'Votre message a bien été envoyé.');
        }
    }

}
