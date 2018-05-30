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
                $responseData = $this->geoInformation->getCityData($data["parameters"]["ville"]);
                $response = ['Tu vis à ' . $responseData->nom, "Quelles informations souhaites-tu? (code postal, population, département, région)"];
                break;

            case 'region':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'region');
//                foreach ($data["outputContexts"] as $output) {
//                    $city = $output['parameters']['ville'] ?? null;
//                }
//                if ($city != null) {
//                    $responseData = $this->geoInformation->getRegion($city);
//                    $response = [$city . ' se situe dans la région de ' . $responseData->nom . ' (' . $responseData->code . ')'];
//                } else {
//                    $response = ["Oups je n'ai pas bien compris votre demande"];
//                }
                break;

            case 'population':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'population');
//                foreach ($data["outputContexts"] as $output) {
//                    $city = $output['parameters']['ville'] ?? null;
//                }
//                if ($city != null) {
//                    $responseData = $this->geoInformation->getCityData($city);
//                    $response = ['A  ' . $responseData->nom . ", il y une population de " . number_format($responseData->population) . ' hab'];
//                } else {
//                    $response = ["Oups je n'ai pas bien compris votre demande"];
//                }
                break;

            case 'departement':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'departement');
//                foreach ($data["outputContexts"] as $output) {
//                    $city = $output['parameters']['ville'] ?? null;
//                }
//                if ($city != null) {
//                    $responseData = $this->geoInformation->getDepartement($city);
//                    $response = [$city . ' se situe dans le département de ' . $responseData->nom . ' (' . $responseData->code . ')'];
//                } else {
//                    $response = ["Oups je n'ai pas bien compris votre demande"];
//                }
                break;

            case 'codePostal':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'codePostal');
//                foreach ($data["outputContexts"] as $output) {
//                    $city = $output['parameters']['ville'] ?? null;
//                }
//                if ($city != null) {
//                    $responseData = $this->geoInformation->getCityData($city);
//                    $response = array($city . ' possède les codes postaux suivant : ');
//                    foreach ($responseData->codesPostaux as $key => $data) {
//                        array_push($response, $key+1 . ' : ' . $data);
//                    }
//                } else {
//                    $response = ["Oups je n'ai pas bien compris votre demande"];
//                }
                break;

            default:
                $response = ["J'ai mal compris votre demande."];
                break;

        }
        return $response;
    }





}