<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LoginForm
{
    private $user;
    private $password;
    
    function __construct($a, $b)
    {
        $this->user = DB::table('admin')
                        ->join('admin_role', 'admin.role', '=', 'admin_role.id')
                        ->select('admin.*', 'admin_role.name as rolename')
                        ->where([
                            'admin.username'  => $a,
                            'admin.status'    => 1,
                            'admin_role.status' => 1
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
    
    // 校验密码
    private function validatePassword($password, $password_hash)
    {
        return password_verify($password, $password_hash);
    }
    
    // 
    public function getUser()
    {
        return $this->user;
    }
    
    //  获取权限数据
    public static function getAuth($role)
    {
        $key = "[role-auth:$role]";
        $cache = Cache::get($key, false);
        $prefix = env('ADMIN_PREFIX', '_admin');
        if (!$cache) {
            $cache = [];
            $data = DB::table('admin_assign')
                ->join('admin_role', 'admin_role.id', '=', 'admin_assign.role')
                ->join('admin_permission', 'admin_permission.id', '=', 'admin_assign.permission')
                ->select('admin_permission.*')
                ->orderBy('admin_permission._sort', 'asc')
                ->get();
            $cache['Action']  = [];
            $cache['Menu'] = [];
            $cache['Page'] = [];
            foreach($data as $i) {
                switch ($i->type) {
                    case 'Menu':
                        array_push($cache['Menu'], [
                            'id'    => $i->id,
                            'pid'   => $i->_pid,
                            'icon'  => $i->_icon,
                            'title' => $i->name,
                            'route' => $i->_route
                        ]);
                        break;
                    case 'Action':
                        array_push($cache['Action'], 'api/admin/'.$i->_route.'@'.$i->_method);
                        break;
                    case 'Page':
                        array_push($cache['Page'], $prefix.'/'.$i->_route);
                        break;
                }
            }
            
            $TreeArr = self::TreeArr($cache['Menu']);
            $cache['Menu'] = self::MenuTree($TreeArr);
        }        
        return $cache;
    }
    
        //  数据树状数组化处理
    private static function TreeArr($data, $pid = 0)
    {
        $tree   = [];
        foreach($data as $key => $menu) {
            if ($menu['pid'] === $pid) {
                unset($data[$key]);
                $menu['child'] = self::TreeArr($data, $menu['id']);
                if(count($menu['child']) === 0) {
                    unset($menu['child']);
                }
                array_push($tree, $menu);
            }
        }
        return $tree;
    }
    
    //  导航转换html
    private static function MenuTree($data)
    {
        $html = '';

        foreach($data as $key => $menu) {
            $html .= "<li class=\"layui-nav-item\">";
            if ($menu['icon'][0] === '/') {
                $html .= "<img class=\"menu-icon\" src=\"$menu[icon]\" />";
            }
            $html .= "<a class=\"menu-a\" href=\"javascript:;\" lay-direction=\"2\">";
            if ($menu['icon'][0] !== '/') {
                $html .= "<i class=\"layui-icon $menu[icon]\"></i>";
            }
            $html .= "<cite>$menu[title]</cite>";
            $html .= "</a>";
            if (array_key_exists('child', $menu)) {
                $html .= self::ChildTree($menu['child']);
            }
            $html .= "</li>";
        }
        
        return $html;
    }
    
    // 
    private static function ChildTree($data)
    {
        $html = '<dl class="layui-nav-child">';
        foreach($data as $key => $menu) {
            if (array_key_exists('child', $menu)) {
                $html .= '<dd>';
                $html .= "<a class=\"menu-a\" href=\"javascript:;\">$menu[title]</a>";
                $html .= self::ChildTree($menu['child']);
                $html .= '</dd>';
            } else {
                $html .= '<dd>';
                if ($menu['route'] === '#') {
                    $html .= "<a class=\"menu-a\" href=\"javascript:;\">$menu[title]</a>";
                } else {
                    $html .= "<a class=\"menu-a\" href=\"javascript:;\" lay-href=\"/".env('ADMIN_PREFIX', '_admin')."/$menu[route]\">$menu[title]</a>";
                }
                $html .= '</dd>';
            }
        }
        $html .= '</dl>';
        return $html;
    }
}