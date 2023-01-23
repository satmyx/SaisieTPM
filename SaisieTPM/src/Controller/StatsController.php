<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Entity\Formulaire;
use App\Entity\RenvoieSaisie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(ManagerRegistry  $doctrine, Request $request, EntityManagerInterface $manager): Response
    {

        $idUtilisateur = $this->getUser()->getId();

        $recupInfoSaisie = $doctrine->getRepository(RenvoieSaisie::class)->getInfosRenvoie($doctrine, $idUtilisateur);

        $recupInfosNbForm = $doctrine->getRepository(Formulaire::class)->getCountFormulaireByUser($doctrine, $idUtilisateur);

        $recupInfosNbChamps = $doctrine->getRepository(Champs::class)->getCountChampsByUser($doctrine, $idUtilisateur);

        $recupInfosNbRenvoie = $doctrine->getRepository(RenvoieSaisie::class)->getNbRenvoie($doctrine, $idUtilisateur);

        $formbuilder = $this->createFormBuilder();

        $formbuilder->add('ChoixFormulaire', EntityType::class, array(
            'class' => Formulaire::class,
        ));

        $formbuilder->add('Supprimer', SubmitType::class);

        $form = $formbuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $idFormulaire = $form['ChoixFormulaire']->getData()->getId();

            $doctrine->getRepository(RenvoieSaisie::class)->deleteByIdForm($doctrine, $idFormulaire);

            $doctrine->getRepository(Formulaire::class)->deleteAllDataFormById($doctrine, $idFormulaire, $idUtilisateur);
            
            $manager->flush();
        }
        
        foreach ($recupInfoSaisie as $key => $value) {
            array_push($recupInfoSaisie[$key], json_decode($value['saisie'], true));
        }

        return $this->render('stats/index.html.twig', [
            'renvoie_saisie' => $recupInfoSaisie,
            'nbFormulaire' => $recupInfosNbForm,
            'nbChamps' => $recupInfosNbChamps,
            'nbRenvoie' => $recupInfosNbRenvoie,
            'form' => $form,
        ]);
    }
}
