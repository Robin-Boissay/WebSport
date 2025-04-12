<?php

namespace App\Controller;

use App\Entity\Activiter;
use App\Entity\TypeActiviter;
use App\Entity\User;
use App\Form\ActiviterType;
use App\Form\ActiviterType2;
use App\Repository\ActiviterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ActiviterController extends AbstractController
{


    #[Route('/activiter', name: 'app_activiter')]
    public function index(ActiviterRepository $activiterRepository): Response
    {
        $activiters = $activiterRepository->findAllByUser($this->getUser());

        return $this->render('activiter/index.html.twig', [
            'activiters' => $activiters,
        ]);
    }
    #[Route('/activiter/showActiver/{id}', name: 'app_activiter_show')]
    public function showActiviterUserId(User $user, ActiviterRepository $activiterRepository): Response
    {
        $activiters = $activiterRepository->findAllByUser($user);

        return $this->render('activiter/index.html.twig', [
            'activiters' => $activiters,
        ]);
    }


    #[Route('/activiter/create', name: 'app_activiter_create')]
    public function createActiviter( Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(ActiviterType2::class, new Activiter());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $activiter = $form->getData();
            $activiter->setCreatedAt(new \DateTimeImmutable());
            $activiter->setUser($this->getUser());
            $em->persist($activiter);
            $em->flush();
            return $this->redirectToRoute('app_activiter_create_final', ['activiter' => $activiter->getId()]);
        }

        return $this->render('activiter/pre_create.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    #[Route('/activiter/create/{activiter}', name: 'app_activiter_create_final')]
    public function createActiviterFinal(Activiter $activiter): Response
    {
        return $this->render('activiter/create.html.twig', [
            'activiter' => $activiter,
        ]);
    }
}
