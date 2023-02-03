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
            'http://saisie/api/type_champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $response->toArray();
    }

    public function getChamps($token) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $response->toArray();
    }

    public function getChampsByUser($token, $idUser) {
        $listeChamps = $this->getChamps($token);

        $champsUtilisateur = [];
        foreach ($listeChamps['hydra:member'] as $key => $value) {
            if($value['utilisateur'] == '/api/users/'. $idUser) {
                $champsUtilisateur[$value['nom']] = $value['@id'];
            }
        }

        return $champsUtilisateur;
    }

    public function getCptForms($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $cptFormulaires = 0;
        $listeReponse = $response->toArray()['hydra:member'];
        foreach ($listeReponse as $value) {
            if($value['relation'] == '/api/users/'.$idUser) {
                $cptFormulaires++;
            }
        }

        return $cptFormulaires;
    }

    public function getFormByUser($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $listeReponse = $response->toArray()['hydra:member'];
        $listeForm = [];
        foreach ($listeReponse as $value) {
            if($value['relation'] == '/api/users/'.$idUser) {
                $listeForm[$value['nom']] = $value['@id'];
            }
        }

        return $listeForm;
    }

    public function deleteRessource($token, $uri) {
        $response = $this->client->request(
            'DELETE',
            'http://saisie'.$uri, [
                'headers' => ['Authorization' => 'Bearer '.$token]
            ]
        );
    }

    public function getCptChamps($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $cptChamps = 0;
        $listeReponse = $response->toArray()['hydra:member'];
        foreach ($listeReponse as $value) {
            if($value['utilisateur'] == '/api/users/'.$idUser) {
                $cptChamps++;
            }
        }

        return $cptChamps;
    }

    public function getCptRenvoieSaisie($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/renvoie_saisies', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $formulaire = $this->client->request(
            'GET',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $listeFormulaire = [];
        $formulaire = $formulaire->toArray()['hydra:member'];
        foreach($formulaire as $value){

            if($value['relation'] == '/api/users/'. $idUser) {
                array_push($listeFormulaire, $value['@id']);
            }
        }

        $cptRenvoieSaisie = 0;
        $listeReponse = $response->toArray()['hydra:member'];
        foreach ($listeReponse as $value) {
            if(in_array($value['fomulaire_id'], $listeFormulaire)) {
                $cptRenvoieSaisie++;
            }
        }

        return $cptRenvoieSaisie;
    }

    public function getUsername($token, $uri) {
        $username = $this->client->request(
            'GET',
            'http://saisie'.$uri, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $username;
    }

    public function getFormById($token, $id) {
        $formulaire = $this->client->request(
            'GET',
            'http://saisie/api/formulaires/'.$id, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $formulaire->toArray();
    }

    public function getByUri($token, $uri) {
        $fetchUri = $this->client->request(
            'GET',
            'http://saisie'.$uri, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $fetchUri->toArray();
    }



    public function getFormChampsByUriAndType($token, $id) {

        $formulaire = $this->getFormById($token, $id);

        $listeChamps = [];

        foreach ($formulaire['champs'] as $value) {
            $listeChamps[] = $this->getByUri($token, $value);
        }

        $listeType = [];

        foreach($listeChamps as $value) {
            $listeType[] = $this->getByUri($token, $value['id_type']);

            $listeTypage = [];
            foreach($listeType as $test) {
                $listeTypage[] = $test['Typage'];

                $listeChamps[$value['nom']] = $test['Typage'];
            }

            array_shift($listeChamps);
        }

        return $listeChamps;
    }

    public function getForm($token) {
        $formulaire = $this->client->request(
            'GET',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $formulaire->toArray();
    }

    public function getRenvoieSaisie($token) {
        $renvoie = $this->client->request(
            'GET',
            'http://saisie/api/renvoie_saisies', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $renvoie->toArray()['hydra:member'];
    }

    public function getRenvoieSaisieByForm($token, $idUser) {
        $response = $this->getRenvoieSaisie($token);

        $formulaire = $this->getForm($token);

        $listeFormulaire = [];
        foreach($formulaire['hydra:member'] as $value){

            if($value['relation'] == '/api/users/'. $idUser) {
                array_push($listeFormulaire, $value['@id']);
            }
        }

        $listeSaisie = [];
        $listeReponse = $response;
        foreach ($listeReponse as $value) {
            if(in_array($value['fomulaire_id'], $listeFormulaire)) {
                array_push($listeSaisie, $value);
            }
        }

        foreach($listeSaisie as $value) {
            $username = $this->getUsername($token, $value['user_id']);

            $nomForm = $this->client->request(
                'GET',
                'http://saisie'.$value['fomulaire_id'], [
                    'headers' => ['Authorization' => 'Bearer '. $token],
                ]
            );

            if(array_key_exists('piecejointe', $value)){
                $listeSaisie[] = array_merge(['username' => $username->toArray()['username'],'formulaire' => $nomForm->toArray()['nom'],'saisie' => $value['saisie'], 'piecejointe' => $value['piecejointe']]);
            } else {
                $listeSaisie[] = array_merge(['username' => $username->toArray()['username'],'formulaire' => $nomForm->toArray()['nom'],'saisie' => $value['saisie'], 'piecejointe' => null]);
            }

            array_shift($listeSaisie);
        }

        return $listeSaisie;
    }

    public function setRenvoieSaisie($token, $userId, $json, $formId, $piecejointe) {
        $response = $this->client->request(
            'POST',
            'http://saisie/api/renvoie_saisies', [
                'headers' => ['Authorization' => 'Bearer '. $token],
                'body' => [
                    'saisie' => $json,
                    'piecejointe' => $piecejointe,
                    'fomulaireId' => $formId,
                    'userId' => $userId,
                ]
            ]
        );
    }

    public function setFormulaire($token, array $data, UserInterface $user) {

        $nom = $data['formulaire']['nom'];

        $listeChampsAttribuer = $data['formulaire']['champs'];

        $utilisateur = '/api/users/'. $user->getId();

        $response = $this->client->request(
            'POST',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
                'body' => [
                    'nom' => $nom,
                    'relation' => $utilisateur,
                    'champs' => $listeChampsAttribuer,
                ]
            ]
        );
    }

    public function setChampsData($token, array $data, UserInterface $user)
    {

        $nom = $data['creation_champs']['nom'];

        $type = $data['creation_champs']['id_type'];

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