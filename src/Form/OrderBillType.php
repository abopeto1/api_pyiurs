<?php

namespace App\Form;

use App\Entity\OrderBill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderBillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('taux')
            ->add('currency')
            ->add('pdf_url')
            ->add('numero')
            ->add('store')
            ->add('total_products')
            ->add('orders')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderBill::class,
            'csrf_protection' => false,
        ]);
    }
}
