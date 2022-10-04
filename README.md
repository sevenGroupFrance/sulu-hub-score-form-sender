# sevengroupfrance/sulu-hub-score-form-sender-bundle

1 - create `sulu_hub_score_form_sender.yaml` file in your `config/packages/` folder.\
2 - Then add this configuration to it:
```
sulu_hub_score_form_sender:
    login:
        id: your_id
        pwd: your password
    forms:
        "Name of your form title": { 
            config: { campaign_id: yourId, database_id: yourId }, 
            fields: [your, fields, here],
            messages:
                    {
                        error: "Your error message",
                        success: "Your success message",
                    },
        }

```
Be careful to always put `email` as the very first field, and put the other fields THE SAME ORDER AS THEY ARE CONFIGURED IN YOUR SULU FORM.\
3 - download this bundle `composer require sevengroupfrance/sulu-hub-score-form-sender-bundle`\
4 - then download this bundle `composer require sevengroupfrance/hub-score-api-bundle`\
5 - lastly configure your hub-score-api-bundle `HubScoreApi.php`'s sendForm function to make the processsed parameters logical to your form configuration.

### 1.1.0
You can now configure the fields you need, your campaign and database ID, for multiple forms.
