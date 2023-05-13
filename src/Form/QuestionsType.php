<?php
namespace App\Form;

use App\Entity\Answers;
use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', null, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswersType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'attr' => [
                    'class' => 'answers-collection',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
