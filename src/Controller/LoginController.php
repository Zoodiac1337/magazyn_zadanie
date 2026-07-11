<?php
//src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // Logika logowania użytkownika będzie tutaj.

        return $this ->render(
            'login.html.twig',
            [
                'title' => 'Login'
            ]
        );
    }
}
?>