<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $test    = $this->webhook($content['queryResult']);
//        var_dump('REQUEST',$content["queryResult"]);
        $response = ['fulfillmentText' => 'Hello', 'fulfillmentMessages' => [['text' => ['text' => $test]]

        ]];

//        var_dump('REQUEST',$content["queryResult"]["parameters"]["ville"]);
        return $this->json($response);

    }

    private function webhook($data)
    {
        switch ($data['intent']['displayName']) {
            case 'City':
                $responseData = $this->getCity($data["parameters"]["ville"]);
                $response = ['Tu vis à ' . $responseData->nom, "Quelles informations souhaites-tu? (code postal, population, département, région)"];
                break;

            case 'region':
                $response = $this->getRegion($data["outputContexts"]["parameters"]["ville"]);
                var_dump($response);die;
                break;

            case 'population':
                var_dump($data["parameters"]);die;
                break;

            case 'departement':
                var_dump($data["parameters"]);die;
                break;

            case 'codePostal':
                var_dump($data["parameters"]);die;
                break;

            default:
                $response = ["J'ai mal compris votre demande."];
                break;

        }
//        if ($data['intent']['displayName'] === 'City') {
//
//            if (count($city) > 0) {
//                foreach ($city as $c) {
//                    if ($c->nom === $data["parameters"]["ville"]) {
//                        $departement = $this->getDepartement($c->codeDepartement);
//                        $region      = $this->getRegion($c->codeRegion);
//                        $response    = ['Tu vis à ' . $c->nom, 'Cette ville est dans le département : ' . $departement->nom . '(' . $departement->code . ')', 'Cette ville est dans la région : ' . $region->nom . '(' . $region->code . ')', 'Elle a une population de : ' . number_format($c->population)];
//                        break;
//                    } else {
//                        $response = ["Malheureusement je ne connais pas cette ville...."];
//                    }
//                }
//            }
//
//        } else {
//            $response = ["J'ai mal compris votre demande."];
//        }
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

    private function getDepartement($code)
    {
        $dep = json_decode(file_get_contents("https://geo.api.gouv.fr/departements?code=" . $code . "&fields=nom,code"));
        return $dep[0];
    }

    private function getRegion($city)
    {
        $data = $this->getCity($city);
        var_dump($data);
        $reg = json_decode(file_get_contents("https://geo.api.gouv.fr/regions?code=" . $city->codeRegion . "&fields=nom,code"));
        var_dump($reg);
        return $reg[0];
    }

    private function getCity($city)
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

}