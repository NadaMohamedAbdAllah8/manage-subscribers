<?php

namespace App\Services;

use App\Models\Setting;
use GuzzleHttp\Client;

class SubscriberService
{
    private static $api_key = null;
    private static $headers = [];
    private $response;

    public function __construct()
    {
        $setting = Setting::first();

        // check: the api key in the database
        if (!is_null($setting) && !is_null($setting->mailer_lite_api_key)) {
            $this->setHeader($setting->mailer_lite_api_key);
        }
    }

    private function setHeader($mailer_lite_api_key)
    {
        self::$api_key = $mailer_lite_api_key;
        self::$headers = [
            'Content-Type' => 'application/json',
            'X-MailerLite-ApiKey' => self::$api_key,
        ];

    }
    public function validateAPIKey()
    {
        if (!is_null(self::$api_key)) {
            return true;
        }
        // insert the api key in the database
        $this->storeAPIKey();
        $setting = Setting::first();
        if ($this->checkAPIKey($setting)) {
            $this->setHeader($setting->mailer_lite_api_key);
            return true;
        } else {
            return false;
        }
    }

    public function store($request)
    {
        // call api to store

        $client = new Client(['base_uri' => 'https://api.mailerlite.com/api/v2/']);
        try {
            $form_params = [
                'email' => $request->email,
                'fields' => ['name' => $request->name,
                    'country' => $request->country],
            ];
            $response = $client->post('subscribers', [
                'headers' => self::$headers,
                'json' => $form_params,
            ]);
            // dd($response->getBody()->getContents());
            // dd($response);
            // dd($response->errors);
            // an error happened
            if ($response->statueCode != 200) {
                return ['success' => true,
                    'error_message' => 'there is an error'];
            }
            return ['success' => true,
                'error_message' => null];
        } catch (\Exception $e) {

            // $error_code = $e->getCode();
            // if ($error_code == 429) {
            //     $body = $response->getBody()->getContents();
            //     dd($body);

            //     return ['success' => false,
            //         'error_message' => 'Too many request, try again later.',
            //     ];
            // }

            // if ($error_code === 422) {
            //     $body = $response->getBody()->getContents();
            //     dd($body);
            //     $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

            //     return ['success' => false,
            //         'error_message' => 'Make sure that the email is valid.',
            //     ];
            // }

         //   dd($e->getMessage());
            return ['success' => false,
                'error_message' => $e->getMessage()
            ];

        }

    }

    private function storeAPIKey()
    {
        Setting::create([
            'mailer_lite_api_key' => config('mailer_lite.api_key'),
        ]);
    }

    // make a call to validate the api key
    private function checkAPIKey($setting)
    {
        echo 'will make a request to check api key';
        $client = new Client(['base_uri' => 'https://api.mailerlite.com/api/v2/']);
        try {
            $client->get('subscribers', [
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