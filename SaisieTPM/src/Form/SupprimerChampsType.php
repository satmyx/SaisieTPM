<?php

namespace App\Form;

use App\Entity\Champs;
use App\Service\CallApiService;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupprimerChampsType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token, CallApiService $api)
    {
       $this->token = $token;
       $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $apiToken = $this->token->getToken()->getUser()->getApiKey();

        $userId = $this->token->getToken()->getUser()->getId();

        $builder
            ->add('nom', ChoiceType::class, array(
                'choices' => $this->api->getChampsByUser($apiToken, $userId),
                'label' => "Vos Champs",
            ))
            ->add('Supprimer', SubmitType::class)
        ;
    }
}
