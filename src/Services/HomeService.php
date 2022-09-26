<?php

namespace Application\Services;

use Core\Services\Service;
use Core\Services\MailService;
use Core\Services\ConfigService;

class HomeService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sendContactMail()
    {
        $configService = new ConfigService();
        $mailService = new MailService();
        $mailService->sendEmail(
            [
            'reply_to' => [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ],
            'recipient' => $configService->getOwnerMailContact(),
            'subject' => 'Message de ' . $_POST['name'],
            'template' => 'contact',
            'template_data' => [
                'name' => $_POST['name'],
                'mail' => $_POST['email'],
                'phone' => $_POST['phone'],
                'message' => $_POST['message'],
                'cs_site_name' => $configService->getByName("cs_site_name")
            ],
            'success_message' => 'Votre message a bien été envoyé.'],
            [],
            false
        );
    }

}
