<?php

namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\EventSubscriber;

use SevenGroupFrance\HubScoreApiBundle\EventSubscriber\HubScoreApi;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sulu\Bundle\FormBundle\Entity\Dynamic;
use Sulu\Bundle\FormBundle\Event\FormSavePostEvent;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SuluHubScoreFormSender implements EventSubscriberInterface
{
    private $id;
    private $pwd;
    private $client;

    public function __construct($id = '', $pwd = '', HttpClientInterface $client) {
        $this->id = $id;
        $this->pwd = $pwd;
        $this->client = $client;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormSavePostEvent::NAME => "hubAPI"
        ];
    }

    public function hubAPI(FormSavePostEvent $event): void
    {
        $dynamic = $event->getData();

        if (!$dynamic instanceof Dynamic) {
            return;
        }

        $form = $dynamic->getForm()->serializeForLocale($dynamic->getLocale(), $dynamic);
        if ($form) {
            $apiCall = new HubScoreApi($this->id, $this->pwd, $this->client);
            $response = $apiCall->getResponse();
            $login_token = $apiCall->getLoginToken();
            if ($response->getStatusCode() === 200 && $login_token) {
                $apiCall->sendForm($this->client, $form);
            }
            /* $after_connect_statusCode = $after_connect_response->getStatusCode(); */
        }
    }
}
