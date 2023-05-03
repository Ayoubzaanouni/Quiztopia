<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Form\AnswersType;
use App\Repository\AnswersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/answers')]
class AnswersController extends AbstractController
{
    #[Route('/', name: 'app_answers_index', methods: ['GET'])]
    public function index(AnswersRepository $answersRepository): Response
    {
        return $this->render('answers/index.html.twig', [
            'answers' => $answersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_answers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnswersRepository $answersRepository): Response
    {
        $answer = new Answers();
        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answersRepository->save($answer, true);

            return $this->redirectToRoute('app_answers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answers_show', methods: ['GET'])]
    public function show(Answers $answer): Response
    {
        return $this->render('answers/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_answers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Answers $answer, AnswersRepository $answersRepository): Response
    {
        $form = $this->createForm(AnswersType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answersRepository->save($answer, true);

            return $this->redirectToRoute('app_answers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answers/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_answers_delete', methods: ['POST'])]
    public function delete(Request $request, Answers $answer, AnswersRepository $answersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $answersRepository->remove($answer, true);
        }

        return $this->redirectToRoute('app_answers_index', [], Response::HTTP_SEE_OTHER);
    }
}
