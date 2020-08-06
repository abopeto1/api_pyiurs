<?php

namespace App\Form;

use App\Entity\Bill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total')
            ->add('taxe')
            ->add('net')
            ->add('accompte')
            ->add('reste')
            ->add('clerk')
            ->add('customer')
            ->add('typePaiement')
            ->add('bill_reference')
            ->add('clotureDay')
            // ->add('operator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
        ]);
    }
}
