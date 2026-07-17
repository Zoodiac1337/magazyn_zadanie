<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\WarehouseOperationRepository;
use App\Repository\WarehouseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WarehouseController extends AbstractController
{
    #[Route('/', name: 'app_warehouse')]
    public function index(WarehouseOperationRepository $operationRepo, WarehouseRepository $warehouseRepo): Response
    {
        // Get the logged-in user
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Check if the user is an Admin
        if ($this->isGranted('ROLE_ADMIN')) {
            // Admins see all warehouses
            $warehouses = $warehouseRepo->findAll();
        } else {
            // Normal users only see their assigned warehouses
            $warehouses = $user->getWarehouses();
        }

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