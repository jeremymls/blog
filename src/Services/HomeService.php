<?php

namespace Application\Services;

use Core\Services\Service;
use Core\Services\MailService;
use Core\Services\ParamService;

class HomeService extends Service
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sendContactMail()
    {
        $paramServices = new ParamService();
        $mailService = new MailService();
        $mailService->sendEmail([
            'reply_to' => [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ],
            'recipient' => $mailService->getOwnerMail(),
            'subject' => 'Message de ' . $_POST['name'],
            'template' => 'contact',
            'template_data' => [
                'name' => $_POST['name'],
                'mail' => $_POST['email'],
                'phone' => $_POST['phone'],
                'message' => $_POST['message'],
                'site_name' => $paramServices->get("site_name")
            ],
            'success_message' => 'Votre message a bien été envoyé.'],
            [],
            false
        );
    }

}