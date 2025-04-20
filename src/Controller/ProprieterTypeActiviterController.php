<?php

namespace App\Controller;

use App\Entity\TypeActiviter;
use App\Entity\ProprieterTypeActiviter;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProprieterTypeActiviterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProprieterTypeActiviterController extends AbstractController
{

    #[Route('/proprieter-type-activiter', name: 'app_proprieter_type_activiter')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $proprieters = $em->getRepository(ProprieterTypeActiviter::class)->findAll();

        return $this->render('proprieter_type_activiter/index.html.twig', [
            'proprieters' => $proprieters,
        ]);
    }

    #[Route('/proprieter-type-activiter/create', name: 'app_proprieter_type_activiter_create')]
    public function createProprieterTypeActiviter(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProprieterTypeActiviterType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $proprieterTypeActiviter = $form->getData();
            $em->persist($proprieterTypeActiviter);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('proprieter_type_activiter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/proprieter-type-activiter/{id}/edit', name: 'app_proprieter_type_activiter_edit')]
    public function editProprieterTypeActiviter(ProprieterTypeActiviter $proprieter_type_activiter ,Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProprieterTypeActiviterType::class, $proprieter_type_activiter);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $proprieterTypeActiviter = $form->getData();
            $em->persist($proprieterTypeActiviter);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('proprieter_type_activiter/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
