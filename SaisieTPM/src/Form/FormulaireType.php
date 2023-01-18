<?php

namespace App\Form;

use App\Entity\Champs;
use App\Entity\Formulaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormulaireType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
       $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('champs', EntityType::class, array(
                'class' => Champs::class,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('champs')
                    ->where('champs.utilisateur = :utilisateur')
                    ->setParameter('utilisateur', $this->token->getToken()->getUser()->getId());
                },
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
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
