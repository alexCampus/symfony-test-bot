<?php

namespace App\Service;


use DateTime;
use DateTimeZone;

class MeteoInformation
{
    public function getMeteo($city)
    {
        $meteo = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/forecast?q=" . $city . ",FR&APPID=8132f6a626e2f3877e96782d01180b12&cnt=2"));
        $data = $this->getPrevisionMeteo($meteo);
        return $data;
    }

    private function getPrevisionMeteo($meteo)
    {
        $now       = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Paris'));
        $timestamp = $now->getTimestamp();
        foreach ($meteo['list'] as $data) {
            if ($data['dt'] > $timestamp) {
                $dataMeteo = $data;
            }
        }

        return $dataMeteo;
    }
}