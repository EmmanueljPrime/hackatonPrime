<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/clients')]
final class ClientController extends AbstractController
{
    #[Route(name: 'client_index', methods: ['GET'])]
    public function index(Request $request, ClientRepository $clientRepository, PaginatorInterface $paginator): Response
    {

        // Récupérer le terme de recherche
        $searchTerm = $request->query->get('q');
        $itemsPerPage = $request->query->getInt('items_per_page', 10);

        $sortBy = $request->query->get('sort_by', 'fullname');
        $sortOrder = $request->query->get('sort_order', 'asc');

        if ($searchTerm) {
            $clients = $clientRepository->findBySearchTerm($searchTerm);
        } else {
            $clients = $clientRepository->findAll();
        }
        // Appliquer le tri en fonction du critère et de la direction
        if ($sortBy == 'fullname') {
            usort($clients, fn($a, $b) => $sortOrder == 'asc' ? strcmp($a->getFullname(), $b->getFullname()) : strcmp($b->getFullname(), $a->getFullname()));
        } elseif ($sortBy == 'email') {
            usort($clients, fn($a, $b) => $sortOrder == 'asc' ? strcmp($a->getEmail(), $b->getEmail()) : strcmp($b->getEmail(), $a->getEmail()));
        } elseif ($sortBy == 'company') {
            usort($clients, fn($a, $b) => $sortOrder == 'asc' ? strcmp($a->getCompany(), $b->getCompany()) : strcmp($b->getCompany(), $a->getCompany()));
        } else {
            // Par défaut, trier par ID
            usort($clients, fn($a, $b) => $sortOrder == 'asc' ? $a->getId() <=> $b->getId() : $b->getId() <=> $a->getId());
        }


        $pagination = $paginator->paginate(
            $clients,
            $request->query->getInt('page', 1),
            $itemsPerPage
        );

        // Calculer la somme des factures pour chaque client
        foreach ($clients as $client) {
            $totalAmount = array_reduce($client->getFactures()->toArray(), function ($sum, $facture) {
                return $sum + $facture->getAmount();
            }, 0);

            $client->totalAmount = $totalAmount;
        }

        return $this->render('client/index.html.twig', [
            'clients' => $pagination,
            'searchTerm' => $searchTerm,
            'itemsPerPage' => $itemsPerPage,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    #[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Client supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('client_index');
    }
}
