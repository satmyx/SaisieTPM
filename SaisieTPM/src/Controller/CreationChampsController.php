<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Form\CreationChampsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationChampsController extends AbstractController
{
    #[Route('/creationchamps', name: 'app_creation_champs')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {

        $creationChamps = new Champs();

        $form = $this->createForm(CreationChampsType::class, $creationChamps);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {

            $creationChamps->setUtilisateur($user);

            $manager->persist($creationChamps);

            $manager->flush();

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Votre champ : '. $creationChamps->getNom(). ' a bien été enregistré');
        }

        return $this->render('creation_champs/index.html.twig', [
            'form' => $form,
        ]);
    }
}
