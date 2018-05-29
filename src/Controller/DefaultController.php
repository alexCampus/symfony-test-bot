<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
//        var_dump('REQUEST',$content["queryResult"]);
        $response = array(
            'fulfillmentText' => 'Hello',
            'fulfillmentMessages'=> array(
                array(
                    'text' => array(
                        'text' => array(
                            'Tu vis à ' . $content["queryResult"]["parameters"]["ville"],
                            'Tu vis à turlutuuu' . $content["queryResult"]["parameters"]["ville"]
                        )
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
            $response = 'Tu vis à ' . $data["parameters"]["ville"];
        } else {
            $response = "Malheureusement je ne connais pas cette ville....";
        }
        return $response;
    }

}