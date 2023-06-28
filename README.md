# sevengroupfrance/sulu-hub-score-form-sender-bundle

1 - create `sulu_hub_score_form_sender.yaml` file in your `config/packages/` folder.\
2 - Then add this configuration to it:
```
sulu_hub_score_form_sender:
    base_configuration:
        id: '%env(HUBSCORE_USERNAME)%'
        pwd: '%env(HUBSCORE_PASSWORD)%'
        base_url: your.base-url.com

    login_url: /yourloginurl

    payload_configuration:
        campagn_id: '%env(HUB_SCORE_SEND_MAIL_CAMPAGN_ID)%'
        database_id: '%env(HUB_SCORE_SEND_MAIL_DATABASE_ID)%'

    send_mail_url: /yoursendmailurl

```
3 - download this bundle `composer require sevengroupfrance/sulu-hub-score-form-sender-bundle`\

This bundle is for Sulu 2.4 and prior, as it uses swiftmailer to make this work.
If you're using Sulu 2.5, prefer using [Symfony mailer](https://symfony.com/doc/current/mailer.html)

### 1.1.0
You can now configure the fields you need, your campaign and database ID, for multiple forms.

### 1.1.1
You can now configure flashbag message (error and success only)

## 1.2.0
Bundle updated to use swiftmailer to watch mail send event instead of kernel event FormPostData.
