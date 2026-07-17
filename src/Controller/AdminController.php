<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
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
        return $this->render('admin/managewarehouses.html.twig', [
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
        return $this->render('admin/manageusers.html.twig', [
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
        return $this->render('admin/manageitems.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}