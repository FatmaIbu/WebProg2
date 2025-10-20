<?php
require_once __DIR__ . '/BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct("products");
    }

    public function add($data) {
        if (!is_array($data)) {
            $data = $this->paramsToArray(func_get_args(), ['name', 'description', 'price', 'category_id']);
        }
        $this->validateProductData($data);
        return parent::add($data);
    }

    public function update($id, $data, $id_column = "product_id") {
        if (!is_array($data)) {
            $args = func_get_args();
            $data = $this->paramsToArray(array_slice($args, 1), ['name', 'description', 'price', 'category_id']);
        }
        $this->validateProductData($data);
        return parent::update($id, $data, $id_column);
    }

    public function get_by_id($product_id, $id_column = "product_id") {
        return parent::get_by_id($product_id, $id_column);
    }
    
    public function delete($product_id, $id_column = "product_id") {
        return parent::delete($product_id, $id_column);
    }

    public function get_by_category($category_id) {
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validateProductData($data) {
        $required = ['name', 'description', 'price', 'category_id'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }
    }

    private function paramsToArray($params, $keys) {
        $result = [];
        foreach ($keys as $index => $key) {
            if (isset($params[$index])) {
                $result[$key] = $params[$index];
            }
        }
        return $result;
    }
}