<?php

namespace App\Form;

use App\Entity\ClotureMonth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClotureMonthType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('month')
            ->add('year')
            ->add('operator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClotureMonth::class,
            'csrf_protection' => false,
        ]);
    }
}
