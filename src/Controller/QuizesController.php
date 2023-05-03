<?php

namespace App\Controller;

use App\Repository\QuestionsRepository;
use App\Repository\UsersRepository;
use App\Entity\Questions;
use App\Entity\Quizes;
use App\Entity\Users;
use App\Form\QuestionsType;
use App\Form\QuizesType;
use App\Repository\QuizesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizes')]
class QuizesController extends AbstractController
{
    #[Route('/', name: 'app_quizes_index', methods: ['GET'])]
    public function index(QuizesRepository $quizesRepository, UsersRepository $usersRepository): Response
    {
        $quizes = $quizesRepository->findAll();
    $data = [];
    
    foreach ($quizes as $quiz) {
        $user = $usersRepository->find($quiz->getUserId()->getId());
        $user_name = $user ? $user->getUserName() : 'Unknown';
        $data[] = [
            'quiz' => $quiz,
            'user_name' => $user_name,
        ];
    }
    
    return $this->render('quizes/index.html.twig', [
        'data' => $data,
    ]);
    }

    #[Route('/new', name: 'app_quizes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizesRepository $quizesRepository): Response
    {
        $quize = new Quizes();
        $form = $this->createForm(QuizesType::class, $quize);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user =new Users();
            $user = $this->getUser();
            $quizesRepository->save($quize,$user ,true);

            return $this->redirectToRoute('app_questions_new', ['quiz_id' => $quize->getId()]);
        }

        return $this->renderForm('quizes/new.html.twig', [
            'quize' => $quize,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quizes_show', methods: ['GET'])]
    public function show(Quizes $quize, UsersRepository $usersRepository): Response
    {
        $user = $usersRepository->find($quize->getUserId()->getId());
        $user_name = $user ? $user->getUserName() : 'Unknown';
        $user_id =$user->getId();
        return $this->render('quizes/show.html.twig', [
            'quize' => $quize,
            'createdBy'=>$user_name,
            'user_id'=>$user_id,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quizes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quizes $quize, QuizesRepository $quizesRepository, UsersRepository $usersRepository): Response
    {
        $form = $this->createForm(QuizesType::class, $quize);
        $form->handleRequest($request);

        $user =new Users();
        $user = $this->getUser();
        $user = $usersRepository->find($quize->getUserId()->getId());
        $user_id =$user->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $quizesRepository->save($quize,$user ,true);

            return $this->redirectToRoute('app_quizes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quizes/edit.html.twig', [
            'quize' => $quize,
            'form' => $form,
            'user_id'=>$user_id,
        ]);
    }

    #[Route('/{id}', name: 'app_quizes_delete', methods: ['POST'])]
    public function delete(Request $request, Quizes $quize, QuizesRepository $quizesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quize->getId(), $request->request->get('_token'))) {
            $quizesRepository->remove($quize, true);
        }

        return $this->redirectToRoute('app_quizes_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit/question', name: 'app_questions_new', methods: ['GET', 'POST'])]
    public function newQuestion(Request $request, QuestionsRepository $questionsRepository, int $id): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionsRepository->save($question, true);

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }
    
}