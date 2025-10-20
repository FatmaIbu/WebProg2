<?php
require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao {
    public function __construct() {
        parent::__construct("reviews");
    }

    public function add($data) {
        // Handle both array and parameter list formats
        if (!is_array($data)) {
            $data = [
                'user_id' => $data,
                'product_id' => func_get_arg(1),
                'rating' => func_get_arg(2),
                'comment' => func_get_arg(3)
            ];
        }

        $this->validateReviewData($data);
        return parent::add($data);
    }

    public function update($review_id, $data, $id_column = "review_id") {
        if (!is_array($data)) {
            $data = [
                'rating' => $data,
                'comment' => func_get_arg(1)
            ];
        }
        $this->validateReviewData($data, false); // false = skip user_id/product_id check for updates
        return parent::update($review_id, $data, $id_column);
    }

    public function get_by_id($review_id, $id_column = "review_id") {
        return parent::get_by_id($review_id, $id_column);
    }
    
    public function delete($review_id, $id_column = "review_id") {
        return parent::delete($review_id, $id_column);
    }

    public function get_by_product($product_id) {
        $query = "SELECT * FROM reviews WHERE product_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function validateReviewData($data, $checkAllFields = true) {
        $required = $checkAllFields 
            ? ['user_id', 'product_id', 'rating', 'comment']
            : ['rating', 'comment'];
            
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException("Missing required field: $field");
            }
        }
        
        // Validate rating range
        if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
            throw new InvalidArgumentException("Rating must be between 1 and 5");
        }
    }
}