<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Repository\ActiviterRepository;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EvenementController extends AbstractController
{
    #[Route('/evenement', name: 'app_evenement')]
    public function index(EvenementRepository $evenementRepository, ActiviterRepository $activiterRepository): Response
    {
        $evenement = $evenementRepository->find(1);
        $data = $activiterRepository->getUserProgressForEvent($evenement->getActiviterType(),$evenement->getUnit(),$evenement->getDateDebut(),$evenement->getDateFin());
        return $this->render('evenement/index.html.twig', [
            'event' => $evenement,
            'eventData' => $data,
        ]);
    }
}
