<?php

namespace App\Form;

use App\Entity\Bill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\BillDetailsType;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('total')
            ->add('taxe')
            ->add('net')
            ->add('accompte')
            ->add('reste')
            ->add('customer')
            ->add('billDetails',CollectionType::class,[
              'entry_type' => BillDetailsType::class,
              'allow_add' => true,
              'error_bubbling' => false,
            ])
            ->add('typePaiement')
            ->add('billReference')
            ->add('operator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bill::class,
            'csrf_protection' => false,
        ]);
    }
}
