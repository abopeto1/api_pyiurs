<?php

namespace App\Form;

use App\Entity\ClotureDay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClotureDayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('theoric_cash')
            ->add('theoric_cash_cdf')
            ->add('comment')
            ->add('operator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ClotureDay::class,
            'csrf_protection' => false,
        ]);
    }
}
