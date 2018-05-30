<?php

namespace App\Service;


class GeoInformation
{
    public function getDepartement($city)
    {
        $data = $this->getCityData($city);
        $dep = json_decode(file_get_contents("https://geo.api.gouv.fr/departements?code=" . $data->codeDepartement . "&fields=nom,code"));
        return $dep[0];
    }

    public function getRegion($city)
    {
        $data = $this->getCityData($city);
        $reg = json_decode(file_get_contents("https://geo.api.gouv.fr/regions?code=" . $data->codeRegion . "&fields=nom,code"));
        return $reg[0];
    }

    public function getCityData($city)
    {
        $data = json_decode(file_get_contents("https://geo.api.gouv.fr/communes?nom=" . $this->skip_accents($city) . "&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre"));

        if (count($data) > 0) {
            foreach ($data as $c) {
                if ($c->nom === $city) {
                    $response = $c;
                }
            }
        }
        return $response;
    }

    public function traitementResponse($outputContexts, $textResponse)
    {
        foreach ($outputContexts as $output) {
            $city = $output['parameters']['ville'] ?? null;
        }
        if ($city != null) {

            switch ($textResponse) {
                case 'region':
                    $responseData = $this->getRegion($city);
                    $response     = [$city . ' se situe dans la région de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'departement':
                    $responseData = $this->getDepartement($city);
                    $response     = [$city . ' se situe dans le département de ' . $responseData->nom . ' (' . $responseData->code . ')'];
                    break;
                case 'population':
                    $responseData = $this->getCityData($city);
                    $response     = ['A  ' . $responseData->nom . ", il y une population de " . number_format($responseData->population) . ' hab'];
                    break;
                case 'codePostal':
                    $responseData = $this->getCityData($city);
                    $response     = [$city . ' possède les codes postaux suivant : '];
                    foreach ($responseData->codesPostaux as $key => $data) {
                        array_push($response, $key+1 . ' : ' . $data);
                    }
                    break;
            }
        } else {
            $response = ["Oups je n'ai pas bien compris votre demande"];
        }
        return $response;
    }

    private function skip_accents($str, $charset = 'utf-8')
    {

        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }
}