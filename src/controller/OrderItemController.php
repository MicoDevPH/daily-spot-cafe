<?php
require_once '../model/OrderItem.php';
require_once '../model/Product.php';

class OrderItemController
{
    private $orderItem;
    private $product;

    public function __construct()
    {
        $this->orderItem = new OrderItem();
        $this->product = new Product();
    }

    public function index()
    {
        $result = $this->orderItem->read();
        $orderItems = array();

        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $this->enrichOrderItem($row);
        }

        return $orderItems;
    }

    public function show($id)
    {
        $this->orderItem->order_item_id = $id;
        $this->orderItem->readOne();

        if (!empty($this->orderItem->order_id)) {
            return $this->enrichOrderItem(array(
                'order_item_id' => $this->orderItem->order_item_id,
                'order_id' => $this->orderItem->order_id,
                'product_id' => $this->orderItem->product_id,
                'quantity' => $this->orderItem->quantity,
                'unit_price' => $this->orderItem->unit_price
            ));
        }

        return null;
    }

    public function store($data)
    {
        if (empty($data['order_id']) || empty($data['product_id']) || empty($data['quantity'])) {
            return array('success' => false, 'message' => 'Order ID, Product ID, and quantity are required');
        }

        // Validate product exists and is available
        $this->product->product_id = $data['product_id'];
        $this->product->readOne();

        if (empty($this->product->product_name)) {
            return array('success' => false, 'message' => 'Product not found');
        }

        if (!$this->product->is_available) {
            return array('success' => false, 'message' => 'Product is not available');
        }

        $this->orderItem->order_id = $data['order_id'];
        $this->orderItem->product_id = $data['product_id'];
        $this->orderItem->quantity = $data['quantity'];
        $this->orderItem->unit_price = $data['unit_price'] ?? $this->product->price;

        if ($this->orderItem->create()) {
            return array(
                'success' => true,
                'message' => 'Order item added successfully',
                'order_item_id' => $this->orderItem->order_item_id
            );
        }

        return array('success' => false, 'message' => 'Failed to add order item');
    }

    public function update($id, $data)
    {
        $this->orderItem->order_item_id = $id;
        $this->orderItem->readOne();

        if (empty($this->orderItem->order_id)) {
            return array('success' => false, 'message' => 'Order item not found');
        }

        $this->orderItem->order_id = $data['order_id'] ?? $this->orderItem->order_id;
        $this->orderItem->product_id = $data['product_id'] ?? $this->orderItem->product_id;
        $this->orderItem->quantity = $data['quantity'] ?? $this->orderItem->quantity;
        $this->orderItem->unit_price = $data['unit_price'] ?? $this->orderItem->unit_price;

        if ($this->orderItem->update()) {
            return array('success' => true, 'message' => 'Order item updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update order item');
    }

    public function destroy($id)
    {
        $this->orderItem->order_item_id = $id;

        if ($this->orderItem->delete()) {
            return array('success' => true, 'message' => 'Order item deleted successfully');
        }

        return array('success' => false, 'message' => 'Failed to delete order item');
    }

    public function getByOrderId($orderId)
    {
        $result = $this->orderItem->readByOrderId($orderId);
        $orderItems = array();

        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $this->enrichOrderItem($row);
        }

        return $orderItems;
    }

    private function enrichOrderItem($row)
    {
        // Get product details
        $this->product->product_id = $row['product_id'];
        $this->product->readOne();

        return array(
            'order_item_id' => $row['order_item_id'],
            'order_id' => $row['order_id'],
            'product_id' => $row['product_id'],
            'product_name' => $this->product->product_name,
            'quantity' => $row['quantity'],
            'unit_price' => $row['unit_price'],
            'subtotal' => $row['quantity'] * $row['unit_price']
        );
    }
}
?>