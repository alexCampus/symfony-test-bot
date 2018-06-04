<?php

namespace App\Service;


use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class TraitementResponse
{
    private $geoInformation;
    private $meteoInfo;

    public function __construct(GeoInformation $geoInformation, MeteoInformation $meteoInformation)
    {
        $this->geoInformation = $geoInformation;
        $this->meteoInfo      = $meteoInformation;
    }

    public function traitementResponse($outputContexts, $textResponse)
    {
        foreach ($outputContexts as $output) {
            $city = $output['parameters']['ville'] ?? null;
        }
        if ($city != null) {

            switch ($textResponse) {
                case 'region':
                    $responseData = $this->geoInformation->getRegion($city);
                    $response     = [$city . ' se situe dans la région de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'departement':
                    $responseData = $this->geoInformation->getDepartement($city);
                    $response     = [$city . ' se situe dans le département de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'population':
                    $responseData = $this->geoInformation->getCityData($city);
                    $response     = ['A  ' . $responseData->nom . ", il y une population de " . number_format($responseData->population) . ' hab'];
                    break;
                case 'codePostal':
                    $responseData = $this->geoInformation->getCityData($city);
                    $response     = [$city . ' possède les codes postaux suivant : '];
                    foreach ($responseData->codesPostaux as $key => $data) {
                        array_push($response, $key+1 . ' : ' . $data);
                    }
                    break;
                case 'meteo':
                    $responseData = $this->meteoInfo->getMeteo($city);
                    // Test message direct slack
                    $client = new Client();
                    $res = $client->request('POST', 'https://slack.com/api/chat.postMessage', [
                        'form_params' => [
                            'token'   => 'xoxp-371098517505-371098517713-376014330567-066539b85aa89593ff9fd045740ab3fa',
                            'channel' => 'général',
                            'text'    => 'hello poulet',
                            "attachments"=> [
                                [
                                    "fallback"=> "Required plain-text summary of the attachment.",
                                    "text"=> "Optional text that appears within the attachment",
                                    "image_url"=> "http://my-website.com/path/to/image.jpg",
                                    "thumb_url"=> "http://example.com/path/to/thumb.png"
                                ]
                            ]
                        ]
                    ]);
                    // Test message direct slack
//                    $time = preg_split('/ /',$responseData['dt_txt']);
//                    $response = ['Le '. date('d M Y',strtotime($time[0])) . ' à ' . date('G:i',strtotime($responseData['dt_txt'])) . ', il devrait faire une température de : ' . ceil($responseData['main']['temp']) . ' degrés.',
//                        "il devrait tombé dans en 3h : " . ceil($responseData['rain']['3h']) . 'mm de pluie',
//                        'http://openweathermap.org/img/w/' . $responseData['weather'][0]['icon'] . '.png'];


                    break;
            }
        } else {
            $response = ["Oups je n'ai pas bien compris votre demande"];
        }
        return $response;
    }
}