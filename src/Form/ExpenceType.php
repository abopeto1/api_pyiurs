<?php

namespace App\Form;

use App\Entity\Expence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motif')
            ->add('taux')
            ->add('currency')
            ->add('periode')
            ->add('montant')
            ->add('expenceCompte')
            ->add('provider')
            ->add('operator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Expence::class,
            'csrf_protection' => false,
        ]);
    }
}
