<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    public function add($data) {
        $this->validateUserData($data);
        return parent::add($data);
    }

    public function update($user_id, $data, $id_column = "user_id") {
        $this->validateUserData($data);
        return parent::update($user_id, $data, $id_column);
    }

    public function get_by_id($user_id, $id_column = "user_id") {
        return parent::get_by_id($user_id, $id_column);
    }
    
    public function delete($user_id, $id_column = "user_id") {
        return parent::delete($user_id, $id_column);
    }

    private function validateUserData($data) {
        $required = ['username', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }
    }
}