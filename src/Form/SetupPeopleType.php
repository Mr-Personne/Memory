<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SetupPeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder
        //     ->add('firstName')
        //     ->add('lastName')
        //     ->add('address')
        //     ->add('town')
        //     ->add('postalCode')
        //     ->add('age')
        //     ->add('birthDay')
        //     ->add('job')
        // ;
        $builder
            ->add('quantity', IntegerType::class, [
                'label'  => 'How many do you want to memorise?',
                 'attr' => [
                     'class' => 'quantity',
                     'min' => 1,
                     'max' => 199,
                     'value' => 4
                    ], 
            ])
            ->add('minutes', IntegerType::class, [
                'label'  => 'How long do you need to memorise?',
                'attr' => [
                    'class' => 'minutes',
                    'min' => 0,
                    'value' => 5,
                     'placeholder' => 'mins...'
                   ], 
            ])
            ->add('secondes', IntegerType::class, [
                'label'  => false,
                'attr' => [
                    'class' => 'secondes',
                    'min' => 0,
                    'max' => 59,
                    'value' => 0,
                    'placeholder' => 'secs...'
                   ], 
            ])
            ->add('go', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Person::class,
        ]);
    }
}
