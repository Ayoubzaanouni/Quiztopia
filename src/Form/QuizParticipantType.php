<?php

namespace App\Form;

use App\Entity\QuizParticipant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbr_tries')
            ->add('score')
            ->add('joined_at')
            ->add('user_id')
            ->add('quiz_id')
            ->add('answers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuizParticipant::class,
        ]);
    }
}
