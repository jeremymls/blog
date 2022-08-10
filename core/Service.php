<?php

namespace Core;

use Application\Repositories\CommentRepository;
use Application\Lib\DatabaseConnection;
use Application\Repositories\PostRepository;
use Application\Repositories\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Service
{

    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->postRepository = new PostRepository();
        $this->postRepository->connection = new DatabaseConnection();
        $this->commentRepository = new CommentRepository();
        $this->commentRepository->connection = new DatabaseConnection();
        $this->userRepository = new UserRepository();
        $this->userRepository->connection = new DatabaseConnection();
    }

    public function validateForm(array $input, array $requiredFields = [])
    {
        $conditions = [];
        foreach ($requiredFields as $key => $field) {
            $conditions[] = !empty($input[$field])?'ok':'ko';
        }
        if (!in_array("ko", $conditions) ){
            foreach ($input as $key => $value) {
                $this->model->$key = $value;
            } 
        return $this->model;
        }else {
                throw new \Exception('Les données du formulaire sont invalides.');
        }
    }

    public function pagination($params, $entities, $nbp = 3)
    {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        $nbPage = ceil(count($params[$entities]) / $nbp);
        $params[$entities] = array_slice($params[$entities], ($page - 1) * $nbp, $nbp);
        $params['nbPage'] = $nbPage;
        return $params;
    }

    public function flash(string $type, string $title, string $message)
    {
        $html = '<div class="container"><div class="alert alert-';
        $html .= $type;
        $html .= ' alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3><strong>';
        $html .= strtoupper($title);
        $html .= '</strong></h3><p>';
        $html .= $message;
        $html .= '</p></div></div>';
        setcookie("flash", $html, time() + 3, "/");
    }

    public function sendConfirmationEmail(string $email, string $name, string $token)
    {
            //Server settings
            // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'mail49.lwspanel.com';                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = 'admin@jm-projets.fr';                     //SMTP username
            $this->mail->Password   = 'aT7_JvDwM9k76aP';                               //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                
        //Set who the message is to be sent from
        $this->mail->setFrom('admin@jm-projets.fr', 'Mailer');
        //Set an alternative reply-to address
        $this->mail->addReplyTo('contact@jeremy-monlouis.fr', 'Jérémy Monlouis');
        //Set who the message is to be sent to
        $this->mail->addAddress($email, $name);
        //Set the subject line
        $this->mail->Subject = 'Validation de votre compte';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $url = __DIR__ . "/validate/$token";
        $msg = file_get_contents('core/ModelMail/activate.html');
        $msg = str_replace('{{name}}', $name, $msg);
        $msg = str_replace('{{url}}', $url, $msg);
        $this->mail->msgHTML($msg, __DIR__);
        //Replace the plain text body with one created manually
        $this->mail->AltBody = "Si vous ne parvenez pas à lire ce message, veuillez copier/coller le lien suivant dans votre navigateur: $url";
        //Attach an image file
        
        //send the message, check for errors
        if (!$this->mail->send()) {
        echo 'Mailer Error: ' . $this->mail->ErrorInfo;
        } else {
        echo 'Message sent!';
        }
    }
}