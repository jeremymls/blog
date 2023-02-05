<?php

/**
 * Created by Jérémy MONLOUIS
 * php version 7.4.3
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */

namespace Application\Services;

use Core\Models\MailContactModel;
use Core\Services\Service;
use Core\Services\MailService;
use Core\Services\ConfigService;
use Core\Services\CsrfService;

/**
 * HomeService
 *
 * Home Service
 *
 * @category Application
 * @package  Application\Services
 * @author   Jérémy MONLOUIS <contact@jeremy-monlouis.fr>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/jeremymls/blog
 */
class HomeService extends Service
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Send Contact Mail
     *
     * Send contact mail
     *
     * @param mixed $data Data
     *
     * @return void
     */
    public function sendContactMail($data)
    {
        $csrfService = CsrfService::getInstance();
        if (!$csrfService->checkToken($data['csrf_token'])) {
            throw new \Exception('Le token CSRF est invalide ou n\'existe pas');
        }
        $configService = new ConfigService();
        $mailService = new MailService();
        $this->model = new MailContactModel();
        $data = $this->validateForm($data, $this->model->getFillable());
        $mailService->sendEmail(
            [
            'reply_to' => [
                'name' => $data->name,
                'email' => $data->email
            ],
            'recipient' => $configService->getOwnerMailContact(),
            'subject' => 'Message de ' . $data->name,
            'template' => 'contact',
            'template_data' => [
                'name' => $data->name,
                'mail' => $data->email,
                'phone' => $data->phone,
                'message' => $data->message,
                'cs_site_name' => $configService->getByName("cs_site_name")
            ],
            'success_message' => 'Votre message a bien été envoyé.'],
            [],
            false
        );
    }
}
