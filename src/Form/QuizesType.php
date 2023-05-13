<?php

namespace App\Form;

use App\Entity\Quizes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class QuizesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Enter the title',
                ],
            ])
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Enter the description',
                ],
            ])
            ->add('is_public', CheckboxType::class, [
                'label' => 'Is Public?',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input ',
                ],
                'label_attr' => [
                    'class' => 'form-check-label align-items-center mb-3',
                ],
            ])
            
            
            ->add('max_tries', null, [
                'attr' => [
                    'class' => 'form-control mb-5',
                    'placeholder' => 'Enter the maximum tries',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizes::class,
        ]);
    }
}
