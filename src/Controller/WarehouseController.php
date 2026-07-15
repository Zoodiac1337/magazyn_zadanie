<?php

namespace App\Controller;
use App\Form\ReceiveType;
use App\Form\IssueType;
use App\Entity\User;
use App\Repository\WarehouseOperationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse')]
    public function index(WarehouseOperationRepository $operationRepo): Response
    {
        // 1. Get the logged-in user
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // 2. Fetch the warehouses assigned to this user
        // (Assumes a ManyToMany relation: $user->getWarehouses())
        $warehouses = $user->getWarehouses();

        $dashboardData = [];

        // 3. For each warehouse, get its real-time stock list
        foreach ($warehouses as $warehouse) {
            $dashboardData[] = [
                'warehouse' => $warehouse,
                'stock' => $operationRepo->getStockReportForWarehouse($warehouse)
            ];
        }

        return $this->render('warehouse.html.twig', [
            'data' => $dashboardData,
        ]);
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

            // Display a success message
            $this->addFlash('success', 'Goods received successfully!');
        }
        return $this->render('receivegoods.html.twig', [
            'form' => $form->createView(),
        ]);

    }

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

    #[Route('/manage-warehouses', name: 'app_manage_warehouses')]
    public function manageWarehouses(Request $request, EntityManagerInterface $entityManager): Response
    {
        $warehouse = new \App\Entity\Warehouse();
        $form = $this->createForm(\App\Form\WarehouseType::class, $warehouse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($warehouse);
            $entityManager->flush();

            // Display a success message
            $this->addFlash('success', 'Warehouse created successfully!');
        }
        return $this->render('managewarehouses.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/manage-users', name: 'app_manage_users')]
    public function manageUsers(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new \App\Entity\User();
        $form = $this->createForm(\App\Form\UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if the "isAdmin" checkbox is checked and set the role accordingly
            if ($form->get('isAdmin')->getData()) {
                $user->setRoles(['ROLE_ADMIN']); // Assign admin role if checkbox is checked
            } else {
                $user->setRoles(['ROLE_USER']); // Default role for regular users
            }
            // Hash the password before saving
            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            // Display a success message
            $this->addFlash('success', 'User created successfully!');
        }
        return $this->render('manageusers.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/manage-items', name: 'app_manage_items')]
    public function manageItems(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new \App\Entity\Product();
        $form = $this->createForm(\App\Form\ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
        }
        return $this->render('manageitems.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}