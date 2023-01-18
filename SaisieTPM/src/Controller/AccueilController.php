<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Entity\Formulaire;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(ManagerRegistry  $doctrine): Response
    {
        $listeForm = $doctrine->getRepository(Formulaire::class)->findAll($doctrine);

        return $this->render('accueil/index.html.twig', [
            'listeForm' => $listeForm,
        ]);
    }
}
