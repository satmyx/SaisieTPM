<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Entity\Formulaire;
use App\Form\FormulaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationFormulaireController extends AbstractController
{
    #[Route('/creationformulaire', name: 'app_creation_formulaire')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $creationForm = new Formulaire();

        $form = $this->createForm(FormulaireType::class, $creationForm);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {
            $creationForm->setRelation($user);
            $manager->persist($creationForm);
            $manager->flush();
        }

        return $this->render('creation_formulaire/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
