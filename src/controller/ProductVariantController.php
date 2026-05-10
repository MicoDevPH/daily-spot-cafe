<?php
require_once __DIR__ . '/../model/ProductVariant.php';

class ProductVariantController
{
    private $variant;

    public function __construct()
    {
        $this->variant = new ProductVariant();
    }

    /**
     * Returns all variants for a given product as an array.
     */
    public function getVariantsForProduct($product_id)
    {
        $result   = $this->variant->readByProduct($product_id);
        $variants = [];

        while ($row = $result->fetch_assoc()) {
            $variants[] = [
                'variant_id'   => $row['variant_id'],
                'product_id'   => $row['product_id'],
                'variant_type' => $row['variant_type'],
                'size_label'   => $row['size_label'],
                'price'        => $row['price'],
                'is_available' => $row['is_available'],
            ];
        }

        return $variants;
    }

    /**
     * Save (replace) all variants for a product.
     *
     * $variants_data: array of rows, each with keys:
     *   variant_type (string|null), size_label (string|null), price (float), is_available (int)
     */
    public function saveVariants($product_id, array $variants_data)
    {
        if (empty($product_id)) {
            return ['success' => false, 'message' => 'Invalid product ID'];
        }

        // Validate prices
        foreach ($variants_data as $v) {
            if (!isset($v['price']) || !is_numeric($v['price']) || $v['price'] < 0) {
                return ['success' => false, 'message' => 'Invalid price in variants'];
            }
        }

        $this->variant->saveVariants($product_id, $variants_data);
        return ['success' => true, 'message' => 'Variants saved successfully'];
    }
}
?>
