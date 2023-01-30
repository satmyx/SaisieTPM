<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getTypeChamps($token) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $response->toArray();
    }

    public function setChampsData($token, array $data, UserInterface $user)
    {

        $nom = $data['creation_champs']['nom'];

        $type = '/api/type_champs/'. $data['creation_champs']['id_type'];

        $utilisateur = '/api/users/'. $user->getId();

        $response = $this->client->request(
            'POST',
            'http://saisie/api/champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
                'body' => [
                    'nom' => $nom,
                    'choisir' => [],
                    'utilisateur' => $utilisateur,
                    'idType' => $type, 
                ]
            ]
        );
    }
}