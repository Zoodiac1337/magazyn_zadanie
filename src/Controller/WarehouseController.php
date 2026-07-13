<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse')]
    public function index(): Response
    {
        return $this->render('warehouse.html.twig');

    }
}