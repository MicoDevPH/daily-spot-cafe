<?php
require_once '../model/Order.php';
require_once '../model/OrderItem.php';
require_once '../model/Product.php';

class OrderController
{
    private $order;
    private $orderItem;
    private $product;

    public function __construct()
    {
        $this->order = new Order();
        $this->orderItem = new OrderItem();
        $this->product = new Product();
    }

    public function index()
    {
        $result = $this->order->read();
        $orders = array();

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    public function show($id)
    {
        $this->order->order_id = $id;
        $this->order->readOne();

        if (!empty($this->order->order_status)) {
            $orderItems = $this->getOrderItems($id);

            return array(
                'order_id' => $this->order->order_id,
                'total_amount' => $this->order->total_amount,
                'order_status' => $this->order->order_status,
                'created_at' => $this->order->created_at,
                'items' => $orderItems
            );
        }

        return null;
    }

    public function store($data)
    {
        if (empty($data['items']) || !is_array($data['items'])) {
            return array('success' => false, 'message' => 'Order must contain at least one item');
        }

        $totalAmount = 0;
        $orderItems = array();

        // Validate and calculate total
        foreach ($data['items'] as $item) {
            if (empty($item['product_id']) || empty($item['quantity'])) {
                return array('success' => false, 'message' => 'Invalid order item data');
            }

            $this->product->product_id = $item['product_id'];
            $this->product->readOne();

            if (empty($this->product->product_name)) {
                return array('success' => false, 'message' => 'Product not found: ' . $item['product_id']);
            }

            if (!$this->product->is_available) {
                return array('success' => false, 'message' => 'Product not available: ' . $this->product->product_name);
            }

            $quantity = (int)$item['quantity'];
            $unitPrice = (float)$this->product->price;
            $totalAmount += $quantity * $unitPrice;

            $orderItems[] = array(
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice
            );
        }

        // Create order
        $this->order->total_amount = $totalAmount;
        $this->order->order_status = $data['order_status'] ?? 'Pending';

        if ($this->order->create()) {
            $orderId = $this->order->order_id;

            // Create order items
            foreach ($orderItems as $item) {
                $this->orderItem->order_id = $orderId;
                $this->orderItem->product_id = $item['product_id'];
                $this->orderItem->quantity = $item['quantity'];
                $this->orderItem->unit_price = $item['unit_price'];

                if (!$this->orderItem->create()) {
                    // If order item creation fails, we should ideally rollback the order
                    // For now, just log the error
                    error_log("Failed to create order item for order $orderId");
                }
            }

            return array(
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $orderId,
                'total_amount' => $totalAmount
            );
        }

        return array('success' => false, 'message' => 'Failed to create order');
    }

    public function update($id, $data)
    {
        $this->order->order_id = $id;
        $this->order->readOne();

        if (empty($this->order->order_status)) {
            return array('success' => false, 'message' => 'Order not found');
        }

        $this->order->total_amount = $data['total_amount'] ?? $this->order->total_amount;
        $this->order->order_status = $data['order_status'] ?? $this->order->order_status;

        if ($this->order->update()) {
            return array('success' => true, 'message' => 'Order updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update order');
    }

    public function updateStatus($id, $status)
    {
        $validStatuses = ['Pending', 'Processing', 'Ready', 'Completed', 'Cancelled'];

        if (!in_array($status, $validStatuses)) {
            return array('success' => false, 'message' => 'Invalid order status');
        }

        $this->order->order_id = $id;
        $this->order->readOne();

        if (empty($this->order->order_status)) {
            return array('success' => false, 'message' => 'Order not found');
        }

        $this->order->order_status = $status;

        if ($this->order->update()) {
            return array('success' => true, 'message' => 'Order status updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update order status');
    }

    public function destroy($id)
    {
        // First delete order items
        $this->deleteOrderItems($id);

        // Then delete the order
        $this->order->order_id = $id;

        if ($this->order->delete()) {
            return array('success' => true, 'message' => 'Order deleted successfully');
        }

        return array('success' => false, 'message' => 'Failed to delete order');
    }

    public function getOrdersByStatus($status)
    {
        $result = $this->order->getOrdersByStatus($status);
        $orders = array();

        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    private function getOrderItems($orderId)
    {
        $result = $this->orderItem->readByOrderId($orderId);
        $items = array();

        while ($row = $result->fetch_assoc()) {
            // Get product details
            $this->product->product_id = $row['product_id'];
            $this->product->readOne();

            $items[] = array(
                'order_item_id' => $row['order_item_id'],
                'product_id' => $row['product_id'],
                'product_name' => $this->product->product_name,
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'],
                'subtotal' => $row['quantity'] * $row['unit_price']
            );
        }

        return $items;
    }

    private function deleteOrderItems($orderId)
    {
        $result = $this->orderItem->readByOrderId($orderId);

        while ($row = $result->fetch_assoc()) {
            $this->orderItem->order_item_id = $row['order_item_id'];
            $this->orderItem->delete();
        }
    }
}
?>