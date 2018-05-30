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

    private function skip_accents($str, $charset = 'utf-8')
    {

        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
        $str = preg_replace('#&[^;]+;#', '', $str);

        return $str;
    }
}