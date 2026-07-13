<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class TestUsersController
{
    // Commented out the route to prevent accidental execution. Uncomment to enable.
    // #[Route('/test-users', name: 'app_test_users')]
    public function index(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // regular user
        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $hashedUserPassword = $passwordHasher->hashPassword($user, 'user123');
        $user->setPassword($hashedUserPassword);

        // admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedAdminPassword = $passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedAdminPassword);

        // persist users to the database
        $entityManager->persist($user);
        $entityManager->persist($admin);
        // flush changes to the database
        $entityManager->flush();

        return new Response('Test users created successfully.');
    }
}