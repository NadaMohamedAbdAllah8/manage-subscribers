<?php

namespace App\Services\SubscriberAdapters;

use App\Models\Setting;
use App\Services\Subscriber;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MailerLiteSubscriberAdapter implements Subscriber
{
    private static $api_key = null;
    private static $headers = [];
    private static $base_uri;

    public function __construct()
    {
        self::$base_uri = 'https://api.mailerlite.com/api/v2/';

        $setting = Setting::first();
        // check: the api key in the database
        if (!is_null($setting) && !is_null($setting->mailer_lite_api_key)) {
            $this->setHeader($setting->mailer_lite_api_key);
        }
    }

    private function setHeader($mailer_lite_api_key): void
    {
        self::$api_key = $mailer_lite_api_key;
        self::$headers = [
            'Content-Type' => 'application/json',
            'X-MailerLite-ApiKey' => self::$api_key,
        ];

    }

    public function validateAPIKey(): bool
    {
        if (!is_null(self::$api_key)) {
            return true;
        }

        // insert the api key in the database
        $setting = $this->storeAPIKey();
        if ($this->makeRequestToCheckAPIKey($setting)) {
            $this->setHeader($setting->mailer_lite_api_key);
            return true;
        } else {
            return false;
        }
    }

    public function listSubscribers(): array
    {
        $client = new Client(['base_uri' => self::$base_uri]);
        try {
            $response = $client->get('subscribers', [
                'headers' => self::$headers,
            ]);

            return ['success' => true, 'data' => null,
                'data' => ['subscribers' => json_decode($response->getBody()->getContents())], 'error_message' => null];
        } catch (ClientException $e) {
            $error_message = $this->errorMessagesToView($e->getResponse());
            return ['success' => false,
                'data' => null,
                'error_message' => $error_message,
            ];
        } catch (\Exception $e) {
            return ['success' => false,
                'subscribers' => [],
                'error_message' => $e->getMessage(),
            ];
        }
    }

    public function store($request): array
    {
        // call api to store
        $client = new Client(['base_uri' => self::$base_uri]);
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
            return [
                'success' => true,
                'error_message' => null,
                'data' => null,
            ];
        } catch (ClientException $e) {
            $error_message = $this->errorMessagesToView($e->getResponse());
            return ['success' => false,
                'error_message' => $error_message,
                'data' => null,
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'error_message' => $e->getMessage(), 'data' => null];
        }
    }

    private function storeAPIKey(): Setting
    {
        return Setting::create([
            'mailer_lite_api_key' => config('mailer_lite.api_key'),
        ]);
    }

    // make a call to validate the api key
    private function makeRequestToCheckAPIKey($setting): bool
    {
        //dd('will make a request to check api key');
        $client = new Client(['base_uri' => self::$base_uri]);
        try {
            $client->get('subscribers?limit=0', [
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

    private function errorMessagesToView($response): string
    {
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        $error_code = $response->getStatusCode();
        $error_message = "";
        // 400 Bad Request
        if ($error_code == 400) {
            $error_message = 'Please check your request.';
        }
        // 401 Unauthorized
        if ($error_code == 401) {
            $error_message = 'Please make sure the API key is valid.';
        }
        // 403 Forbidden
        if ($error_code == 403) {
            $error_message = 'Please check that you have the permission to perform this action.';
        }
        // 404 Not Found
        if ($error_code == 404) {
            $error_message = 'The endpoint is not found.';
        }
        // 422 Unprocessable Entity
        if ($error_code === 422) {
            $error_message = 'Validation error. ' . $data['error_details']['message'];
        }
        //429 Too Many Requests
        if ($error_code == 429) {
            $error_message = 'Too many request, please try again in a few minutes.';
        }
        // 500 Internal Server Error
        if ($error_code == 500) {
            $error_message = 'MailerLite returned an error, please try again in a few minutes.';
        }

        return $error_message;
    }
}
