<?php

namespace MyProject\Models\Products;

use MyProject\Services\Db as Db;
//use MyProject\Models\Users\User as User;
use MyProject\Exceptions\InvalidArgumentException as InvalidArgumentException;
use Qiwi\Api\BillPayments;

class Product {
    
    public $mode;
   
    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }
    
    public function setStatusModePay()
    {
         $result = $this->db->query('SELECT * FROM settings', [], self::class);
         $this->mode = $result[0]->status;
    }
    
    

    public function findAll(): array {
        return $this->db->query('SELECT * FROM products ORDER BY category_id ;', [], self::class);
    }

    public function getProductById($id) {
        $data = $this->db->query('SELECT * FROM products WHERE id=:id;', [':id' => $id], self::class);
        return json_encode($data[0]);
    }

    public function getAllProductsCart($ids) { //нужен при открывании корзины
        $idList = implode(',', array_fill(0, count(explode(',', $ids)), '?'));

        $query = 'SELECT * FROM products WHERE id IN (' . $idList . ')';

        $data = $this->db->query('SELECT * FROM products WHERE id IN (' . $idList . ')', [$ids, 'in'], self::class);

        $newArr = [];

        foreach ($data as $key => $product) { //что бы потом на клиенте было проще определить количество продуктов добавленных к ворзину
            $newArr[$product->id] = $product;
        }

        return json_encode($newArr);
    }

    public function getPayOrderProduct() {

        $sql = "SELECT * FROM orders WHERE id_user = " . $_SESSION["user"] . " AND status = 'PAID' ORDER BY id DESC";
        $orders = $this->db->query($sql, [], self::class);
        return $orders;
    }

    public function pay($data) {

       
        if (!isset($_SESSION["user"])) {
            return json_encode(['error' => false]);
        }

        if ($data == '{}') {
            return json_encode(['error' => 'empty']);
        }
        
        $this->setStatusModePay();
        
 
        $data = json_decode($data);

        try {
            $whenThen = '';
            $ids = '';

            foreach ($data as $id => $quantity) {
                $whenThen = $whenThen . ' WHEN ' . $id . ' THEN ' . $quantity . ' ';
                $ids = $ids . $id . ',';
            }

            $ids = rtrim($ids, ",");

            $bytes = random_bytes(14);
            $billId = vsprintf('%s-%s-%s-%s', str_split(bin2hex($bytes), 7));

            $sql = 'SELECT id, title, price, ' . $_SESSION["user"] . ' as id_user, "wating" as status, "' . $billId . '" as billId,
          CASE id ' . $whenThen . '
          ELSE 1
          END AS quantity,
          price *
          CASE id ' . $whenThen . '
          ELSE 1
          END AS totalPrice
          FROM products
          WHERE id IN (' . $ids . ')';

            $res = $this->db->query($sql, [], self::class);
            $newOrder = '';

            $totalPrice = 0;

            foreach ($res as $order) {
                $totalPrice += $order->totalPrice;
                $newOrder .= "('" .
                        $order->title . "', " .
                        $order->price . ", " .
                        $order->id_user . ", '" .
                        $order->status . "', '" .
                        $order->billId . "', " .
                        $order->quantity . ', ' .
                        $order->totalPrice . ")," . "\n";
            }

            $insertOrderBD = 'INSERT INTO orders
          (title, price, id_user, status, billId, quantity, totalPrice)
          VALUES ' . $newOrder;

            $insertOrderBD = substr($insertOrderBD, 0, -2);

            $createOder = $this->db->query($insertOrderBD, [], self::class);

            if ($this->mode == 1) {
                return json_encode(['success' => true, 'order_id' => $billId]);
            } else if ($this->mode == 0) {
                $SECRET_KEY = '';

                $publicKey = '';

                $billPayments = new \Qiwi\Api\BillPayments($SECRET_KEY);

                $params = [
                    'publicKey' => $publicKey,
                    'amount' => $totalPrice,
                    'billId' => $billId,
                    'comment' => 'id пользователя: ' . $_SESSION["user"] . ', заказ: ' . $billId,
                    'successUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/payment/check/' . $billId
                ];

                $link = $billPayments->createPaymentForm($params);
                return json_encode(['link' => $link]);
            }
        } catch (\Exception $e) {
            return json_encode(['error' => 'Ошибка']);
        }
    }

    public function orderCheck($billId) {

 
        if (!isset($_SESSION["user"]))
            return false;


        $sql = "SELECT * FROM orders WHERE billId = '" . $billId . "' AND id_user = " . $_SESSION["user"];
        $result = $this->db->query($sql, [], self::class);

        if ($result[0]->status === 'PAID') {
            return true;
        }
        
        $this->setStatusModePay();
        
        if (!empty($result)) {
            if ($this->mode == 1) {
                $sqlUpdate = "UPDATE orders SET status='PAID' WHERE billId = ? AND id_user = ?";

                $resultUpdate = $this->db->update($sqlUpdate, [$billId, $_SESSION["user"]]);
                if ($resultUpdate > 0) {
                    return true;
                }
            } 
            elseif ($this->mode == 0) {
                $SECRET_KEY = '';

                $publicKey = '';

                $billPayments = new \Qiwi\Api\BillPayments($SECRET_KEY);

                $response = $billPayments->getBillInfo($billId);

                if ($response['status']['value'] === 'PAID') {
                    $sqlUpdate = "UPDATE orders SET status='PAID' WHERE billId = ? AND id_user = ?";

                    $resultUpdate = $this->db->update($sqlUpdate, [$billId, $_SESSION["user"]]);
                    if ($resultUpdate > 0) {
                        return true;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

}
