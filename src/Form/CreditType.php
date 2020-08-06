<?php

namespace App\Form;

use App\Entity\Credit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\CreditEcheanceType;

class CreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('taux')
            ->add('tauxEuro')
            ->add('currency')
            ->add('nbr_echeance')
            ->add('amount')
            ->add('provider')
            ->add('agent')
            ->add('motif')
            ->add('creditEcheances', CollectionType::class, [
                'entry_type' => CreditEcheanceType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
            'csrf_protection' => false,
        ]);
    }
}
