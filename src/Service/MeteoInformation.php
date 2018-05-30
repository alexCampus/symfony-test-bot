<?php

namespace App\Service;


use DateTime;
use DateTimeZone;

class MeteoInformation
{
    public function getMeteo($city)
    {
        $meteo = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/forecast?q=" . $city . ",FR&APPID=8132f6a626e2f3877e96782d01180b12"));
        $data = $this->getPrevisionMeteo($meteo);
        return $data;
    }

    private function getPrevisionMeteo($meteo)
    {
        $now       = new DateTime();
        $now->setTimezone(new DateTimeZone('Europe/Paris'));
        $timestamp1 = $now->format('Y-m-d H:i:s');
        $timestamp = $now->getTimestamp();
        var_dump($timestamp1);die;
        return true;
    }
}