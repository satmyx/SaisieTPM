<?php

namespace App\Form;

use App\Entity\Champs;
use App\Entity\Formulaire;
use App\Service\CallApiService;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[\AllowDynamicProperties]
class FormulaireType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token, CallApiService $api)
    {
       $this->token = $token;
       $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $listeChamps = array();

        $user = $this->token->getToken()->getUser();

        foreach($this->api->getChamps($user->getApiKey())['hydra:member'] as $value){

            if($value['utilisateur'] == '/api/users/'. $user->getId()) {
                $listeChamps[$value['nom']] = $value['@id'];
            }
        }

        $builder
            ->add('nom', TextType::class)
            ->add('champs', ChoiceType::class, array(
                'choices' => $listeChamps,
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
            ))
            ->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formulaire::class,
        ]);
    }
}
