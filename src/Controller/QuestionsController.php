<?php

namespace App\Controller;

use App\Entity\Quizes;
use App\Entity\Answers;
use App\Entity\Questions;

use App\Form\QuestionsType;
use Symfony\Component\Form\FormError;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/', name: 'app_questions_index', methods: ['GET'])]
    public function index(QuestionsRepository $questionsRepository): Response
    {
        return $this->render('questions/index.html.twig', [
            'questions' => $questionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_questions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestionsRepository $questionsRepository, $quiz_id, EntityManagerInterface $em): Response
    {
        $question = new Questions();
        

        // dummy code - add some example tags to the task
        // (otherwise, the template will render an empty list of tags)
        // $tag1 = new Answers();
        // $tag1->setText('ans1');
        // $question->getAnswers()->add($tag1);
        // $tag2 = new Answers();
        // $tag2->setText('tag2');
        
        // $question->getAnswers()->add($tag2);
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);
        
        

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz = $em->getRepository(Quizes::class)->find($quiz_id);
            $question->setQuizId($quiz);
            
            // $answersData = $form->get('answers')->getData();
            // foreach ($answersData as $answerData) {
            //     $answer = new Answers();
            //     $answer->setText($answerData->getText());
            //     $answer->setIsCorrect($answerData->isIsCorrect());
            //     $answer->setQuestId($question);
            //     $answer->setQuizId($quiz);
            //     $question->addAnswer($answer);

            // }
            $answersData = $form->get('answers')->getData();
            foreach ($answersData as $answerData) {

                $answerData->setQuestId($question);
                $answerData->setQuizId($quiz);
                $question->addAnswer($answerData);
            }
            
            $answers = $form->get('answers')->getData();
            $atLeastOneCorrect = false;
            foreach ($answers as $answer) {
                if ($answer->isIsCorrect()) {
                    $atLeastOneCorrect = true;
                    break;
                }
            }
            if (!$atLeastOneCorrect) {
                $form->addError(new FormError('At least one answer should be marked as correct.'));            } 
            else{
            $questionsRepository->save($question, true);

    
            // return $this->redirectToRoute('app_questions_new', ['quiz_id' => $quiz->getId()]);


            if ($request->request->has('save_and_add_another')) {
                // redirect to the new question form
                return $this->redirectToRoute('app_questions_new', ['quiz_id' => $quiz->getId()]);
            } else {
                // redirect to some other page
                return $this->redirectToRoute('app_user_quizes');
            }        }}
    
        return $this->renderForm('questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    
    }
    

    #[Route('/{id}', name: 'app_questions_show', methods: ['GET'])]
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_questions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Questions $question, QuestionsRepository $questionsRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        $quiz = $em->getRepository(Quizes::class)->find($question->getQuizId());

        if ($form->isSubmitted() && $form->isValid()) {
            $questionsRepository->save($question, true);

            $answersData = $form->get('answers')->getData();
            foreach ($answersData as $answerData) {

                $answerData->setQuestId($question);
                $answerData->setQuizId($quiz);
                $question->addAnswer($answerData);

            }
            $questionsRepository->save($question, true);



            if ($request->request->has('save_and_add_another')) {
                // redirect to the new question form
                return $this->redirectToRoute('app_questions_new', ['quiz_id' => $quiz->getId()]);
            } else {
                // redirect to some other page
                return $this->redirectToRoute('app_user_quizes');
            }  
        }

        return $this->renderForm('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questions_delete', methods: ['POST'])]
    public function delete(Request $request, Questions $question, QuestionsRepository $questionsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $questionsRepository->remove($question, true);
        }
        $referer = $request->headers->get('referer');

    return $this->redirectToRoute('app_user_quizes');

    }   
}
