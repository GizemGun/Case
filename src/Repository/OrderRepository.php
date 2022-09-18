<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param array $postData
     * @return array
     */
    public function new(array $postData)
    {
        $result = ["isSuccess" => false, "message" => "No action taken", "data" => []];
        $em = $this->getEntityManager();
        try {
            /** @var User|null $owner */
            $owner = $em->find(User::class, (int)$postData["user"]);
            if (!is_null($owner)) {
                $order = new Order();
                $order
                    ->setUser($owner)
                    ->setAddress($postData["address"])
                    ->setOrderCode($this->recursiveOrder())
                    ->setShippingDate(null);
                foreach ($postData["products"] as $product) {
                    /** @var Product|null $productEntity */
                    $productEntity = $em->find(Product::class, $product["product"]);
                    if (!is_null($product)) {
                        $price = $productEntity->getPrice() * $product["quantity"];
                        $order
                            ->addOrderProduct(
                                (new OrderProduct())
                                    ->setPrice(floatval($price))
                                    ->setOrderr($order)
                                    ->setProduct($productEntity)
                                    ->setQuantity($product["quantity"])
                            );
                        $em->persist($order);
                        $em->flush();
                    } else {
                        $result["message"] = "Product not found";
                        return $result;
                    }
                }
            } else {
                $result["message"] = "User not found";
                return $result;
            }
            $result["isSuccess"] = true;
            $result["message"] = "Action taken";
            $result["data"] = [];
        } catch (\Exception $e) {
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    /**
     * @param array $postData
     * @return array
     */
    public function update(array $postData)
    {
        $result = ["isSuccess" => false, "message" => "No action taken", "data" => []];
        $em = $this->getEntityManager();
        try {
            /** @var Order|null $order */
            $order = $this->findOneBy(["id" => intval($postData["order"]), "user" => intval($postData["user"])]);
            if (!is_null($order)) {
                if (is_null($order->getShippingDate())) {
                    $order
                        ->setAddress($postData["address"]);
                    foreach ($postData["orderProducts"] as $orderProduct) {
                        /** @var OrderProduct|null $orderProductEntity */
                        $orderProductEntity = $em->getRepository(OrderProduct::class)->findOneBy(["orderr" => $order->getId(), "id" => $orderProduct["id"]]);
                        if (!is_null($orderProductEntity)) {
                            $price = $orderProductEntity->getProduct()->getPrice() * $orderProduct["quantity"];
                            $orderProductEntity
                                ->setPrice(floatval($price))
                                ->setQuantity($orderProduct["quantity"]);
                            $em->persist($orderProductEntity);
                            $em->flush();
                        } else {
                            $result["message"] = "Order product not found: " . $orderProduct["id"];
                            return $result;
                        }
                    }
                } else {
                    $result["message"] = "You cannot update this order because of shipping date decided.";
                    return $result;
                }
            } else {
                $result["message"] = "Order not found";
                return $result;
            }
            $result["isSuccess"] = true;
            $result["message"] = "Action taken";
            $result["data"] = [];
        } catch (\Exception $e) {
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    /**
     * @param array $postData
     * @return array
     */
    public function updateShipping(array $postData)
    {
        $result = ["isSuccess" => false, "message" => "No action taken", "data" => []];
        $em = $this->getEntityManager();
        try {
            /** @var Order|null $order */
            $order = $this->findOneBy(["orderCode" => $postData["orderCode"]]);
            if (!is_null($order)) {
                $order
                    ->setShippingDate((new \DateTime($postData["shippingDate"])));
                $em->persist($order);
                $em->flush();
            } else {
                $result["message"] = "Order not found";
                return $result;
            }
            $result["isSuccess"] = true;
            $result["message"] = "Action taken";
            $result["data"] = [];
        } catch (\Exception $e) {
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    /**
     * @param array $postData
     * @return array
     */
    public function getUserOrders(array $postData): array
    {
        $result = ["isSuccess" => true, "message" => "No action", "data" => null];
        try {
            $orders = $this->createQueryBuilder("o");
            $orders
                ->select("o.id, o.orderCode, o.shippingDate, o.address");
            $orders
                ->leftJoin("o.user", "u");
            $orders
                ->where("u.id= :user")
                ->setParameter("user", intval($postData["user"]));
            $orders = $orders->getQuery()->getArrayResult();
            $result["data"] = $orders;
        } catch (\Exception $e) {
            $result["isSuccess"] = false;
            $result["message"] = $e->getMessage();
        }
        return $result;
    }

    /**
     * @return string
     */
    public function recursiveOrder()
    {
        $em = $this->getEntityManager();
        $seed = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        shuffle($seed);
        $hrand = '';
        foreach (array_rand($seed, 10) as $k) $hrand .= $seed[$k];
        $randOrderId = $em->getRepository(Order::class)->findBy(['orderCode' => $hrand]);
        if (count($randOrderId) > 1) {
            return $this->recursiveOrder();
        } else {
            return $hrand;
        }
    }
}
