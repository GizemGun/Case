<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderProduct>
 *
 * @method OrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProduct[]    findAll()
 * @method OrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    /**
     * @param array $postData
     * @return array
     */
    public function getOrderDetails(array $postData): array
    {
        $result = ["isSuccess" => true, "message" => "No action", "data" => null];
        try {
            $orders = $this->createQueryBuilder("op");
            $orders
                ->select("o.id as orderId, o.orderCode")
                ->addSelect("op.id as orderProductId, op.quantity, op.price")
                ->addSelect("p.name");
            $orders
                ->leftJoin("op.orderr", "o")
                ->leftJoin("o.user", "u")
                ->leftJoin("op.product", "p");
            $orders
                ->where("u.id= :user")
                ->setParameter("user", intval($postData["user"]))
                ->andWhere("o.orderCode=:orderCode")
                ->setParameter("orderCode", $postData["orderCode"]);
            $orders = $orders->getQuery()->getArrayResult();
            $result["data"] = $orders;
        } catch (\Exception $e) {
            $result["isSuccess"] = false;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }
}
