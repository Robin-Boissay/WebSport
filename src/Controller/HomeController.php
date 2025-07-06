<?php

namespace App\Controller;

use App\Entity\Activiter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $friends = [];

        foreach ($user->getSentFriendRequests() as $friendRequest) {
            if ($friendRequest->getStatus() === 'accepted') {
                $friends[] = $friendRequest->getReceiver();
            }
        }

        foreach ($user->getReceivedFriendRequests() as $friendRequest) {
            if ($friendRequest->getStatus() === 'accepted') {
                $friends[] = $friendRequest->getRequester();
            }
        }

        // On ajoute l'utilisateur lui-même
        $users = array_merge([$user], $friends);

        // On récupère toutes les activités
        $activites = $em->getRepository(Activiter::class)->findByUsers($users);
        return $this->render('home/index.html.twig', [
            'activiters' => $activites,
        ]);
    }
}
