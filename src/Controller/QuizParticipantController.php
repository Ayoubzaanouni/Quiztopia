<?php

namespace App\Controller;

use App\Entity\QuizParticipant;
use App\Form\QuizParticipantType;
use App\Repository\QuizParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz/participant')]
class QuizParticipantController extends AbstractController
{
    #[Route('/', name: 'app_quiz_participant_index', methods: ['GET'])]
    public function index(QuizParticipantRepository $quizParticipantRepository): Response
    {
        return $this->render('quiz_participant/index.html.twig', [
            'quiz_participants' => $quizParticipantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quiz_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuizParticipantRepository $quizParticipantRepository): Response
    {
        $quizParticipant = new QuizParticipant();
        $form = $this->createForm(QuizParticipantType::class, $quizParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizParticipantRepository->save($quizParticipant, true);

            return $this->redirectToRoute('app_quiz_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_participant/new.html.twig', [
            'quiz_participant' => $quizParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_participant_show', methods: ['GET'])]
    public function show(QuizParticipant $quizParticipant): Response
    {
        return $this->render('quiz_participant/show.html.twig', [
            'quiz_participant' => $quizParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quiz_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuizParticipant $quizParticipant, QuizParticipantRepository $quizParticipantRepository): Response
    {
        $form = $this->createForm(QuizParticipantType::class, $quizParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizParticipantRepository->save($quizParticipant, true);

            return $this->redirectToRoute('app_quiz_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_participant/edit.html.twig', [
            'quiz_participant' => $quizParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_participant_delete', methods: ['POST'])]
    public function delete(Request $request, QuizParticipant $quizParticipant, QuizParticipantRepository $quizParticipantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizParticipant->getId(), $request->request->get('_token'))) {
            $quizParticipantRepository->remove($quizParticipant, true);
        }

        return $this->redirectToRoute('app_quiz_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
