<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
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
