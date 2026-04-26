<?php

session_start();
header('Content-Type: application/json');

require_once '../controller/ProductController.php';

$productController = new ProductController();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':

        $data = array(
            'product_name' => $_POST['product_name'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'price' => $_POST['price'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'long_description' => $_POST['long_description'] ?? '',
            'is_published' => $_POST['is_published'] ?? 1,
            'is_available' => $_POST['is_available'] ?? 1
        );
        
        $result = $productController->store($data);
        echo json_encode($result);
        break;
        
    case 'read':
        // Get all products
        $products = $productController->index();
        echo json_encode(array('success' => true, 'data' => $products));
        break;
        
    case 'update':
        // Update a product
        $id = $_POST['product_id'] ?? 0;
        $data = array(
            'product_name' => $_POST['product_name'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'price' => $_POST['price'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'long_description' => $_POST['long_description'] ?? ''
        );
        
        $result = $productController->update($id, $data);
        echo json_encode($result);
        break;
        
    case 'delete':
        // Delete a product
        $id = $_POST['product_id'] ?? 0;
        $result = $productController->destroy($id);
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(array('success' => false, 'message' => 'Invalid action'));
}
?>