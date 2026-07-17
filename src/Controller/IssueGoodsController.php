<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\IssueType;
use App\Repository\WarehouseOperationRepository;

class IssueGoodsController extends AbstractController
{
    #[Route('/issue-goods', name: 'app_issue_goods')]
    public function issueGoods(Request $request, EntityManagerInterface $entityManager, WarehouseOperationRepository $operationRepo): Response
    {
        $operation = new \App\Entity\WarehouseOperation();
        $form = $this->createForm(IssueType::class, $operation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the quantity to be issued is available in stock
            $currentStock = $operationRepo->getCurrentStock($operation->getWarehouse(), $operation->getProduct());
            // If the requested quantity exceeds the current stock, show an error message
            if ($operation->getQuantity() > $currentStock) {
                $this->addFlash('error', sprintf('Insufficient stock to issue the requested quantity. Available stock: %d', $currentStock));
                return $this->render('issuegoods.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            // If the stock is sufficient, proceed to issue the goods
            $operation->setType('OUT'); // Set the operation type to 'OUT' for issuing goods
            $operation->setUser($this->getUser()); // Set the user who performed the
            $operation->setCreatedAt(new \DateTime()); // Timestamp of the operation
            $entityManager->persist($operation);
            $entityManager->flush();

            // Display a success message
            $this->addFlash('success', 'Goods issued successfully!');
        }
        return $this->render('issuegoods.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}