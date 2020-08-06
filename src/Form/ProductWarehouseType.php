<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductWarehouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cat')
            ->add('codebarre')
            ->add('taille')
            ->add('pu')
            ->add('couleur')
            ->add('marque')
            ->add('description')
            ->add('codeLivraison')
            ->add('caa')
            ->add('pv')
            ->add('source')
            ->add('type')
            ->add('segment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
        ]);
    }
}
