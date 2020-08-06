<?php

namespace App\Form;

use App\Entity\AgentLoan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentLoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('period')
            ->add('amount')
            ->add('currency')
            ->add('taux')
            ->add('agent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AgentLoan::class,
            'csrf_protection' => false,
        ]);
    }
}
