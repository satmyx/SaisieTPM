<?php

namespace App\Controller;

use App\Service\CallApiService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(Request $request, CallApiService $api): Response
    {

        $user = $this->getUser();

        $idUtilisateur = $user->getId();

        $recupInfoSaisie = $api->getRenvoieSaisieByForm($user->getApiKey(), $user->getId());

        $recupInfosNbForm = $api->getCptForms($user->getApiKey(), $user->getId());

        $recupInfosNbChamps = $api->getCptChamps($user->getApiKey(), $user->getId());

        $recupInfosNbRenvoie = $api->getCptRenvoieSaisie($user->getApiKey(), $user->getId());

        $recupDelete = $api->getFormByUser($user->getApiKey(), $user->getId());

        $formbuilder = $this->createFormBuilder();

        $formbuilder->add('ChoixFormulaire', ChoiceType::class, array(
            'choices' => $recupDelete,
        ));

        $formbuilder->add('Supprimer', SubmitType::class);

        $form = $formbuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $data = $request->request->all();

            $listeRenvoie = $api->getByUri($user->getApiKey(), $data['form']['ChoixFormulaire']);

            foreach ($listeRenvoie['renvoieSaisies'] as $value) {
                $api->deleteRessource($user->getApiKey(), $value);
            }

            foreach ($listeRenvoie['champs'] as $value) {
                $api->deleteRessource($user->getApiKey(), $value);
            }

            $api->deleteRessource($user->getApiKey(), $data['form']['ChoixFormulaire']);

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Supression effectuée avec succès');

            return $this->redirectToRoute("app_stats");
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
