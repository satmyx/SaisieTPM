<?php

namespace App\Form;

use App\Entity\Champs;
use App\Entity\TypeChamps;
use App\Service\CallApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreationChampsType extends AbstractType
{

    private $token;

    public function __construct(TokenStorageInterface $token, CallApiService $api)
    {
       $this->token = $token;
       $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $typeDeChamps = array();

        foreach($this->api->getTypeChamps($this->token->getToken()->getUser()->getApiKey())['hydra:member'] as $value){
            $typeDeChamps[$value['nom']] = $value['@id'];
        }

        $builder
            ->add('nom')
            ->add('id_type', ChoiceType::class, array(
                'choices' => $typeDeChamps,
                'label' => "Type de saisie",
            ))
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Champs::class,
        ]);
    }
}
