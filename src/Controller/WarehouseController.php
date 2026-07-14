<?php

namespace App\Controller;
use App\Form\ReceiveType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse')]
    public function index(): Response
    {
        return $this->render('warehouse.html.twig');

    }

    #[Route('/receive-goods', name: 'app_receive_goods')]
    public function receiveGoods(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operation = new \App\Entity\WarehouseOperation();
        $form = $this->createForm(ReceiveType::class, $operation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $operation->setType('IN'); // Set the operation type to 'IN' for receiving goods
            $operation->setUser($this->getUser()); // Set the user who performed the
            $operation->setCreatedAt(new \DateTime()); // Timestamp of the operation
            $entityManager->persist($operation);
            $entityManager->flush();

            // Display a success message and redirect to the warehouse page
            $this->addFlash('success', 'Goods received successfully!');
            return $this->redirectToRoute('app_receive_goods');
        }
        return $this->render('receivegoods.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/issue-goods', name: 'app_issue_goods')]
    public function issueGoods(): Response
    {
        return $this->render('issuegoods.html.twig');

    }

    #[Route('/manage-warehouses', name: 'app_manage_warehouses')]
    public function manageWarehouses(): Response
    {
        return $this->render('managewarehouses.html.twig');

    }

    #[Route('/manage-users', name: 'app_manage_users')]
    public function manageUsers(): Response
    {
        return $this->render('manageusers.html.twig');

    }

    #[Route('/manage-items', name: 'app_manage_items')]
    public function manageItems(): Response
    {
        return $this->render('manageitems.html.twig');

    }
}