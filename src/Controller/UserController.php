<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $friendSent = $this->getUser()->getSentFriendRequests();
        $friendReceived = $this->getUser()->getReceivedFriendRequests();
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'friendSent' => $friendSent,
            'friendReceived' => $friendReceived,
        ]);
    }
    #[Route('/profile/{id}', name: 'app_profile')]
    public function profile(User $user): Response
    {   
        $activiters = $user->getActiviters();
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'activiters' => $activiters,
        ]);
    }

    
    #[Route('/friend/request/{id}', name: 'app_user_send_friend_request', methods: ['POST'])]
    public function sendFriendRequest(User $user, EntityManagerInterface $em)
    {   
        $friend = new Friend();
        $friend->setRequester($this->getUser());
        $friend->setReceiver($user);
        $friend->setStatus('accepted');
        $friend->setRequestedAt(new \DateTimeImmutable());
        $friend->setAcceptedAt(new \DateTimeImmutable());
        $em->persist($friend);
        $em->flush();
        $this->addFlash('success', 'Friend request sent successfully!');
        return $this->redirectToRoute('app_user');
    }
}
