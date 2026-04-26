<?php
require_once '../src/model/Category.php';

class CategoryController
{
    private $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index()
    {
        $result = $this->category->read();
        $categories = array();

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function show($id)
    {
        $this->category->category_id = $id;
        $this->category->readOne();

        if (!empty($this->category->category_name)) {
            return array(
                'category_id' => $this->category->category_id,
                'category_name' => $this->category->category_name,
                'description' => $this->category->description,
                'is_active' => $this->category->is_active,
                'created_at' => $this->category->created_at
            );
        }

        return null;
    }

    public function store($data)
    {
        if (empty($data['category_name'])) {
            return array('success' => false, 'message' => 'Category name is required');
        }

        $this->category->category_name = $data['category_name'];
        $this->category->description = $data['description'] ?? '';
        $this->category->is_active = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        if ($this->category->create()) {
            return array(
                'success' => true,
                'message' => 'Category created successfully',
                'category_id' => $this->category->category_id
            );
        }

        return array('success' => false, 'message' => 'Failed to create category');
    }

    public function update($id, $data)
    {
        if (empty($data['category_name'])) {
            return array('success' => false, 'message' => 'Category name is required');
        }

        $this->category->category_id = $id;
        $this->category->category_name = $data['category_name'];
        $this->category->description = $data['description'] ?? '';
        $this->category->is_active = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        if ($this->category->update()) {
            return array('success' => true, 'message' => 'Category updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update category');
    }

    public function destroy($id)
    {
        $this->category->category_id = $id;

        if ($this->category->delete()) {
            return array('success' => true, 'message' => 'Category deleted successfully');
        }

        return array('success' => false, 'message' => 'Failed to delete category');
    }

    public function getActiveCategories()
    {
        $result = $this->category->getActiveCategories();
        $categories = array();

        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        return $categories;
    }

    public function toggleStatus($id)
    {
        $category = $this->show($id);
        if (!$category) {
            return array('success' => false, 'message' => 'Category not found');
        }

        $this->category->category_id = $id;
        $this->category->category_name = $category['category_name'];
        $this->category->description = $category['description'];
        $this->category->is_active = $category['is_active'] ? 0 : 1;

        if ($this->category->update()) {
            return array(
                'success' => true,
                'message' => 'Category status updated successfully',
                'is_active' => $this->category->is_active
            );
        }

        return array('success' => false, 'message' => 'Failed to update category status');
    }
}
?>