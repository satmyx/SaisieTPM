<?php

namespace App\Controller;

use App\Entity\Champs;
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

        $arrFormulaire = $doctrine->getRepository(Champs::class)->getInfoFormulaire($doctrine, 9);

        $formbuilder = $this->createFormBuilder();

        foreach($arrFormulaire as $row => $value) {
            $nomautoriser = preg_replace('/[^a-zA-Z0-9\']/', '_', $value['nom']);
            $nomautoriser = str_replace("'", '_', $nomautoriser);
            switch ($value['typage']) {
                case 'TextType::class':
                    $formbuilder->add($nomautoriser, TextType::class);
                break;
                case 'TextareaType::class':
                    $formbuilder->add($nomautoriser, TextareaType::class);
                break;
                case 'EmailType::class':
                    $formbuilder->add($nomautoriser, EmailType::class);
                break;
                case 'NumberType::class':
                    $formbuilder->add($nomautoriser, NumberType::class);
                break;
                case 'DateType::class':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text'
                    ]);
                break;
                case 'DateType::classfin':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text'
                    ]);
                break;
                case 'TimeType::class':
                    $formbuilder->add($nomautoriser, TimeType::class, [
                        'widget' => 'single_text'
                    ]);
                break;
                case 'FileType::class':
                    $formbuilder->add($nomautoriser, FileType::class);
                break;
            }
        }
        $formbuilder->add('Envoyer', SubmitType::class);
        $form = $formbuilder->getForm();

        if($form->isSubmitted() && $form->isValid()){
        
            $dataform = $form->getData();

        }

        return $this->render('renvoie_saisie/index.html.twig', [
            'form' => $form->createview(),
            'arrform' => $arrFormulaire,
        ]);
    }
}
