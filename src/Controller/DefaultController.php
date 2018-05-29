<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        var_dump('REQUEST',$content["queryResult"]);
        die;
        return $this->render('index.html.twig');
    }

    public function webhook(Request $request)
    {
        var_dump($request);die;
    }

}