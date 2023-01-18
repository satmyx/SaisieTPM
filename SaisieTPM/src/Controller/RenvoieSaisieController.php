<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Entity\Formulaire;
use App\Entity\RenvoieSaisie;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
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
    #[Route('/renvoiesaisie/{id}', name: 'app_renvoie_saisie', requirements: ["id" => "\d+"])]
    public function index(ManagerRegistry  $doctrine, EntityManagerInterface $manager, Request $request, FileUploader $fileUploader, int $id): Response
    {

        $leFormulaire = $doctrine->getRepository(Formulaire::class)->find($id);

        $arrFormulaire = $doctrine->getRepository(Champs::class)->getInfoFormulaire($doctrine, $id);

        $renvoieSaisie = new RenvoieSaisie();

        $formbuilder = $this->createFormBuilder();

        $motInterdit = array(
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ÿ' => 'y'
        );

        foreach ($arrFormulaire as $row => $value) {
            $nomautoriser = strtr($value['nom'], $motInterdit);
            $nomautoriser = preg_replace('/[^a-zA-Z0-9\']/', '_', $nomautoriser);
            $nomautoriser = str_replace("'", '_', $nomautoriser);
            $pieceJointe = false;
            switch ($value['typage']) {
                case 'TextType::class':
                    $formbuilder->add($nomautoriser, TextType::class, [
                        'by_reference' => false,
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'TextareaType::class':
                    $formbuilder->add($nomautoriser, TextareaType::class, [
                        'by_reference' => false,
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'EmailType::class':
                    $formbuilder->add($nomautoriser, EmailType::class, [
                        'by_reference' => false,
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'NumberType::class':
                    $formbuilder->add($nomautoriser, NumberType::class, [
                        'by_reference' => false,
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'DateType::class':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text',
                        'input' => 'string',
                        'by_reference' => false,
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'TimeType::class':
                    $formbuilder->add($nomautoriser, TimeType::class, [
                        'widget' => 'single_text',
                        'by_reference' => false,
                        'input' => 'string', 
                        'label' => $value['nom'],
                    ]);
                    break;
                case 'FileType::class':
                    $pieceJointe = true;
                    $formbuilder->add('piecejointe', FileType::class, [
                        'by_reference' => false,
                        'label' => $value['nom'],
                        'mapped' => false,
                        'required' => false,
                    ]);
                    break;
            }
        }
        $formbuilder->add('Envoyer', SubmitType::class);

        $form = $formbuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            $renvoieSaisie->setUserId($user);

            $renvoieSaisie->setFomulaireId($leFormulaire);

            $renvoieSaisie->setSaisie($form->getData());

            if ($pieceJointe) {
                $piecejointeFileName = $fileUploader->upload($form->get('piecejointe')->getData());
                $renvoieSaisie->setPiecejointe($piecejointeFileName);
            } else {
                $renvoieSaisie->setPiecejointe(null);
            }

            $manager->persist($renvoieSaisie);

            $manager->flush();

            return $this->redirectToRoute("app_accueil");
        }

        return $this->render('renvoie_saisie/index.html.twig', [
            'form' => $form->createview(),
            'arrform' => $arrFormulaire,
            'id' => $id,
        ]);
    }
}
