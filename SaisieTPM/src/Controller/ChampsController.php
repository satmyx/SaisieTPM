<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Form\SupprimerChampsType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChampsController extends AbstractController
{
    #[Route('/gererchamps', name: 'app_champs')]
    public function index(ManagerRegistry  $doctrine, EntityManagerInterface $manager, Request $request): Response
    {

        $form = $this->createForm(SupprimerChampsType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $idChamps = $form['nom']->getData()->getId();

            $doctrine->getRepository(Champs::class)->deleteChampsById($doctrine, $idChamps);
            
            $manager->flush();

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Supression effectuée avec succès');
        }

        return $this->render('champs/index.html.twig', [
            'form' => $form,
        ]);
    }
}
