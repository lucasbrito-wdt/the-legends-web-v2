<?php

namespace App\Validators;

use GuzzleHttp\Client;

class ReCaptcha
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client;
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => config('captcha.secret'),
                    'response' => $value,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }
}
