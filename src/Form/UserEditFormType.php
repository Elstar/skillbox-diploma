<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => 'Your name',
                'attr' => ['placeholder' => 'Your name'],
                'label_translation_parameters' => [
                    'First name' => 'First name'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => ['placeholder' => 'Your E-mail']
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match',
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Password', 'autocomplete' => 'off', 'value' => ''],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => ['placeholder' => 'Repeat Password', 'autocomplete' => 'off', 'value' => ''],
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
