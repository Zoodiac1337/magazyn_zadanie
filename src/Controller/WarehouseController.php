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

    #[Route('/receive-goods', name: 'app_receive_goods')]
    public function receiveGoods(): Response
    {
        return $this->render('receivegoods.html.twig');

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