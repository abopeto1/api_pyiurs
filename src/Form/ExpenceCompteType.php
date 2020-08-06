<?php

namespace App\Form;

use App\Entity\ExpenceCompte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenceCompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('expenceCompteCategorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExpenceCompte::class,
            'csrf_protection' => false,
        ]);
    }
}
