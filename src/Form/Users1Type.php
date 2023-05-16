<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class Users1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name')
            ->add('old_password', PasswordType::class, [
                'invalid_message' => 'password incorrect',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                // Additional options for the unmapped field
            ])
        //     ->add('new_password', TextType::class, [
        //         'mapped' => false,
        //         'required' => false,
        //         // Additional options for the unmapped field
        //     ])
        //     ->add('confirm_password', TextType::class, [
        //         'mapped' => false,
        //         'required' => false,
        //         // Additional options for the unmapped field
        //     ])
        // ;
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'label' => false,
            'mapped' => false,
            'first_options'  => ['label' => false, 'attr' => ['placeholder' => 'Password','style' => 'width: fit-content;']],
            'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Repeat Password','style' => 'width: fit-content;']],
            'attr' => [
                'autocomplete' => 'new-password',
                'style' => 'width: 200px;',
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
