<?php

namespace App\Service;


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
                    $response     = [$city . ' se situe dans la r�gion de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'departement':
                    $responseData = $this->geoInformation->getDepartement($city);
                    $response     = [$city . ' se situe dans le d�partement de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'population':
                    $responseData = $this->geoInformation->getCityData($city);
                    $response     = ['A  ' . $responseData->nom . ", il y une population de " . number_format($responseData->population) . ' hab'];
                    break;
                case 'codePostal':
                    $responseData = $this->geoInformation->getCityData($city);
                    $response     = [$city . ' poss�de les codes postaux suivant : '];
                    foreach ($responseData->codesPostaux as $key => $data) {
                        array_push($response, $key+1 . ' : ' . $data);
                    }
                    break;
                case 'meteo':
                    $responseData = $this->meteoInfo->getMeteo($city);
//                    $response = ['A ' . $responseData->dt_txt . ', il fera une temp�rature de : ' . ceil($responseData->main->temp), "La probalit� de pluie dans les 3h est de : " . $responseData->rain['3h']*100 . '%'];
                    var_dump(preg_split('/ /',$responseData->dt_txt));die;
                    break;
            }
        } else {
            $response = ["Oups je n'ai pas bien compris votre demande"];
        }
        return $response;
    }
}