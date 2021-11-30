<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Exception\LogicException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/admin/advert')]
class AdvertController extends AbstractController
{

    private WorkflowInterface $advertPublishingStateMachine;

    public function __construct(WorkflowInterface $advertPublishingStateMachine)
    {
        $this->advertPublishingStateMachine = $advertPublishingStateMachine;
    }


    #[Route('/', name: 'advert_index', methods: ['GET'])]
    public function index(AdvertRepository $advertRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $adverts = $paginator->paginate(
            $advertRepository->findAll(),
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }


    #[Route('/new', name: 'advert_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $advert = new Advert();
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($advert);
            $entityManager->flush();

            return $this->redirectToRoute('advert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advert/new.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'advert_show', methods: ['GET'])]
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }


    #[Route('/{id}/edit', name: 'advert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Advert $advert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('advert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('advert/edit.html.twig', [
            'advert' => $advert,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'advert_delete', methods: ['POST'])]
    public function delete(Request $request, Advert $advert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($advert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('advert_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/{state}', name: 'advert_workflow', methods: ['GET', 'POST'])]
    public function switchWorkflowState(Request $request, Advert $advert, EntityManagerInterface $entityManager, $state): Response
    {
        dump($advert);
        if($state === 'to_rejected' || $state === 'publish' || $state === 'reject'){
            try {
                $this->advertPublishingStateMachine->apply($advert, $state);
                $entityManager->flush();
            } catch (LogicException $exception) {
                // ...
            }
        }

        return $this->redirectToRoute('advert_index', [], Response::HTTP_SEE_OTHER);

    }
}
