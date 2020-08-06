<?php

namespace App\Form;

use App\Entity\BilanBudget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilanBudgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month')
            ->add('year')
            ->add('value')
            ->add('bilan_account')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BilanBudget::class,
            'csrf_protection' => false,
        ]);
    }
}
