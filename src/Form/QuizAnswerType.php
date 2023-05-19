<?php
namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Collections\Collection; // Add this line

class QuizAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];

        foreach ($questions as $question) {
            $builder->add('question_'.$question->getId(), CollectionType::class, [
                'entry_type' => CheckboxType::class,
                'entry_options' => [
                    'label' => false,
                    'mapped' => false,
                    'required' => false,
                    'choices' => $question->getAnswers(),
                    'choice_label' => 'text',
                ],
                'label' => $question->getText(),
                'mapped' => false,
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('questions');
        $resolver->setAllowedTypes('questions', Collection::class); // Modify this line
    }
}
