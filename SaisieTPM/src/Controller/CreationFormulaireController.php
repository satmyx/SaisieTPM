<?php

namespace App\Controller;

use App\Form\FormulaireType;
use App\Service\CallApiService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreationFormulaireController extends AbstractController
{
    #[Route('/creationformulaire', name: 'app_creation_formulaire')]
    public function index(Request $request, CallApiService $api): Response
    {
        $form = $this->createForm(FormulaireType::class);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();

            $api->setFormulaire($user->getApiKey(), $data, $user);

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Votre formulaire : '. $data['formulaire']['nom']. ' a bien été enregistré');

            return $this->redirectToRoute("app_accueil");
        }

        return $this->render('creation_formulaire/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
