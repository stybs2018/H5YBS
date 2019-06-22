<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;

class LoginForm
{
    private $user;
    private $password;
    
    function __construct($a, $b)
    {
        $this->user = DB::table('admin')->where([
                'username'  => $a,
                'status'    => 1
            ])->first();
        $this->password = $b;
    }
    
    // 校验
    public function validate()
    {
        if (!$this->user) {
            return false;
        }
        
        if ($this->user->password === NULL) {
            return true;
        }
        
        return $this->validatePassword($this->password, $this->user->password);
    }
    
    private function validatePassword($password, $password_hash)
    {
        return password_verify($password, $password_hash);
    }
    
    public function getUser()
    {
        return $this->user;
    }
}