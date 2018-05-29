<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
//        var_dump('REQUEST',$content["queryResult"]["parameters"]["ville"]);
        return $this->json(array('username' => 'jane.doe'));

    }

    public function webhook(Request $request)
    {
        var_dump($request);die;
    }

}