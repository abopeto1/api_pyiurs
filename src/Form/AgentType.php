<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('fonction')
            ->add('mail')
            ->add('telephone')
            ->add('country')
            ->add('adress')
            ->add('private_telephone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
            'csrf_protection' => false,
        ]);
    }
}
