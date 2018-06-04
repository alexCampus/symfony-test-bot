<?php

namespace App\Service;


class Webhook
{
    private $traitementResponse;


    public function __construct(TraitementResponse $traitementResponse)
    {
        $this->traitementResponse = $traitementResponse;
    }

    public function getIntent($data)
    {
        switch ($data['intent']['displayName']) {
            case 'City':
                $response = ['Tu souhaites des informations sur  ' . $data["parameters"]["ville"] . '.', "Quelles informations souhaites-tu? (code postal, population, département, région, meteo)"];
                break;

            case 'region':
                $response = $this->traitementResponse->traitementResponse($data["outputContexts"], 'region');
                break;

            case 'population':
                $response = $this->traitementResponse->traitementResponse($data["outputContexts"], 'population');
                break;

            case 'departement':
                $response = $this->traitementResponse->traitementResponse($data["outputContexts"], 'departement');
                break;

            case 'codePostal':
                $response = $this->traitementResponse->traitementResponse($data["outputContexts"], 'codePostal');
                break;

            case 'Meteo':
                var_dump($data);
                $response = $this->traitementResponse->traitementResponse($data["outputContexts"], 'meteo');
                break;

            default:
                $response = ["J'ai mal compris votre demande."];
                break;

        }
        return $response;
    }
}