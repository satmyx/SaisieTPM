<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Form\SupprimerChampsType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChampsController extends AbstractController
{
    #[Route('/gererchamps', name: 'app_champs')]
    public function index(Request $request, CallApiService $api): Response
    {
        $token = $this->getUser()->getApiKey();

        $userId = $this->getUser()->getId();

        $form = $this->createForm(SupprimerChampsType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->all();

            $api->deleteRessource($token, $data['supprimer_champs']['nom']);

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Supression effectuée avec succès');

            return $this->redirectToRoute("app_champs");
        }

        return $this->render('champs/index.html.twig', [
            'form' => $form,
        ]);
    }
}
