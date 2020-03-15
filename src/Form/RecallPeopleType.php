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
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('town')
            ->add('postalCode')
            ->add('age')
            // ->add('birthDay')
            ->add('job')
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                
                $form = $event->getForm();
                $peopleQuantity = $form->getConfig()->getOption('peopleFormQuantity');

                if ($peopleQuantity > 1) {
                    for ($i=2; $i <= $peopleQuantity; $i++) { 
                        $form
                            ->add('firstName'.$i)
                            ->add('lastName'.$i)
                            ->add('address'.$i)
                            ->add('town'.$i)
                            ->add('postalCode'.$i)
                            ->add('age'.$i)
                            ->add('job'.$i);
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Person::class,
            'peopleFormQuantity' => false,
        ]);
    }
}
