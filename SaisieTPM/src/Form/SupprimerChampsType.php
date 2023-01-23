<?php

namespace App\Form;

use App\Entity\Champs;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupprimerChampsType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
       $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, array(
                'class' => Champs::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('champs')
                    ->where('champs.utilisateur = :utilisateur')
                    ->setParameter('utilisateur', $this->token->getToken()->getUser()->getId());
                },
            ))
            ->add('Supprimer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Champs::class,
        ]);
    }
}
