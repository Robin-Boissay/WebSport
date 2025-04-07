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
    public function index(EntityManagerInterface $em, Activiter $activiter): Response
    {
        $activiters = $em->getRepository(Activiter::class)->findAll();

        return $this->render('home/index.html.twig', [
            'activiters' => $activiters,
        ]);
    }
}
