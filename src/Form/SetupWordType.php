<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SetupWordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'label'  => 'How many do you want to memorise?',
                 'attr' => [
                     'class' => 'quantity',
                     'min' => 1,
                     'max' => 2000,
                     'value' => 10,
                    ], 
            ])
            ->add('minutes', IntegerType::class, [
                'label'  => 'How long do you need to memorise?',
                'attr' => [
                    'class' => 'minutes',
                    'min' => 0,
                    'value' => 5
                   ], 
            ])
            ->add('secondes', IntegerType::class, [
                'label'  => false,
                'attr' => [
                    'class' => 'secondes',
                    'min' => 0,
                    'max' => 59,
                    'value' => 0
                   ], 
            ])
            ->add('go', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
