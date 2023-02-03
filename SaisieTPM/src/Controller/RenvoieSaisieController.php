<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Service\CallApiService;

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
    public function index(Request $request, FileUploader $fileUploader, CallApiService $api, int $id): Response
    {
        $token = $this->getUser()->getApiKey();

        $leFormulaire = $api->getFormById($token, $id);

        $arrFormulaire = $api->getFormChampsByUriAndType($token, $id);

        $formbuilder = $this->createFormBuilder();

        $motInterdit = array(
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ÿ' => 'y'
        );

        foreach ($arrFormulaire as $key => $value) {
            $nomautoriser = strtr($key, $motInterdit);
            $nomautoriser = preg_replace('/[^a-zA-Z0-9\']/', '_', $nomautoriser);
            $nomautoriser = str_replace("'", '_', $nomautoriser);
            $pieceJointe = false;
            switch ($value) {
                case 'TextType::class':
                    $formbuilder->add($nomautoriser, TextType::class, [
                        'by_reference' => false,
                        'label' => $key,
                    ]);
                    break;
                case 'TextareaType::class':
                    $formbuilder->add($nomautoriser, TextareaType::class, [
                        'by_reference' => false,
                        'label' => $key,
                    ]);
                    break;
                case 'EmailType::class':
                    $formbuilder->add($nomautoriser, EmailType::class, [
                        'by_reference' => false,
                        'label' => $key,
                    ]);
                    break;
                case 'NumberType::class':
                    $formbuilder->add($nomautoriser, NumberType::class, [
                        'by_reference' => false,
                        'label' => $key,
                    ]);
                    break;
                case 'DateType::class':
                    $formbuilder->add($nomautoriser, DateType::class, [
                        'widget' => 'single_text',
                        'input' => 'string',
                        'by_reference' => false,
                        'label' => $key,
                    ]);
                    break;
                case 'TimeType::class':
                    $formbuilder->add($nomautoriser, TimeType::class, [
                        'widget' => 'single_text',
                        'by_reference' => false,
                        'input' => 'string', 
                        'label' => $key,
                    ]);
                    break;
                case 'FileType::class':
                    $pieceJointe = true;
                    $formbuilder->add('piecejointe', FileType::class, [
                        'by_reference' => false,
                        'label' => $key,
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

            $data = $request->request->all();

            array_pop($data['form']);
            array_pop($data['form']);

            $apiUser = '/api/users/'.$user->getId();

            if ($pieceJointe) {
                $piecejointeFileName = $fileUploader->upload($form->get('piecejointe')->getData());
                $api->setRenvoieSaisie($token, $apiUser, $data['form'], $leFormulaire['@id'], $piecejointeFileName);
            } else {
                $api->setRenvoieSaisie($token, $apiUser, $data['form'], $leFormulaire['@id'], null);
            }

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Votre réponse a bien été enregistré');

            return $this->redirectToRoute("app_accueil");
        }

        return $this->render('renvoie_saisie/index.html.twig', [
            'form' => $form->createview(),
            'id' => $id,
        ]);
    }
}
