<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function index()
    {
        return $this->render('index.html.twig');
    }

    public function webhook(Request $request)
    {
        dump($request);
    }

}