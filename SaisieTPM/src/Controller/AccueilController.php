<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(CallApiService $api): Response
    {
        $token = $this->getUser()->getApiKey();
        $listeForm = $api->getForm($token)['hydra:member'];

        return $this->render('accueil/index.html.twig', [
            'listeForm' => $listeForm,
        ]);
    }
}
