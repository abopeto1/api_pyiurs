<?php

/*
 * This file is part of the API for Pyiurs Boutique POS.
 *
 * (c) Arnold Bopeto <abopeto1@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Orange;

use App\Entity\Customer;
use GuzzleHttp\Client;

class Api
{
    const URI_SMS = "/smsmessaging/v1/outbound/tel%3A%2B243899900151/requests";
    const PHONE_NUMBER = "243899900151";
    const TOKEN = "ZgcyxKCVPdSbe0LGC09A1oGPlDKc";

    private $orangeClient;

    public function __construct(Client $orangeClient)
    {
        $this->orangeClient = $orangeClient;
    }

    public function postMessage(Customer $client, $message)
    {
        $body["outboundSMSMessageRequest"]["address"] = "tel:+243". $client->getTelephone();
        $body["outboundSMSMessageRequest"]["outboundSMSTextMessage"]["message"] = $message;
        $body["outboundSMSMessageRequest"]["senderAddress"] = "tel:+" . self::PHONE_NUMBER;
        $body["outboundSMSMessageRequest"]["senderName"] = "Pyiurs";

        $config = [
            'body'   => json_encode($body),
            "headers" => [
                'Authorization' => 'Bearer ' . self::TOKEN,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ]
        ];
        $uri = "/smsmessaging/v1/outbound/tel%3A%2B". self::PHONE_NUMBER ."/requests";
        $response = $this->orangeClient->post($uri, $config);

        return $response->getBody();
    }
}
