<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Form\CreationChampsType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CreationChampsController extends AbstractController
{

    #[Route('/creationchamps', name: 'app_creation_champs')]
    public function index(Request $request, CallApiService $api): Response
    {
        $form = $this->createForm(CreationChampsType::class);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();

            $nom = $data['creation_champs']['nom'];

            $api->setChampsData($user->getApiKey(), $data, $user);

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Votre champ : '. $nom. ' a bien été enregistré');
        }

        return $this->render('creation_champs/index.html.twig', [
            'form' => $form,
        ]);
    }
}