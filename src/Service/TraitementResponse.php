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
                    $test = json_encode([
                        [
                            "fallback" => "You are unable to choose a game",
                            "callback_id" => "wopr_game",
                            "text" => "Choose a game to play",
                            "color" => "#3AA3E3",
                            "attachment_type" => "default",
                            "actions" => [
                                [
                                    "name" => "game",
                                    "text" => "Chess",
                                    "type" => "button",
                                    "value" => "chess"
                                ],
                                [
                                    "name" => "game",
                                    "text" => "Falken's Maze",
                                    "type" => "button",
                                    "value" => "maze"
                                ],
                                [
                                    "name" => "game",
                                    "text" => "Thermonuclear War",
                                    "style" => "danger",
                                    "type" => "button",
                                    "value" => "war",
                                    "confirm" => [
                                        "title" => "Are you sure?",
                                        "text" => "Wouldn't you prefer a good game of chess?",
                                        "ok_text" => "Yes",
                                        "dismiss_text" => "No"
                                    ]
                                ]
                            ]
                        ]
                    ]);
                    $client = new Client();
                    // Test message direct slack
                    $time = preg_split('/ /',$responseData['dt_txt']);
                    $response = ['Le '. date('d M Y',strtotime($time[0])) . ' à ' . date('G:i',strtotime($responseData['dt_txt'])) . ', il devrait faire une température de : ' . ceil($responseData['main']['temp']) . ' degrés.',
                        "il devrait tombé dans 3h : " . ceil($responseData['rain']['3h']) . 'mm de pluie'
                        ];
                    sleep(5);
                    $res = $client->request('POST', 'https://slack.com/api/chat.postMessage', [
                        'form_params' => [
                            'token'   => getenv('slack-api'),
                            'channel' => 'général',
                            'text'    => 'hello poulet',
                            "attachments"=> $test
                        ]
                    ]);
                    break;
            }
        } else {
            $response = ["Oups je n'ai pas bien compris votre demande"];
        }
        return $response;
    }
}