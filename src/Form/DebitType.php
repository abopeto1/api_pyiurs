<?php

namespace App\Form;

use App\Entity\Debit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DebitEcheanceType;

class DebitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motif')
            ->add('amount')
            ->add('currency')
            ->add('taux')
            ->add('provider')
            ->add('debitEcheances', CollectionType::class, [
                'entry_type' => DebitEcheanceType::class,
                'allow_add' => true,
                'error_bubbling' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Debit::class,
            'csrf_protection' => false,
        ]);
    }
}
