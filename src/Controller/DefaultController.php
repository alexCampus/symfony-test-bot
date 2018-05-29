<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $test = $this->webhook($content['queryResult']);
//        var_dump('REQUEST',$content["queryResult"]);
        $response = array(
            'fulfillmentText' => 'Hello',
            'fulfillmentMessages'=> array(
                array(
                    'text' => array(
                        'text' => $test
                    )
                )

            )
        );

//        var_dump('REQUEST',$content["queryResult"]["parameters"]["ville"]);
        return $this->json($response);

    }

    private function webhook($data)
    {
        if ($data['intent']['displayName'] === 'City') {
            $city = json_decode(
                        file_get_contents("https://geo.api.gouv.fr/communes?nom=" . $this->skip_accents($data["parameters"]["ville"]) . "&fields=nom,code,codesPostaux,codeDepartement,codeRegion,population&format=json&geometry=centre")
            );
            if (count($city) > 0) {
                foreach ($city as $c) {
                    if ($c->nom === $data["parameters"]["ville"]) {
                        $departement = $this->getDepartement($c->codeDepartement);
                        $response = array(
                            'Tu vis à ' . $c->nom,
                            'Cette ville est dans le département : ' . $departement->nom . '(' . $departement->code . ')',
                            'Elle a une population de : ' . number_format($c->population)
                        );
                        break;
                    } else {
                        $response = ["Malheureusement je ne connais pas cette ville...."];
                    }
                }
            }

        } else {
            $response = ["J'ai mal compris votre demande."];
        }
        return $response;
    }

    private function skip_accents( $str, $charset='utf-8' ) {

        $str = htmlentities( $str, ENT_NOQUOTES, $charset );

        $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
        $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
        $str = preg_replace( '#&[^;]+;#', '', $str );

        return $str;
    }

    private function getDepartement($code)
    {
        $dep = json_decode(
            file_get_contents("https://geo.api.gouv.fr/departements?code=" . $code ."&fields=nom")
        );
        return $dep[0];
    }

}