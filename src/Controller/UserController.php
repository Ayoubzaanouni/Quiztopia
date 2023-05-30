<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Users1Type;
use App\Entity\Quizes;
use App\Entity\QuizParticipant;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        $status = 'nothing';
        $user = $this->getUser();
        $form = $this->createForm(Users1Type::class,$user);
        $form->handleRequest($request);
        
        $user1 = $em->getRepository(Users::class)->find($user->getId());


        if ($form->isSubmitted() && $form->isValid()){
            $oldPassword = $form->get('old_password')->getData();
            $hashedPassword = $user1->getPassword();
            
            if (password_verify($oldPassword, $hashedPassword)) {
                $status = 'correct';
                $user_name= $form->get('user_name')->getData();
                
                $user1->setUserName($user_name);
                // Old password matches, proceed with updating the password
                $newPassword = $form->get('new_password')->getData();

                if(!empty($newPassword))
                {
                    $user1->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
                }
              
                $usersRepository->save($user1, true);
                
                        }
                        else{
                            $status= 'incorrect';
                        }
        }


        // if ($form->isSubmitted() && $form->isValid()&&!empty($form->get('new_password')->getData())) {
        //     $oldPassword = $form->get('old_password')->getData();
        //     $hashedPassword = $user1->getPassword();

        //     // Perform your password validation logic here
        //     if (password_verify($oldPassword, $hashedPassword)) {
        //         $status = true;
        //         $user_name= $form->get('user_name')->getData();
        //         $user1->serUserName($user_name);
        //         // Old password matches, proceed with updating the password
        //         $newPassword = $form->get('new_password')->getData();
              
        //         $user1->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
                
                
        //     $usersRepository->save($user1, true);

            
        //                 }
        //             }
        
                    return $this->render('user/index.html.twig', [
                        'controller_name' => 'UserController',
                        'form' => $form,
                        'status' => $status,
                ]);
}

    #[Route('/user/quizes', name: 'app_user_quizes')]
    public function show(): Response
    {
         /** @var User $user */
         $user = $this->getUser();

         // Get the quizzes created by the user
         $quizes = $user->getQuizes();

        return $this->render('user/user_quizes.html.twig', [
            'quizes' => $quizes,
        ]);
    }

    #[Route('/user/quizes-history', name: 'app_user_quizes_history')]
    public function history(EntityManagerInterface $em): Response
    {
        /** @var User1 $user */
        $user = $this->getUser();
        $user_id = $user->getId();
        
        $conn = $em->getConnection();

        $sql = 'SELECT DISTINCT quiz_id_id FROM quiz_participant WHERE user_id_id = :userId';
        $statement = $conn->prepare($sql);
        $statement->bindValue('userId', $user_id);
        $result = $statement->executeQuery();
        $quizesIds = $result->fetchAllAssociative();
        $quizIds = array_column($quizesIds, 'quiz_id_id');
        $quizRepository = $em->getRepository(Quizes::class);
        $quizesData = $quizRepository->findBy(['id' => $quizIds]);


        return $this->render('user/joined_history.html.twig', [
            'quizes' => $quizesData,
            'ids' => $quizesIds,
        ]);
    }
    #[Route('/user/quizes-history/{id}', name: 'app_user_tries', methods: ['GET'])]
    public function tries(Quizes $quize, Request $request): Response
    {

        $participantId = $request->query->get('user_id');
        $participantName = $request->query->get('part_name');


        /** @var User1 $user */
        $user = $this->getUser();

        if($participantId == null){
            $user_id = $user->getId();
        }
        else{
            $user_id =$participantId;
        }
        
        
        $quiz_pt = $quize->getQuizParticipants();

        return $this->render('user/tries.html.twig', [
            'quize_pt' => $quiz_pt,
            'user_id'=>$user_id,
            'quize' => $quize,
            'part_name'=> $participantName,
        ]);
    }

    #[Route('/user/quizes-history/trie/{id}', name: 'app_user_trie_history', methods: ['GET'])]
    public function answers(QuizParticipant $pt, EntityManagerInterface $em): Response
    {
        /** @var User1 $user */
        $user = $this->getUser();
        $user_id = $user->getId();

        $quiz = $pt->getQuizId();
        $quizQuestion =$quiz->getQuestions();

        $answers = $pt->getAnswers();

        return $this->render('user/answers.html.twig', [
            'answers' => $answers,
            'user_id'=>$user_id,
            'pt' => $pt,
            'questions' => $quizQuestion,
        ]);
    }
    
    

    //a = another
    #[Route('/user/{id}', name: 'app_a_user')]
    public function other($id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Users::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        
        $quizes = $user->getQuizes();

        return $this->render('user/public_quizes.html.twig', [
            'quizes' => $quizes,
        ]);
    }
}
