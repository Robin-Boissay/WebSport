<?php

namespace App\Controller;

use App\Entity\Friend;
use App\Entity\ProprieterTypeActiviter;
use App\Entity\TypeActiviter;
use App\Entity\User;
use App\Repository\ActiviterRepository;
use App\Repository\FriendRepository;
use App\Repository\ProprieterTypeActiviterRepository;
use App\Repository\TypeActiviterRepository;
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
    #[Route('/profile/{id}/showStat', name: 'app_profile_show_stats')]
    public function profileShowStats(User $user, ActiviterRepository $activiterRepository, TypeActiviterRepository $typeActiviter, ProprieterTypeActiviterRepository $proprieterTypeActiviterRepository): Response
    {  

        $flatStats = $activiterRepository->getAllUserStatsGrouped($user);
        $aggregatedData = [];
        foreach ($flatStats as $item) {
            $typeActiviterName = $item['typeActiviter'];
            $unite = $item['unite'];
            $total = $item['total'];
        
            if (!isset($aggregatedData[$typeActiviterName])) {
                $aggregatedData[$typeActiviterName] = [
                    'user_id' => $item['user_id'],
                    'username' => $item['username'],
                    'typeActiviter' => $typeActiviterName,
                    'stats' => []
                ];
            }
            $aggregatedData[$typeActiviterName]['stats'][$unite] = $total;
        }

        return $this->render('user/show_stats.html.twig', [
            'username' => $user->getUsername(),
            'activitiesData' => $aggregatedData
        ]);
    }

    
    #[Route('/friend/request/{id}', name: 'app_user_send_friend_request', methods: ['POST'])]
    public function sendFriendRequest(User $user, EntityManagerInterface $em, FriendRepository $friendRepository)
    {   
        if($friendRepository->areFriends($user, $this->getUser())){
            $this->addFlash('warning', 'Vous êtes déjà amis');
            return $this->redirectToRoute('app_user');
        }
        $friend = new Friend();
        $friend->setRequester($this->getUser());
        $friend->setReceiver($user);
        $friend->setStatus('pending');
        $friend->setRequestedAt(new \DateTimeImmutable());
        $friend->setAcceptedAt(new \DateTimeImmutable());
        $em->persist($friend);
        $em->flush();
        $this->addFlash('success', 'Friend request sent successfully!');
        return $this->redirectToRoute('app_user');
    }

    #[Route('/friend/accept-request/{id}', name: 'app_accept_friend_request', methods: ['POST'])]
    public function acceptFriendRequest(Friend $friend, EntityManagerInterface $em, FriendRepository $friendRepository)
    {   

        $friend->setStatus('accepted');
        $friend->setAcceptedAt(new \DateTimeImmutable());
        $em->persist($friend);
        $em->flush();
        $this->addFlash('success', 'Friend request accepted successfully!');
        return $this->redirectToRoute('app_user');
    }
    #[Route('/friend/decline-request/{id}', name: 'app_decline_friend_request', methods: ['POST'])]
    public function declineFriendRequest(Friend $friend, EntityManagerInterface $em, FriendRepository $friendRepository)
    {   

        $friend->setStatus('declined');
        $em->persist($friend);
        $em->flush();
        $this->addFlash('success', 'Friend request declined successfully!');
        return $this->redirectToRoute('app_user');
    }
    
}

