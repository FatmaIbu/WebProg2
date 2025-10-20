<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/BaseDao.php';
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/ProductDao.php';
require_once __DIR__ . '/OrderDao.php';
require_once __DIR__ . '/CategoryDao.php';
require_once __DIR__ . '/ReviewDao.php';

class DAOTestRunner {
    private $pdo;
    private $userDao;
    private $productDao;
    private $orderDao;
    private $categoryDao;
    private $reviewDao;

    public function __construct() {
        // Initialize all DAOs
        $this->userDao = new UserDao();
        $this->productDao = new ProductDao();
        $this->orderDao = new OrderDao();
        $this->categoryDao = new CategoryDao();
        $this->reviewDao = new ReviewDao();
        
        // Get PDO instance for transactions
        $this->pdo = $this->userDao->getConnection();
    }

    public function runAllTests() {
        try {
            $this->pdo->beginTransaction();
            
            $this->printHeader("STARTING DAO TESTS");
            
            $this->testUserDAO();
            $this->testProductDAO();
            $this->testCategoryDAO(); 
            $this->testOrderDAO();
            $this->testReviewDAO();
            
            // Rollback to avoid permanent changes
            $this->pdo->rollBack();
            
            $this->printHeader("ALL TESTS COMPLETED SUCCESSFULLY");
        } catch (Exception $e) {
            $this->pdo->rollBack();
            $this->printError("TEST FAILED: " . $e->getMessage());
            throw $e;
        }
    }

    private function testUserDAO() {
        $this->printHeader("USER DAO TESTS");
        
        // Test get_all()
        $users = $this->userDao->get_all();
        $this->printResult("Get all users", !empty($users));
        
        // Test get_by_id()
        $testUser = $this->userDao->get_by_id(1);
        $this->printResult("Get user by ID", !empty($testUser));
        
        // Test add()
        $userData = [
            'username' => 'testuser_' . uniqid(),
            'email' => 'test_' . uniqid() . '@example.com',
            'password' => password_hash('testpass', PASSWORD_DEFAULT)
        ];
        $userId = $this->userDao->add($userData);
        $this->printResult("Add new user", $userId > 0);
        
        // Test update()
        $updateData = [
            'username' => 'updated_' . $userData['username'],
            'email' => 'updated_' . $userData['email']
        ];
        $updateSuccess = $this->userDao->update($userId, $updateData);
        $this->printResult("Update user", $updateSuccess);
        
        // Verify update
        $updatedUser = $this->userDao->get_by_id($userId);
        $this->printResult("Verify update", 
            $updatedUser['username'] === $updateData['username'] && 
            $updatedUser['email'] === $updateData['email']
        );
        
        // Test delete
        $deleteSuccess = $this->userDao->delete($userId);
        $this->printResult("Delete user", $deleteSuccess);
        
        // Verify delete
        $deletedUser = $this->userDao->get_by_id($userId);
        $this->printResult("Verify delete", empty($deletedUser));
    }

    private function testProductDAO() {
        $this->printHeader("PRODUCT DAO TESTS");
        
        // First create a test category
        $categoryId = $this->categoryDao->add("TestCategory_" . uniqid());
        
        // Test get_all()
        $products = $this->productDao->get_all();
        $this->printResult("Get all products", !empty($products));
        
        // Test get_by_id()
        $testProduct = $this->productDao->get_by_id(1);
        $this->printResult("Get product by ID", !empty($testProduct));
        
        // Test add()
        $newProductId = $this->productDao->add(
            "Test Product " . uniqid(), 
            "Test Description", 
            19.99, 
            $categoryId
        );
        $this->printResult("Add new product", $newProductId > 0);
        
        // Test update()
        $updateSuccess = $this->productDao->update(
            $newProductId, 
            "Updated Product", 
            "Updated Desc", 
            29.99, 
            $categoryId
        );
        $this->printResult("Update product", $updateSuccess);
        
        // Test delete
        $deleteSuccess = $this->productDao->delete($newProductId);
        $this->printResult("Delete product", $deleteSuccess);
        
        // Clean up
        $this->categoryDao->delete($categoryId);
    }

