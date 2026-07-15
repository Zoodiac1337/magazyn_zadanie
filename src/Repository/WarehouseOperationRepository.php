<?php

namespace App\Repository;

use App\Entity\Warehouse;
use App\Entity\WarehouseOperation;
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
