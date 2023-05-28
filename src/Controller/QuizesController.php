<?php

namespace App\Controller;

use App\Entity\Answers;
use App\Repository\QuestionsRepository;
use App\Repository\UsersRepository;
use App\Entity\Questions;
use App\Entity\Quizes;
use App\Entity\QuizParticipant;
use App\Entity\Users;
use App\Form\QuestionsType;
use App\Form\QuizesType;
use App\Repository\QuizesRepository;
use App\Repository\QuizParticipantRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



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
            $max = $form->get('max_tries')->getData();
            if ($max < 0){
                $quize->setMaxTries(0);
            }
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

    // #[Route('/code', name: 'app_quizes_code', methods: ['GET', 'POST'])]
    // public function code(Request $request): Response
    // {
    //     $form = $this->createFormBuilder()
    //     ->add('code', TextType::class)
    //     ->getForm();
    //     $form->handleRequest($request);
    //     if($form->isSubmitted() && $form->isValid()){
    //         $code = $form->get('code')->getData();
    //         return $this->redirectToRoute('app_questions_join', ['code' => $code]);
    //     }
        
    // }

    #[Route('/join/{code}', name: 'app_quizes_join', methods: ['GET', 'POST'])]
    public function join(EntityManagerInterface $em,QuizParticipantRepository $qpr,Request $request,string $code, QuizesRepository $QuizesRepository, UsersRepository $usersRepository): Response
    {
        $quize = $QuizesRepository->findOneBy(['code' => $code]);
        $user = $usersRepository->find($quize->getUserId()->getId());
        $user_name = $user ? $user->getUserName() : 'Unknown';
        $user_id =$user->getId();
        $questions = $quize->getQuestions();
        
        $qb1 = $em->createQueryBuilder();
        $qb1->select('COUNT(a.id)')
        ->from('App\Entity\Answers', 'a')
        ->where('a.is_correct = :correct')
        ->andWhere('a.quiz_id = :quizId')
        ->setParameter('correct', true)
        ->setParameter('quizId', $quize->getId());
        $nbr_correct_answers = $qb1->getQuery()->getSingleScalarResult();



        if (!$quize) {
            throw $this->createNotFoundException('Quiz not found.');
        }
        
        $qb = $em->createQueryBuilder();
        $qb->select('COUNT(p.id)')
        ->from('App\Entity\QuizParticipant', 'p')
        ->where('p.user_id = :userId')
        ->andWhere('p.quiz_id = :quizId')
        ->setParameter('userId', $this->getUser()->getId())
        ->setParameter('quizId', $quize->getId());


        $count = $qb->getQuery()->getSingleScalarResult();
        if ($request->isMethod('POST')) {
            
        $quiz_participant = new QuizParticipant();
        $quiz_participant->setUserId($this->getUser());
        $quiz_participant->setQuizId($quize);
        $quiz_participant->setJoinedAt();
        
        $quiz_participant->setNbrTries($count+1);
        
    $counter = 0;
    $selectedAnswers = $request->request->get('selectedAnswers');
    if (!empty($selectedAnswers)) {    
    $selectedAnswers = json_decode($selectedAnswers, true);

        // Process the selected answers
        foreach ($selectedAnswers as $answerId) {
            // $answerId is the ID of the selected answer
            $answer = $em->getRepository(Answers::class)->findOneBy(['id' => $answerId]);
            // Perform any required logic or processing
            if($answer->isIsCorrect())
                {
                    $counter +=1;
                }
                else{
                    $counter -=1;
                 }
                 $quiz_participant->addAnswer($answer);
        }
    }

        // ... (rest of your code)
        if($nbr_correct_answers == 0)
        {
            $nbr_correct_answers =1;
        }
        $score = ($counter/$nbr_correct_answers)*100;
        
        if($score > 0)
        {
            $quiz_participant->setScore(round($score, 2));
            $scoreString = strval(round($score,2));
        }
        else{
            $quiz_participant->setScore(0);
            $scoreString = strval(0);
        }
        $qpr->save($quiz_participant, true);

        $quiz = $quiz_participant->getQuizId();
        $quizQuestion =$quiz->getQuestions();
        $answers = $quiz_participant->getAnswers();

        return $this->render('user/answers.html.twig', [
            'answers' => $answers,
            'user_id'=>$user_id,
            'pt' => $quiz_participant,
            'questions' => $quizQuestion,
            'score' => $scoreString,
        ]);
    
    }


    
        return $this->render('quizes/join.html.twig', [
            'quize' => $quize,
            'createdBy'=>$user_name,
            'user_id'=>$user_id,
            'questions'=>$questions,
            'nbr_tries'=>$count,
            
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
            'questions' => $quize->getQuestions(),
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
