<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function index(Request $request, Response $response)
    {
        $content = json_decode($request->getContent(), true);
        $data = json_decode($response->getBody()->getContents(), true);
        var_dump('REQUEST',$content);
        var_dump('RESPONSE',$data);
        die;
        return $this->render('index.html.twig');
    }

    public function webhook(Request $request)
    {
        var_dump($request);die;
    }

}