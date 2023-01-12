<?php

namespace App\Controller;

use App\Entity\Champs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(ManagerRegistry  $doctrine): Response
    {
        return $this->render('accueil/index.html.twig');
    }
}
