<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\WarehouseOperationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse')]
    public function index(WarehouseOperationRepository $operationRepo): Response
    {
        // Get the logged-in user
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Fetch the warehouses assigned to this user
        $warehouses = $user->getWarehouses();

        $dashboardData = [];

        // For each warehouse, get its real-time stock list
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
}