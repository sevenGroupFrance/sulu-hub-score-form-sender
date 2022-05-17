# sevengroupfrance/sulu-hub-score-form-sender-bundle

1 - create `sulu_hub_score_form_sender.yaml` file in your `config/packages/` folder.\
2 - Then add this configuration to it:
```
sulu_hub_score_form_sender:
    login:
        id: your_id
        pwd: your password
```
3 - download this bundle `composer require sevengroupfrance/sulu-hub-score-form-sender-bundle`\
4 - then download this bundle `composer require sevengroupfrance/hub-score-api-bundle`\
5 - lastly configure your hub-score-api-bundle `HubScoreApi.php`'s sendForm function to make the processsed parameters logical to your form configuration.
