<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('lastName')
            ->add('email')
            ->add('address')
            ->add('neighborhood')
            ->add('phone')
            ->add('birthdate', DateType::class,[
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ])
            ->add('age')
            ->add('photo')
            ->add('city', EntityType::class,[
                    'class'=>City::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('password')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
