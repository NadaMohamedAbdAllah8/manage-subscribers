<?php

namespace App\Services;

use App\Models\Setting;
use GuzzleHttp\Client;

class SubscriberService
{
    public function validateAPIKey()
    {
        $setting = Setting::first();

        // insert the api key in the database
        if (!is_null($setting) && !is_null($setting->mailer_lite_api_key)) {
            return true;
        }

        $this->storeAPIKey();
        // make a call to validate the api key
        $setting = Setting::first();

        return $this->checkAPIKey($setting);
    }

    private function storeAPIKey()
    {
        Setting::create([
            'mailer_lite_api_key' => config('mailer_lite.api_key'),
        ]);
    }

    private function checkAPIKey($setting)
    {
        $client = new Client(['base_uri' => 'https://api.mailerlite.com/api/v2/']);

        try {
            $response = $client->get('subscribers', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-MailerLite-ApiKey' => $setting->mailer_lite_api_key,
                ],
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }

    }
}
