<?php

namespace App\Controller;

use App\Service\GeoInformation;
use App\Service\Webhook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $geoInformation;
    private $webhook;

    public function __construct(GeoInformation $geoInformation, Webhook $webhook)
    {
        $this->geoInformation = $geoInformation;
        $this->webhook        = $webhook;
    }

    public function index(Request $request)
    {
        $content      = json_decode($request->getContent(), true);
        $responseText = $this->webhook->getIntent($content['queryResult']);
        $response     = ['fulfillmentText' => 'Hello', 'fulfillmentMessages' => [
            [
//                'text' => ['text' => $responseText],
                'card' => [
                    'title' => 'test',
                    'text' => ['textttt', 'texxxxxxxxxxxxxxxxxxt'],
                    "imageUri" => "https://www.testclan.com/images/testbot/siege/weapons/assault-rifles.jpg"
                ],
            ]]];

        return $this->json($response);

    }
}