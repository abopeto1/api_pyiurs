<?php

namespace App\Form;

use App\Entity\CashIn;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CashInType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('currency')
            ->add('operator')
            ->add('motif')
            ->add('comment')
            ->add('provider')
            ->add('taux')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CashIn::class,
            'csrf_protection' => false,
        ]);
    }
}
