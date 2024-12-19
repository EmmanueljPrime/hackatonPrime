<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/factures')]
final class FactureController extends AbstractController
{
    #[Route(name: 'facture_index', methods: ['GET'])]
    public function index(Request $request, FactureRepository $factureRepository, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('q');
        $itemsPerPage = $request->query->getInt('items_per_page', 10);
        $sortBy = $request->query->get('sort_by', 'date');
        $sortOrder = $request->query->get('sort_order', 'asc');


        if ($searchTerm) {
            $factures = $factureRepository->findBySearchTerm($searchTerm);
        } else {
            $factures = $factureRepository->findAll();
        }

        if ($sortBy == 'client') {
            usort($factures, fn($a, $b) => $sortOrder == 'asc' ? strcmp($a->getClient()->getFullname(), $b->getClient()->getFullname()) : strcmp($b->getClient()->getFullname(), $a->getClient()->getFullname()));
        } elseif ($sortBy == 'sendingDate') {
            usort($factures, fn($a, $b) => $sortOrder == 'asc' ? $a->getSendingDate() <=> $b->getSendingDate() : $b->getSendingDate() <=> $a->getSendingDate());
        } elseif ($sortBy == 'status') {
            usort($factures, fn($a, $b) => $sortOrder == 'asc' ? strcmp($a->getStatus(), $b->getStatus()) : strcmp($b->getStatus(), $a->getStatus()));
        } elseif ($sortBy == 'amount') {
            usort($factures, fn($a, $b) => $sortOrder == 'asc' ? $a->getAmount() <=> $b->getAmount() : $b->getAmount() <=> $a->getAmount());
        } else {
            // Par défaut, trier par numéro (id)
            usort($factures, fn($a, $b) => $sortOrder == 'asc' ? $a->getId() <=> $b->getId() : $b->getId() <=> $a->getId());
        }

        $pagination = $paginator->paginate(
            $factures,
            $request->query->getInt('page', 1),
            $itemsPerPage
        );

        return $this->render('facture/index.html.twig', [
            'factures' => $pagination,
            'searchTerm' => $searchTerm,
            'itemsPerPage' => $itemsPerPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/new', name: 'facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
