<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $result = ["isSuccess" => true, "message" => "No action", "data" => null];
        try {
            $orders = $this->createQueryBuilder("p");
            $orders
                ->select("p.id, p.name, p.price");
            $orders = $orders->getQuery()->getArrayResult();
            $result["data"] = $orders;
        } catch (\Exception $e) {
            $result["isSuccess"] = false;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }
}
