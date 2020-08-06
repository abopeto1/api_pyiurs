<?php

namespace App\Form;

use App\Entity\CreditEcheance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditEcheanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('paied', DateType::class, [
                'widget' => 'single_text',
            ])
            // ->add('statut')
            // ->add('expence')
            // ->add('credit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreditEcheance::class,
            'csrf_protection' => false,
        ]);
    }
}
