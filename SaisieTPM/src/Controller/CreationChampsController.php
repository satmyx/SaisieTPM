<?php

namespace App\Controller;

use App\Entity\Champs;
use App\Form\CreationChampsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use GuzzleHttp\Client;

class CreationChampsController extends AbstractController
{
    #[Route('/creationchamps', name: 'app_creation_champs')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://127.0.0.1:8000/',
            // You can set any number of default request options.
            'timeout'  => 10.0,
        ]);

        $creationChamps = new Champs();

        $form = $this->createForm(CreationChampsType::class, $creationChamps);

        $form->handleRequest($request);

        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()) {

            $creationChamps->setUtilisateur($user);

            $data = $request->request->all();

            $type = '/api/type_champs/'. $data['creation_champs']['id_type'];

            dump($type);

            $utilisateur = '/api/users/'. $user->getId();

            $client->request('POST', '/api/champs', array(
                'multipart' => [
                    [
                        'name' => 'nom',
                        'contents' => $data['creation_champs']['nom']
                    ],
                    [
                        'name' => 'id_type',
                        'contents' => $type
                    ],
                    [
                        'name' => 'utilisateur',
                        'contents' => $utilisateur
                    ],
                ]
            ));

            sweetalert()->toast(true, 'top-end', false)->addSuccess('Votre champ : '. $creationChamps->getNom(). ' a bien été enregistré');
        }

        return $this->render('creation_champs/index.html.twig', [
            'form' => $form,
        ]);
    }
}
