<?php

namespace App\Service;


class Webhook
{
    private $geoInformation;

    public function __construct(GeoInformation $geoInformation)
    {
        $this->geoInformation = $geoInformation;
    }

    public function responseTraitement($data)
    {
        switch ($data['intent']['displayName']) {
            case 'City':
                $response = ['Tu souhaites des informations sur  ' . $data["parameters"]["ville"] . '.', "Quelles informations souhaites-tu? (code postal, population, département, région)"];
                break;

            case 'region':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'region');
                break;

            case 'population':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'population');
                break;

            case 'departement':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'departement');
                break;

            case 'codePostal':
                $response = $this->geoInformation->traitementResponse($data["outputContexts"], 'codePostal');
                break;

            default:
                $response = ["J'ai mal compris votre demande."];
                break;

        }
        return $response;
    }
}