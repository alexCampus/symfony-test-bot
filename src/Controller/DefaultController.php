<?php

namespace App\Controller;

use App\Service\GeoInformation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $geoInformation;

    public function __construct(GeoInformation $geoInformation)
    {
        $this->geoInformation = $geoInformation;
    }

    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $test    = $this->webhook($content['queryResult']);
        $response = ['fulfillmentText' => 'Hello', 'fulfillmentMessages' => [['text' => ['text' => $test]]

        ]];

        return $this->json($response);

    }

    private function webhook($data)
    {
        switch ($data['intent']['displayName']) {
            case 'City':
                var_dump($data["outputContexts"]);die;
                $responseData = $this->geoInformation->getCityData($data["parameters"]["ville"]);
                $response = ['Tu vis à ' . $responseData->nom, "Quelles informations souhaites-tu? (code postal, population, département, région)"];
                break;

            case 'region':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'region');
                break;

            case 'population':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'population');
                break;

            case 'departement':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'departement');
                break;

            case 'codePostal':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'codePostal');
                break;

            default:
                $response = ["J'ai mal compris votre demande."];
                break;

        }
        return $response;
    }





}