    private function testCategoryDAO() {
        $this->printHeader("CATEGORY DAO TESTS");
        
        // Test get_all()
        $categories = $this->categoryDao->get_all();
        $this->printResult("Get all categories", !empty($categories));
        
        // Test get_by_id()
        $testCategory = $this->categoryDao->get_by_id(1);
        $this->printResult("Get category by ID", !empty($testCategory));
        
        // Test add()
        $categoryName = "TestCategory_" . uniqid();
        $newCategoryId = $this->categoryDao->add($categoryName);
        $this->printResult("Add new category", $newCategoryId > 0);
        
        // Test update()
        $newName = "Updated_" . $categoryName;
        $updateSuccess = $this->categoryDao->update($newCategoryId, $newName);
        $this->printResult("Update category", $updateSuccess);
        
        // Test delete
        $deleteSuccess = $this->categoryDao->delete($newCategoryId);
        $this->printResult("Delete category", $deleteSuccess);
    }

    private function testOrderDAO() {
        $this->printHeader("ORDER DAO TESTS");
        
        // First create a test user
        $userId = $this->userDao->add([
            'username' => 'order_test_user',
            'email' => 'order_test@example.com',
            'password' => 'testpass'
        ]);
        
        // Test get_all()
        $orders = $this->orderDao->get_all();
        $this->printResult("Get all orders", is_array($orders));
        
        // Test get_by_id()
        if (!empty($orders)) {
            $testOrder = $this->orderDao->get_by_id($orders[0]['order_id']);
            $this->printResult("Get order by ID", !empty($testOrder));
        }
        
        // Test add()
        $newOrderId = $this->orderDao->add($userId, "pending", 99.99);
        $this->printResult("Add new order", $newOrderId > 0);
        
        // Test update()
        $updateSuccess = $this->orderDao->update($newOrderId, $userId, "completed", 109.99);
        $this->printResult("Update order", $updateSuccess);
        
        // Test delete
        $deleteSuccess = $this->orderDao->delete($newOrderId);
        $this->printResult("Delete order", $deleteSuccess);
        
        // Clean up
        $this->userDao->delete($userId);
    }

    private function testReviewDAO() {
        $this->printHeader("REVIEW DAO TESTS");
        
        // Create test user and product
        $userId = $this->userDao->add([
            'username' => 'review_test_user',
            'email' => 'review_test@example.com',
            'password' => 'testpass'
        ]);
        
        $categoryId = $this->categoryDao->add("ReviewTestCategory");
        $productId = $this->productDao->add(
            "ReviewTestProduct", 
            "Test Description", 
            19.99, 
            $categoryId
        );
        
        // Test get_all()
        $reviews = $this->reviewDao->get_all();
        $this->printResult("Get all reviews", is_array($reviews));
        
        // Test add()
        $newReviewId = $this->reviewDao->add($userId, $productId, 5, "Great product!");
        $this->printResult("Add new review", $newReviewId > 0);
        
        // Test update()
        $updateSuccess = $this->reviewDao->update($newReviewId, 4, "Actually very good");
        $this->printResult("Update review", $updateSuccess);
        
        // Test delete
        $deleteSuccess = $this->reviewDao->delete($newReviewId);
        $this->printResult("Delete review", $deleteSuccess);
        
        // Clean up
        $this->productDao->delete($productId);
        $this->categoryDao->delete($categoryId);
        $this->userDao->delete($userId);
    }

    private function printHeader($message) {
        echo "\n\033[1;36m" . str_repeat("=", 80) . "\033[0m";
        echo "\n\033[1;36m" . str_pad($message, 80, " ", STR_PAD_BOTH) . "\033[0m";
        echo "\n\033[1;36m" . str_repeat("=", 80) . "\033[0m\n\n";
    }

    private function printResult($testName, $success) {
        $status = $success ? "PASS" : "FAIL";
        $color = $success ? "1;32" : "1;31";
        echo "\033[{$color}m[" . str_pad($status, 5) . "]\033[0m {$testName}\n";
        
        if (!$success) {
            // For debugging, you might want to log more info here
        }
    }

    private function printError($message) {
        echo "\n\033[1;31m[ERROR] " . $message . "\033[0m\n";
    }
}

// Run the tests
try {
    $testRunner = new DAOTestRunner();
    $testRunner->runAllTests();
} catch (Exception $e) {
    echo "\n\033[1;31mCRITICAL ERROR: " . $e->getMessage() . "\033[0m\n";
    exit(1);
}