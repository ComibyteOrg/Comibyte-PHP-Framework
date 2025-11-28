<?php
namespace App\Helper;

use App\Database\DatabaseQuery;

class Auth
{
    private $userData = null;
    private $db;

    public function __construct() {
        $this->db = new DatabaseQuery();
    }

    public function check()
    {
        return Helper::check();
    }

    public function id()
    {
        return Helper::auth();
    }

    public function user()
    {
        return $this->getUserData();
    }

    private function getUserData() {
        if ($this->userData !== null) {
            return $this->userData;
        }

        $id = $this->id();
        if (!$id) {
            return null;
        }

        try {
             // Fetch user from 'users' table
             $result = $this->db->select('users', '*', "id = ?", "i", [$id]);
             
             if ($result && $result->num_rows > 0) {
                 $this->userData = $result->fetch_assoc();
                 return $this->userData;
             }
        } catch (\Exception $e) {
             // If table doesn't exist or other error, show message as requested
             die("Error: users table does not exist or database error: " . $e->getMessage());
        }
        
        return null;
    }

    public function __get($name) {
        $data = $this->getUserData();
        if ($data && isset($data[$name])) {
            return $data[$name];
        }
        return null;
    }
}
