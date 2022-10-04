<?php

namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\EventSubscriber;

use SevenGroupFrance\HubScoreApiBundle\EventSubscriber\HubScoreApi;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Sulu\Bundle\FormBundle\Entity\Dynamic;
use Sulu\Bundle\FormBundle\Event\FormSavePostEvent;

class SuluHubScoreFormSender implements EventSubscriberInterface
{
    private $id;
    private $pwd;
    private $forms;
    private $client;
    private $flashBag;

    public function __construct($id = '', $pwd = '', $forms = [], HttpClientInterface $client, FlashBagInterface $flashBag)
    {
        $this->id = $id;
        $this->pwd = $pwd;
        $this->forms = $forms;
        $this->client = $client;
        $this->flashBag = $flashBag;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormSavePostEvent::NAME => "hubAPI"
        ];
    }

    public function hubAPI(FormSavePostEvent $event)
    {
        $dynamic = $event->getData();

        if (!$dynamic instanceof Dynamic) {
            return;
        }

        $form = $dynamic->getForm()->serializeForLocale($dynamic->getLocale(), $dynamic);
        if ($form) {
            $apiCall = new HubScoreApi($this->id, $this->pwd, $this->forms, $this->client);
            $response = $apiCall->getResponse();
            if ($response->getStatusCode() === 200) {
                $login_token = $apiCall->getLoginToken();
                if ($login_token) {
                    $finalResponse = $apiCall->sendForm($this->client, $form);
                    $rep = $finalResponse['reponse'];
                    $messages = $finalResponse['messages'];
                    if ($rep->getStatusCode() >= 400) {
                        $this->flashBag->add('error', isset($messages['error']) ? $messages['error'] : "");
                        return;
                    }
                    $this->flashBag->add('success', isset($messages['success']) ? $messages['success'] : "");
                }
            }
        }
    }
}
