<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Mpesa
{
    public static function securityCredentials()
    {
        try {
            $public_key = Storage::get('mpesa-cert.cer');
            $initiator_pw = config('services.mpesa.initiator_password');
            openssl_public_encrypt($initiator_pw, $encrypted, $public_key, OPENSSL_PKCS1_PADDING);
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function password()
    {
        $passkey = config('services.mpesa.passkey');
        $business_shortcode = config('services.mpesa.shortcode');
        $timestamp = Carbon::now()->format('YmdHis');
        return base64_encode($business_shortcode . $passkey . $timestamp);
    }

    public static function stkPushUrl()
    {
        if (config('services.mpesa.environment') === 'sandbox') {
            return config('services.mpesa.stk_push_url_sandbox');
        } elseif (config('services.mpesa.env') === 'live') {
            return config('services.mpesa.stk_push_url_live');
        } else {
            return config('services.mpesa.stk_push_url_sandbox');
        }
    }

    public static function accessTokenUrl()
    {
        if (config('services.mpesa.environment') === 'sandbox') {
            return config('services.mpesa.access_token_url_sandbox');
        } elseif (config('services.mpesa.env') === 'live') {
            return config('services.mpesa.access_token_url_live');
        } else {
            return config('services.mpesa.access_token_url_sandbox');
        }
    }

    public static function b2CUrl()
    {
        if (config('services.mpesa.environment') === 'sandbox') {
            return config('services.mpesa.b2c_url_sandbox');
        } elseif (config('services.mpesa.env') === 'live') {
            return config('services.mpesa.b2c_url_live');
        } else {
            return config('services.mpesa.b2c_url_sandbox');
        }
    }

    public static function accessToken()
    {
        $consumer_key = config('services.mpesa.consumer_key');
        $consumer_secret = config('services.mpesa.consumer_secret');
        $credentials = base64_encode($consumer_key . ":" . $consumer_secret);
        $url = self::accessTokenUrl();

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $credentials));
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            $access_token = json_decode($curl_response);
            return $access_token->access_token;
        } catch (\Exception $e) {
            info($e);
            return false;
        }
    }
}
