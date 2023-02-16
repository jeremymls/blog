<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Core\Services;

use Core\Services\FlashService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * MailService
 *
 * Send emails
 *
 * @category Core
 * @package  Core\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class MailService
{
    protected $mail;
    protected $configService;
    protected $configStatus = false;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->configService = new ConfigService();
        $this->mail = $this->getMailConfig();
    }

    /**
     * Get Mail Config
     *
     * Get the mail configuration
     *
     * @return PHPMailer
     */
    private function getMailConfig()
    {
        if (
            $this->configService->getByName("mb_host") == ""
            || $this->configService->getByName("mb_user") == ""
            || $this->configService->getByName("mb_pass") == ""
        ) {
            $this->configStatus = false;
        } else {
            $this->configStatus = true;
        }

        $mail = new PHPMailer();
        //Send using SMTP
        $mail->isSMTP();
        //Set the SMTP server to send through
        $mail->Host       = $this->configService->getByName("mb_host");
        //Enable SMTP authentication
        $mail->SMTPAuth   = true;
        //SMTP username
        $mail->Username   = $this->configService->getByName("mb_user");
        //SMTP password
        $mail->Password   = $this->configService->getByName("mb_pass");
        //Enable implicit TLS encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        //TCP port to connect to
        //use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $mail->Port       = 465;
        // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );
        $mail->setFrom(
            $this->configService->getByName("mb_user"),
            utf8_decode($this->configService->getByName("cs_site_name")) 
        );
        return $mail;
    }

    /**
     * Send Email
     *
     * Send an email
     *
     * @param array  $config      Array of configuration
     * @param array  $mail_images Array of images to attach
     * @param bool   $signature   Add signature
     * @param string $alt_body    Alternative body
     *
     * @return void
     */
    public function sendEmail(
        array $config,
        array $mail_images = [],
        bool $signature = true,
        string $alt_body = null
    ) {
        if (!$this->configStatus) {
            FlashService::getInstance()->danger(
                "Configuration manquante",
                'Veuillez configurer le serveur de messagerie'
            );
        } else {
            //Set an alternative reply-to address
            $this->mail->addReplyTo(
                $config['reply_to']['email'],
                $config['reply_to']['name']
            );
            //Set who the message is to be sent to
            $this->mail->addAddress(
                $config['recipient']['email'],
                utf8_decode($config['recipient']['name'])
            );
            //Set the subject line
            $this->mail->Subject = utf8_decode($config['subject']);
            // template preparation //
            // Read and store the header
            $msg = file_get_contents('src/config/templates_mail/partials/header.html');
            // Read and store the body
            $msg .= file_get_contents(
                'src/config/templates_mail/' . $config['template'] . '.html'
            );
            if ($signature) {
                // Read and store the signature
                $msg .= file_get_contents(
                    'src/config/templates_mail/partials/signature.html'
                );
                // Replace the owner name
                $msg = str_replace(
                    '{{owner}}',
                    htmlentities($this->configService->getByName("cs_owner_name")),
                    $msg
                );
                // Replace the owner mail
                $msg = str_replace(
                    '{{owner_mail}}',
                    $this->configService->getByName("cs_owner_email"),
                    $msg
                );
            }
            // Read and store the footer
            $msg .= file_get_contents('src/config/templates_mail/partials/footer.html');
            // Dynamise the message //
            $msg = str_replace('{{title}}', $config['subject'], $msg);
            foreach ($config["template_data"] as $key => $value) {
                // Replace the template data
                $msg = str_replace('{{' . $key . '}}', htmlentities($value), $msg);
            }
            // Convert HTML into a basic plain-text alternative body
            $this->mail->msgHTML($msg, __DIR__);
            $this->mail->AltBody = $alt_body ?:
            // Replace the plain text body with one created manually
            "Si vous ne parvenez pas à lire ce message,
            veuillez utiliser un navigateur compatible HTML.";
            // Attach images
            foreach ($mail_images as $image) {
                $this->mail->addAttachment('src/config/templates_mail/images/' . $image);
            }
            // Send the message, check for errors
            if (!$this->mail->send()) {
                throw new \Exception(
                    'Une erreur est survenue lors de l\'envoi du mail: '
                    . $this->mail->ErrorInfo,
                    500
                );
            } else {
                FlashService::getInstance()->success(
                    'E-mail envoyé',
                    $config['success_message']
                );
            }
        }
    }
}
