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

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var string $pwd
     */
    private $pwd;

    /**
     * @var array $forms
     */
    private $forms;

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
        array $forms = [],
        HttpClientInterface $client,
        FlashBagInterface $flashBag
    ) {
        $this->id = $id;
        $this->pwd = $pwd;
        $this->forms = $forms;
        $this->client = $client;
        $this->flashBag = $flashBag;
    }


    /**
     * getSubscribedEvents function.
     * 
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormSavePostEvent::NAME => "hubAPI"
        ];
    }

    /**
     * hubAPI function.
     * Checks if the data from form event is of Dynamic type, then gets the form datas out of it.
     * Once done it hydrates the HubScoreApi object with the yaml configuration and the HttpClientInterface client.
     * Then it gets the response out of it and do the logic if it gets connected to the API.
     * If everything is good, it sends the form, then it gets another status code.
     * 
     * @param object FormSavePostEvent
     * @return void
     */
    public function hubAPI(FormSavePostEvent $event): void
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
