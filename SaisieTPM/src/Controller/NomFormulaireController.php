<?php

namespace App\Controller;

use App\Entity\Formulaire;
use App\Form\CreationFormulaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NomFormulaireController extends AbstractController
{
    #[Route('/nomformulaire', name: 'app_nom_formulaire')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {

        $creationForm = new Formulaire();

        $form = $this->createForm(CreationFormulaireType::class, $creationForm);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            $creationForm->setRelation($user);

            $manager->persist($creationForm);

            $manager->flush();

            return $this->redirectToRoute("app_creation_champs");
        }

        return $this->render('nom_formulaire/index.html.twig', [
            'form' => $form,
        ]);
    }
}
