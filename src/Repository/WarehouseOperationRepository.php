<?php

namespace App\Repository;

use App\Entity\Warehouse;
use App\Entity\WarehouseOperation;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseOperation>
 */
class WarehouseOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseOperation::class);
    }

       /**
        * @return WarehouseOperation[] Returns an array of WarehouseOperation objects
        */
       public function getStockReportForWarehouse(Warehouse $warehouse): array
       {
           return $this->createQueryBuilder('o')
            ->select('p.id as productId', 'p.name as productName', 'p.unit as productUnit')
            // Calculate: SUM(if IN, add quantity; if OUT, subtract quantity)
            ->addSelect("SUM(CASE WHEN o.type = 'IN' THEN o.quantity ELSE -o.quantity END) as totalStock")
            ->join('o.product', 'p')
            ->where('o.warehouse = :warehouse')
            ->setParameter('warehouse', $warehouse)
            ->groupBy('p.id')
            // Having totalStock > 0 ensures we don't list products with 0 stock
            ->having('totalStock > 0')
            ->getQuery()
            ->getResult();
       }

       public function getCurrentStock(Warehouse $warehouse, Product $product): int
       {
        $result = $this->createQueryBuilder('o')
            ->select("SUM(CASE WHEN o.type = 'IN' THEN o.quantity ELSE -o.quantity END)")
            ->where('o.warehouse = :warehouse')
            ->andWhere('o.product = :product')
            ->setParameter('warehouse', $warehouse)
            ->setParameter('product', $product)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
}

    //    public function findOneBySomeField($value): ?WarehouseOperation
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
