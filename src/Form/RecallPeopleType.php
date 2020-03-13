<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RecallPeopleType extends AbstractType
{
    private function getPeopleQantity(): ?string
    {
        $session = new Session();
        return $session->get('generatedPeople');
    }

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
                // $product = $event->getData();
                $peopleQuantity = $this->getPeopleQantity();
                $form = $event->getForm();

                // checks if the Product object is "new"
                // If no data is passed to the form, the data is "null".
                // This should be considered a new "Product"
                if ($peopleQuantity > 1 || 1 == 1) {
                    $form->add('TESTFIELD', TextType::class);
                }
            })
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // 'data_class' => Person::class,
        ]);
    }
}
