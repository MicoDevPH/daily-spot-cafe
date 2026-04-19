<?php
require_once '../model/User.php';
require_once '../model/UserDetails.php';

class UserController
{
    private $user;
    private $userDetails;

    public function __construct()
    {
        $this->user = new User();
        $this->userDetails = new UserDetails();
    }

    public function index()
    {
        $result = $this->user->read();
        $users = array();

        while ($row = $result->fetch_assoc()) {
            $users[] = $this->enrichUser($row);
        }

        return $users;
    }

    public function show($id)
    {
        $this->user->user_id = $id;
        $this->user->readOne();

        if (!empty($this->user->username)) {
            return $this->enrichUser(array(
                'user_id' => $this->user->user_id,
                'username' => $this->user->username,
                'role_id' => $this->user->role_id,
                'created_at' => $this->user->created_at
            ));
        }

        return null;
    }

    public function store($data)
    {
        $requiredFields = ['username', 'password', 'role_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return array('success' => false, 'message' => ucfirst($field) . ' is required');
            }
        }

        // Check if username already exists
        if ($this->user->usernameExists($data['username'])) {
            return array('success' => false, 'message' => 'Username already exists');
        }

        $this->user->username = $data['username'];
        $this->user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->user->role_id = $data['role_id'];

        if ($this->user->create()) {
            // Create user details if provided
            if (!empty($data['first_name']) || !empty($data['email'])) {
                $this->userDetails->user_id = $this->user->user_id;
                $this->userDetails->first_name = $data['first_name'] ?? '';
                $this->userDetails->middle_name = $data['middle_name'] ?? '';
                $this->userDetails->last_name = $data['last_name'] ?? '';
                $this->userDetails->suffix = $data['suffix'] ?? '';
                $this->userDetails->phone_number = $data['phone_number'] ?? '';
                $this->userDetails->email = $data['email'] ?? '';
                $this->userDetails->create();
            }

            return array(
                'success' => true,
                'message' => 'User created successfully',
                'user_id' => $this->user->user_id
            );
        }

        return array('success' => false, 'message' => 'Failed to create user');
    }

    public function update($id, $data)
    {
        $this->user->user_id = $id;
        $this->user->readOne();

        if (empty($this->user->username)) {
            return array('success' => false, 'message' => 'User not found');
        }

        // Check username uniqueness if changed
        if (isset($data['username']) && $data['username'] !== $this->user->username) {
            if ($this->user->usernameExists($data['username'])) {
                return array('success' => false, 'message' => 'Username already exists');
            }
        }

        $this->user->username = $data['username'] ?? $this->user->username;
        $this->user->role_id = $data['role_id'] ?? $this->user->role_id;

        // Only hash password if it's being updated
        if (!empty($data['password'])) {
            $this->user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($this->user->update()) {
            // Update user details if provided
            $this->userDetails->user_id = $id;
            $this->userDetails->readOne();

            if (!empty($this->userDetails->first_name) || isset($data['first_name'])) {
                $this->userDetails->first_name = $data['first_name'] ?? $this->userDetails->first_name;
                $this->userDetails->middle_name = $data['middle_name'] ?? $this->userDetails->middle_name;
                $this->userDetails->last_name = $data['last_name'] ?? $this->userDetails->last_name;
                $this->userDetails->suffix = $data['suffix'] ?? $this->userDetails->suffix;
                $this->userDetails->phone_number = $data['phone_number'] ?? $this->userDetails->phone_number;
                $this->userDetails->email = $data['email'] ?? $this->userDetails->email;

                if (!empty($this->userDetails->first_name)) {
                    $this->userDetails->update();
                } else {
                    $this->userDetails->create();
                }
            }

            return array('success' => true, 'message' => 'User updated successfully');
        }

        return array('success' => false, 'message' => 'Failed to update user');
    }

    public function destroy($id)
    {
        // Delete user details first
        $this->userDetails->user_id = $id;
        $this->userDetails->delete();

        // Then delete user
        $this->user->user_id = $id;

        if ($this->user->delete()) {
            return array('success' => true, 'message' => 'User deleted successfully');
        }

        return array('success' => false, 'message' => 'Failed to delete user');
    }

    public function login($username, $password)
    {
        if ($this->user->authenticate($username, $password)) {
            // Get user details
            $userDetails = $this->getUserDetails($this->user->user_id);

            return array(
                'success' => true,
                'message' => 'Login successful',
                'user' => array(
                    'user_id' => $this->user->user_id,
                    'username' => $this->user->username,
                    'role_id' => $this->user->role_id,
                    'created_at' => $this->user->created_at,
                    'details' => $userDetails
                )
            );
        }

        return array('success' => false, 'message' => 'Invalid username or password');
    }

    public function changePassword($id, $currentPassword, $newPassword)
    {
        $this->user->user_id = $id;
        $this->user->readOne();

        if (empty($this->user->username)) {
            return array('success' => false, 'message' => 'User not found');
        }

        // Verify current password
        if (!$this->user->authenticate($this->user->username, $currentPassword)) {
            return array('success' => false, 'message' => 'Current password is incorrect');
        }

        // Update password
        $this->user->password = password_hash($newPassword, PASSWORD_DEFAULT);

        if ($this->user->update()) {
            return array('success' => true, 'message' => 'Password changed successfully');
        }

        return array('success' => false, 'message' => 'Failed to change password');
    }

    public function getUsersByRole($roleId)
    {
        $result = $this->user->getUsersByRole($roleId);
        $users = array();

        while ($row = $result->fetch_assoc()) {
            $users[] = $this->enrichUser($row);
        }

        return $users;
    }

    private function enrichUser($row)
    {
        $userDetails = $this->getUserDetails($row['user_id']);

        return array(
            'user_id' => $row['user_id'],
            'username' => $row['username'],
            'role_id' => $row['role_id'],
            'created_at' => $row['created_at'],
            'details' => $userDetails
        );
    }

    private function getUserDetails($userId)
    {
        $this->userDetails->user_id = $userId;
        $this->userDetails->readOne();

        if (!empty($this->userDetails->first_name)) {
            return array(
                'first_name' => $this->userDetails->first_name,
                'middle_name' => $this->userDetails->middle_name,
                'last_name' => $this->userDetails->last_name,
                'suffix' => $this->userDetails->suffix,
                'phone_number' => $this->userDetails->phone_number,
                'email' => $this->userDetails->email,
                'updated_at' => $this->userDetails->updated_at,
                'full_name' => $this->userDetails->getFullName()
            );
        }

        return null;
    }
}
?>