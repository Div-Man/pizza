<?php

namespace MyProject\Models\Admin;

use MyProject\Services\Db as Db;

class Main {

    public $db;

    public function __construct() {
        $this->db = Db::getInstance();
    }

    public function getStatusTestMode() {
        $sql = "SELECT * FROM settings WHERE id = 1;";
        $mode = $this->db->query($sql, [], self::class);
        return $mode;
    }

    public function updateStatusPay($data) {
        $sql = "UPDATE settings SET status=:status";
        $this->db->query($sql, [':status' => $data['status']], self::class);
    }

    public function getAllOrders() {

        $sql = "SELECT 'totalPrice' as name, SUM(totalPrice) as value
            FROM orders
            WHERE status = 'PAID'
            UNION ALL
            SELECT 'quantityOrder' as name, COUNT(DISTINCT billid) as value
            FROM orders
            WHERE status = 'PAID';

            ";

        $result = $this->db->query($sql, [], self::class);

        $infoOrder = new \StdClass();

        foreach ($result as $val) {
            $property = $val->name;
            $infoOrder->$property = $val->value;
        }

        return $infoOrder;
    }

}
