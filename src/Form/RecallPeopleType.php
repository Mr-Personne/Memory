<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RecallPeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,['required'=>false])
            ->add('lastName',TextType::class,['required'=>false])
            ->add('address',TextType::class,['required'=>false])
            ->add('town',TextType::class,['required'=>false])
            ->add('postalCode',TextType::class,['required'=>false])
            ->add('age',TextType::class,['required'=>false])
            // ->add('birthDay')
            ->add('job',TextType::class,['required'=>false])
            // ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                
            //     $form = $event->getForm();
            //     $peopleQuantity = $form->getConfig()->getOption('peopleFormQuantity');

            //     if ($peopleQuantity > 1) {
            //         for ($i=2; $i <= $peopleQuantity; $i++) { 
            //             $form
            //                 ->add('firstName'.$i,TextType::class,['required'=>false])
            //                 ->add('lastName'.$i,TextType::class,['required'=>false])
            //                 ->add('address'.$i,TextType::class,['required'=>false])
            //                 ->add('town'.$i,TextType::class,['required'=>false])
            //                 ->add('postalCode'.$i,TextType::class,['required'=>false])
            //                 ->add('age'.$i,TextType::class,['required'=>false])
            //                 ->add('job'.$i,TextType::class,['required'=>false]);
            //         }
            //     }
            // })
            ->add('Answer!', SubmitType::class)
            ->add('recall-more', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Person::class,
            'peopleFormQuantity' => false,
        ]);
    }
}
