<?php
require_once '../model/Product.php';
require_once '../model/Category.php';

class ProductController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index()
    {
        $result = $this->product->read();
        $products = array();

        while ($row = $result->fetch_assoc()) {
            $products[] = $this->enrichProduct($row);
        }

        return $products;
    }

    public function show($id)
    {
        $this->product->product_id = $id;
        $this->product->readOne();

        if (!empty($this->product->product_name)) {
            return $this->enrichProduct(array(
                'product_id' => $this->product->product_id,
                'product_name' => $this->product->product_name,
                'price' => $this->product->price,
                'short_description' => $this->product->short_description,
                'long_description' => $this->product->long_description,
                'category_id' => $this->product->category_id,
                'is_published' => $this->product->is_published,
                'is_available' => $this->product->is_available,
                'img_url' => $this->product->img_url,
                'created_at' => $this->product->created_at
            ));
        }

        return null;
    }

    public function store($data)
    {
        $requiredFields = ['product_name', 'price', 'short_description', 'long_description', 'category_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return array('success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required');
            }
        }

        // Validate category exists
        $this->category->category_id = $data['category_id'];
        $this->category->readOne();
        if (empty($this->category->category_name)) {
            return array('success' => false, 'message' => 'Invalid category');
        }

        $this->product->product_name = $data['product_name'];
        $this->product->price = $data['price'];
        $this->product->short_description = $data['short_description'];
        $this->product->long_description = $data['long_description'];
        $this->product->category_id = $data['category_id'];
        $this->product->is_published = isset($data['is_published']) ? (int)$data['is_published'] : 1;
        $this->product->is_available = isset($data['is_available']) ? (int)$data['is_available'] : 1;
        $this->product->img_url = $data['img_url'] ?? '';

        if ($this->product->create()) {
            return array(
                'success' => true,
                'message' => 'Product created successfully',
                'product_id' => $this->product->product_id
            );
        }

        return array('success' => false, 'message' => 'Failed to create product');
    }

    public function update($id, $data)
    {
        $this->product->product_id = $id;
        $this->product->readOne();

        if (empty($this->product->product_name)) {
            return array('success' => false, 'message' => 'Product not found');
        }

        // Validate category if provided
        if (isset($data['category_id'])) {
            $this->category->category_id = $data['category_id'];
            $this->category->readOne();
            if (empty($this->category->category_name)) {
                return array('success' => false, 'message' => 'Invalid category');
            }
        }

        $this->product->product_name = $data['product_name'] ?? $this->product->product_name;
        $this->product->price = $data['price'] ?? $this->product->price;
        $this->product->short_description = $data['short_description'] ?? $this->product->short_description;
        $this->product->long_description = $data['long_description'] ?? $this->product->long_description;
        $this->product->category_id = $data['category_id'] ?? $this->product->category_id;
        $this->product->is_published = isset($data['is_published']) ? (int)$data['is_published'] : $this->product->is_published;
        $this->product->is_available = isset($data['is_available']) ? (int)$data['is_available'] : $this->product->is_available;
        $this->product->img_url = $data['img_url'] ?? $this->product->img_url;

        if ($this->product->update()) {
            return array('success' => true, 'message' => 'Product updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update product');
    }

    public function destroy($id)
    {
        $this->product->product_id = $id;

        if ($this->product->delete()) {
            return array('success' => true, 'message' => 'Product deleted successfully');
        }

        return array('success' => false, 'message' => 'Failed to delete product');
    }

    public function getPublishedProducts()
    {
        $result = $this->product->readPublished();
        $products = array();

        while ($row = $result->fetch_assoc()) {
            $products[] = $this->enrichProduct($row);
        }

        return $products;
    }

    public function getProductsByCategory($categoryId)
    {
        $result = $this->product->readByCategory($categoryId);
        $products = array();

        while ($row = $result->fetch_assoc()) {
            $products[] = $this->enrichProduct($row);
        }

        return $products;
    }

    public function togglePublished($id)
    {
        $product = $this->show($id);
        if (!$product) {
            return array('success' => false, 'message' => 'Product not found');
        }

        $this->product->product_id = $id;
        $this->product->product_name = $product['product_name'];
        $this->product->price = $product['price'];
        $this->product->short_description = $product['short_description'];
        $this->product->long_description = $product['long_description'];
        $this->product->category_id = $product['category_id'];
        $this->product->is_published = $product['is_published'] ? 0 : 1;
        $this->product->is_available = $product['is_available'];
        $this->product->img_url = $product['img_url'];

        if ($this->product->update()) {
            return array(
                'success' => true,
                'message' => 'Product publication status updated successfully',
                'is_published' => $this->product->is_published
            );
        }

        return array('success' => false, 'message' => 'Failed to update product publication status');
    }

    public function toggleAvailability($id)
    {
        $product = $this->show($id);
        if (!$product) {
            return array('success' => false, 'message' => 'Product not found');
        }

        $this->product->product_id = $id;
        $this->product->product_name = $product['product_name'];
        $this->product->price = $product['price'];
        $this->product->short_description = $product['short_description'];
        $this->product->long_description = $product['long_description'];
        $this->product->category_id = $product['category_id'];
        $this->product->is_published = $product['is_published'];
        $this->product->is_available = $product['is_available'] ? 0 : 1;
        $this->product->img_url = $product['img_url'];

        if ($this->product->update()) {
            return array(
                'success' => true,
                'message' => 'Product availability updated successfully',
                'is_available' => $this->product->is_available
            );
        }

        return array('success' => false, 'message' => 'Failed to update product availability');
    }

    public function search($query)
    {
        $result = $this->product->search($query);
        $products = array();

        while ($row = $result->fetch_assoc()) {
            $products[] = $this->enrichProduct($row);
        }

        return $products;
    }

    private function enrichProduct($row)
    {
        // Get category details
        $this->category->category_id = $row['category_id'];
        $this->category->readOne();

        return array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'price' => $row['price'],
            'short_description' => $row['short_description'],
            'long_description' => $row['long_description'],
            'category_id' => $row['category_id'],
            'category_name' => $this->category->category_name,
            'is_published' => $row['is_published'],
            'is_available' => $row['is_available'],
            'img_url' => $row['img_url'],
            'created_at' => $row['created_at']
        );
    }
}
?>