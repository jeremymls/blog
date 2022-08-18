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
        $this->mail->isSMTP();                                               //Send using SMTP
        $this->mail->Host       = 'mail49.lwspanel.com';                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                      //Enable SMTP authentication
        $this->mail->Username   = 'contact@jeremy-monlouis.fr';              //SMTP username
        $this->mail->Password   = 'Aaliyah_19';                              //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;               //Enable implicit TLS encryption
        $this->mail->Port       = 465;                                       //TCP port
        $this->mail->setFrom('contact@jeremy-monlouis.fr', 'Mailer');        //Set who the message is to be sent from
    }

    public function sendConfirmationEmail(string $email, string $name, string $token)
    {
        
        $this->mail->addReplyTo('contact@jeremy-monlouis.fr', $this->owner); //Set an alternative reply-to address
        $this->mail->addAddress($email, 'Nouvel utilisateur');               //Set who the message is to be sent to
        $this->mail->Subject = 'Validation de votre compte';                 //Set the subject line
        $msg = file_get_contents('core/Services/Mail/models/activate.html'); //Read an HTML message body from an external file
        //------------- Personalize the message -----------------------------//
        $name = htmlentities($name);
        $msg = str_replace('{{name}}', $name, $msg);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/confirmation/$token";
        $msg = str_replace('{{url}}', $url, $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        //-------------------------------------------------------------------//
        $this->mail->msgHTML($msg, __DIR__);                                 //convert HTML into a basic plain-text alternative body
        //Replace the plain text body with one created manually
        $this->mail->AltBody = "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url";
        //send the message, check for errors
        if (!$this->mail->send()) {
            $this->flash->danger('Erreur', 'Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo);
        } else {
            $this->flash->success('Succès', 'Un mail de confirmation vous a été envoyé. <br>Veuillez cliquer sur le lien contenu dans le mail pour valider votre compte (expire après 30mn).');
        }
    }

    public function sendContactEmail($post)
    {
        $this->mail->addReplyTo($post['email'], $post['name']); 
        $this->mail->addAddress('contact@jeremy-monlouis.fr', 'JM Projets');
        $this->mail->Subject = 'Message de ' . $post['name'];
        $msg = file_get_contents('core/Services/Mail/models/contact.html');
        $msg = str_replace('{{name}}', htmlentities($post['name']), $msg);
        $msg = str_replace('{{mail}}', $post['email'], $msg);
        $msg = str_replace('{{phone}}', $post['phone'], $msg);
        $msg = str_replace('{{message}}', htmlentities($post['message']), $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        $this->mail->msgHTML($msg, __DIR__);
        if (!$this->mail->send()) {
            throw new \Exception('Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo, 500);
        } else {
            $this->flash->success('Succès', 'Votre message a bien été envoyé.');
        }
    }

    public function sendForgetPasswordEmail(string $email, string $name, string $token)
    {
        $this->mail->addReplyTo('contact@jeremy-monlouis.fr', $this->owner); 
        $this->mail->addAddress($email, utf8_decode($name));
        $this->mail->Subject = utf8_decode('Mot de passe oublié');
        $msg = file_get_contents('core/Services/Mail/models/forget.html');
        $msg = str_replace('{{name}}', htmlentities($name), $msg);
        $url = "http://" . $_SERVER['SERVER_NAME'] . "/reset_password/$token";
        $msg = str_replace('{{url}}', $url, $msg);
        $msg = str_replace('{{owner}}', $this->owner, $msg);
        $this->mail->msgHTML($msg, __DIR__);
        $this->mail->AltBody = "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url";
        if (!$this->mail->send()) {
            throw new \Exception('Une erreur est survenue lors de l\'envoi du mail: ' . $this->mail->ErrorInfo, 500);
        } else {
            $this->flash->success('Succès', 'Un mail de réinitialisation de mot de passe vous a été envoyé. <br>Veuillez cliquer sur le lien contenu dans le mail pour réinitialiser votre mot de passe (expire après 30mn).');
        }
    }
}
