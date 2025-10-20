<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao {
    public function __construct() {
        parent::__construct("categories");
    }

    public function add($data) {
        if (!is_array($data)) {
            $data = ['name' => $data];
        }
        $this->validateCategoryData($data);
        return parent::add($data);
    }

    public function update($category_id, $data, $id_column = "category_id") {
        if (!is_array($data)) {
            $data = ['name' => $data];
        }
        $this->validateCategoryData($data);
        return parent::update($category_id, $data, $id_column);
    }

    public function get_by_id($category_id, $id_column = "category_id") {
        return parent::get_by_id($category_id, $id_column);
    }
    
    public function delete($category_id, $id_column = "category_id") {
        return parent::delete($category_id, $id_column);
    }

    private function validateCategoryData($data) {
        if (!isset($data['name']) || empty($data['name'])) {
            throw new InvalidArgumentException("Category name is required");
        }
    }
}