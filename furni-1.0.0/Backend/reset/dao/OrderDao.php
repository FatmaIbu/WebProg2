<?php
require_once __DIR__ . '/BaseDao.php';

class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct("orders");
    }

    public function add($data) {
        // Handle both array and parameter list formats
        if (!is_array($data)) {
            $data = [
                'user_id' => $data,
                'status' => func_get_arg(1),
                'total_amount' => func_get_arg(2)
            ];
        }

        $this->validateOrderData($data);
        return parent::add($data);
    }

    public function update($order_id, $data, $id_column = "order_id") {
        if (!is_array($data)) {
            $data = [
                'user_id' => $data,
                'status' => func_get_arg(1),
                'total_amount' => func_get_arg(2)
            ];
        }
        return parent::update($order_id, $data, $id_column);
    }

    public function get_by_id($order_id, $id_column = "order_id") {
        return parent::get_by_id($order_id, $id_column);
    }
    
    public function delete($order_id, $id_column = "order_id") {
        return parent::delete($order_id, $id_column);
    }

    public function get_by_user($user_id) {
        $query = "SELECT * FROM orders WHERE user_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validateOrderData($data) {
        $required = ['user_id', 'status', 'total_amount'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }
    }
}