<?php

namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\EventSubscriber;

use Swift_Events_SendEvent;
use Swift_Events_SendListener;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SuluHubScoreFormSender implements Swift_Events_SendListener
{

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var string $pwd
     */
    private $pwd;

    /**
     * @var string $base_url
     */
    private $base_url;

    /**
     * @var string $login_url
     */
    private $login_url;

    /**
     * @var int $campagn_id
     */
    private $campagn_id;

    /**
     * @var int $database_id
     */
    private $database_id;

    /**
     * @var string $token
     */
    private $token;

    /**
     * @var string $send_mail_url
     */
    private $send_mail_url;

    /**
     * @var array $idArray
     */
    private $idArray = [];

    /**
     * @var HttpClientInterface $client
     */
    private $client;

    /**
     * @var FlashBagInterface $flashBag
     */
    private $flashBag;

    public function __construct(
        string $id = '',
        string $pwd = '',
        string $base_url = '',
        string $login_url = '',
        int $campagn_id = 0,
        int $database_id = 0,
        string $send_mail_url = '',
        HttpClientInterface $client,
        FlashBagInterface $flashBag
    ) {
        $this->id = $id;
        $this->pwd = $pwd;
        $this->base_url = $base_url;
        $this->login_url = $login_url;
        $this->campagn_id = $campagn_id;
        $this->database_id = $database_id;
        $this->send_mail_url = $send_mail_url;
        $this->client = $client;
        $this->flashBag = $flashBag;
    }

    private function login($id, $pwd, $base_url, $login_url)
    {
        $response = $this->client->request(
            'POST',
            'https://' . $base_url . $login_url,
            [
                'json' => [
                    'Username' => $id,
                    'Password' => $pwd
                ]
            ]
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 & $statusCode < 300) {
            $this->token = $response->toArray()['token'];
        } else {
            $this->token = null;
        }

        return $response;
    }

    public function beforeSendPerformed(Swift_Events_SendEvent $event)
    {
        // checks if the token is hydrated
        // if the token is hydrated, we skip this part, which is required only one time
        if (empty($this->token)) {
            // if it's the first time, the token is hydrated by the login function
            $this->login($this->id, $this->pwd, $this->base_url, $this->login_url);
            // if the token is invalid, then we redirect the user to the url they were on and we show an error flash message
            if (!isset($this->token) || empty($this->token)) {
                $this->flashBag->add('error', "Une erreur est survenue, merci de réessayer ou de contacter un administrateur si l'erreur persiste.");
                header("Location: " . $this->getUrl(), false);
                exit;
            }
        }
    }

    public function sendPerformed(Swift_Events_SendEvent $event)
    {
        $messageId = $event->getMessage()->getId();
        // if the messageId is not in the idArray
        if (!in_array($messageId, $this->idArray, true)) {
            // insert id in array and execute send mail api request
            $this->idArray[] = $messageId;

            $response = $this->client->request(
                'POST',
                'https://' . $this->base_url . $this->send_mail_url,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-Type' => 'application/json'
                    ],
                    "json" =>
                    [
                        "userMail" => $this->getMessageTo($event->getMessage()->getTo()),
                        "campagnId" => $this->campagn_id,
                        "databaseId" => $this->database_id,
                        "html" => $event->getMessage()->getBody()
                    ]
                ]
            );

            return $response;
        }
        // else, do nothing
        return;
    }

    public function getUrl()
    {
        return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    private function getMessageTo($toArray)
    {
        foreach ($toArray as $key => $value) {
            break;
        }
        return $key;
    }
}
