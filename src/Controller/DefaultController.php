<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $response = array(
            'fulfillmentText' => 'Hello',
            'fulfillmentMessages'=> array(
                array(
                    'text' => array(
                        'text' => array(
                            'Tu vis à ' . $content["queryResult"]["parameters"]["ville"]
                        )
                    )
                )

            )
        );

        $response1 = array(
            'fulfillmentText' => 'Hello',
            "messages"=> array(
                array(
                    "speech"=> "Text response",
                    "type"=> 0
                )
            )
        );

//        var_dump('REQUEST',$content["queryResult"]["parameters"]["ville"]);
        return $this->json($response1);

    }

    public function webhook(Request $request)
    {
        var_dump($request);die;
    }

}