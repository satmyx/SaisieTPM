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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RenvoieSaisieController extends AbstractController
{
    #[Route('/renvoiesaisie', name: 'app_renvoie_saisie')]
    public function index(ManagerRegistry  $doctrine, EntityManagerInterface $manager, Request $request): Response
    {

        $idFormulaire = 9;

        $leFormulaire = $doctrine->getRepository(Formulaire::class)->find($idFormulaire);

        $arrFormulaire = $doctrine->getRepository(Champs::class)->getInfoFormulaire($doctrine, $idFormulaire);

        $renvoieSaisie = new RenvoieSaisie();

        $formbuilder = $this->createFormBuilder();

        foreach($arrFormulaire as $row => $value) {
            $nomautoriser = preg_replace('/[^a-zA-Z0-9\']/', '_', $value['nom']);
            $nomautoriser = str_replace("'", '_', $nomautoriser);
            switch ($value['typage']) {
                case 'TextType::class':
                    $formbuilder->add($nomautoriser, TextType::class, [
                        'by_reference' => false,
                    ]);
                break;
                case 'TextareaType::class':
                    $formbuilder->add($nomautoriser, TextareaType::class, [
                        'by_reference' => false,
                    ]);
                break;
                case 'EmailType::class':
                    $formbuilder->add($nomautoriser, EmailType::class, [
                        'by_reference' => false,
                    ]);
                break;
                case 'NumberType::class':
                    $formbuilder->add($nomautoriser, NumberType::class, [
                        'by_reference' => false,
                    ]);
                break;
                case 'DateType::class':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text',
                        'by_reference' => false,
                    ]);
                break;
                case 'DateType::classfin':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text',
                        'by_reference' => false,
                    ]);
                break;
                case 'TimeType::class':
                    $formbuilder->add($nomautoriser, TimeType::class, [
                        'widget' => 'single_text',
                        'by_reference' => false,
                    ]);
                break;
                case 'FileType::class':
                    $formbuilder->add($nomautoriser, FileType::class, [
                        'by_reference' => false,
                    ]);
                break;
            }
        }
        $formbuilder->add('Envoyer', SubmitType::class);

        $form = $formbuilder->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $user = $this->getUser();

            $renvoieSaisie->setUserId($user);

            $renvoieSaisie->setFomulaireId($leFormulaire);

            $renvoieSaisie->setSaisie(array($form->getData()));

            $manager->persist($renvoieSaisie);

            $manager->flush();

            return $this->redirectToRoute("app_accueil");

        }

        return $this->render('renvoie_saisie/index.html.twig', [
            'form' => $form->createview(),
            'arrform' => $arrFormulaire,
        ]);
    }
}