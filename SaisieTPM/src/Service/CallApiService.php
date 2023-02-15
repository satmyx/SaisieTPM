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

    /**
     * Permet de récupérer le type d'un champs.
     */
    public function getTypeChamps($token) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/type_champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $response->toArray();
    }

    /**
     * Permet de récupérer la liste des champs.
     */
    public function getChamps($token) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/champs', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $response->toArray();
    }

    /**
     * Permet de récupérer la liste des champs par utilisateur.
     */
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

    /**
     * Compteur permettant de retourner le nombre de formulaire par utilisateur (Stats).
     */
    public function getCptForms($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/formulaires?relation='.$idUser, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $cptFormulaires = $response->toArray()['hydra:totalItems'];

        return $cptFormulaires;
    }

    /**
     * Renvoie les formulaires d'un utilisateur.
     */
    public function getFormByUser($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/formulaires?relation='.$idUser, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $listeFormByUser = [];

        $listeForm = $response->toArray()['hydra:member'];

        foreach ($listeForm as $key => $value) {
            $listeFormByUser[$value['nom']] = $value['@id'];
        }

        return $listeFormByUser;
    }

    /**
     * Supprime une ressource en BDD via un URI donner.
     */
    public function deleteRessource($token, $uri) {
        $response = $this->client->request(
            'DELETE',
            'http://saisie'.$uri, [
                'headers' => ['Authorization' => 'Bearer '.$token]
            ]
        );
    }

    /**
     * Renvoie le nombre de champs par utilisateur (Stats).
     */
    public function getCptChamps($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/champs?utilisateur='.$idUser, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $cptChamps = $response->toArray()['hydra:totalItems'];

        return $cptChamps;
    }

    /**
     * Renvoie le nombre de renvoie par formulaire selon l'utilisateur (Stats).
     */
    public function getCptRenvoieSaisie($token, $idUser) {
        $response = $this->client->request(
            'GET',
            'http://saisie/api/renvoie_saisies?fomulaire_id.relation='.$idUser, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $countRenvoie = $response->toArray()['hydra:totalItems'];

        return $countRenvoie;
    }

    /**
     * Renvoie un formulaire selon son ID.
     */
    public function getFormById($token, $id) {
        $formulaire = $this->client->request(
            'GET',
            'http://saisie/api/formulaires/'.$id, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $formulaire->toArray();
    }

    /**
     * Renvoie une ressource présente en BDD selon l'URI donner.
     */
    public function getByUri($token, $uri) {
        $fetchUri = $this->client->request(
            'GET',
            'http://saisie'.$uri, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $fetchUri->toArray();
    }

    /**
     * Retourne la liste des champs par formulaire ainsi que leurs types.
     */
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

    /**
     * Renvoie la liste des formulaires.
     */
    public function getForm($token) {
        $formulaire = $this->client->request(
            'GET',
            'http://saisie/api/formulaires', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $formulaire->toArray();
    }

    /**
     * Renvoie toutes les saisies des formulaires.
     */
    public function getRenvoieSaisie($token) {
        $renvoie = $this->client->request(
            'GET',
            'http://saisie/api/renvoie_saisies', [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        return $renvoie->toArray()['hydra:member'];
    }

    /**
     * Renvoie les saisies d'un formulaire précis.
     */
    public function getRenvoieSaisieByForm($token, $idUser) {

        $response = $this->client->request(
            'GET',
            'http://saisie/api/renvoie_saisies?fomulaire_id.relation='.$idUser, [
                'headers' => ['Authorization' => 'Bearer '. $token],
            ]
        );

        $renvoie = $response->toArray()['hydra:member'];

        $listeSaisie = [];

        foreach($renvoie as $value) {

            $username = $value['user_id']['username'];

            $nomForm = $value['fomulaire_id']['nom'];

            if(array_key_exists('piecejointe', $value)){
                $listeSaisie[] = array_merge(['username' => $username,'formulaire' => $nomForm,'saisie' => $value['saisie'], 'piecejointe' => $value['piecejointe']]);
            } else {
                $listeSaisie[] = array_merge(['username' => $username,'formulaire' => $nomForm,'saisie' => $value['saisie'], 'piecejointe' => null]);
            }
        }

        return $listeSaisie;
    }

    /**
     * Permet d'envoyer une saisie d'un formulaire.
     */
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

    /**
     * Permet de faire un formulaire avec l'attribution des champs.
     */
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

    /**
     * Permet de faire la création d'un champs.
     */
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