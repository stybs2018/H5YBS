<?php
namespace App\Models\Admin;

class Assign {
    
    // 菜单树
    public static function MenuTree($data, $pid = 0)
    {
        $arr = [];
        $children = [];
        foreach ($data as $i) {
            if ($i->_pid === $pid) {
                $children = self::MenuTree($data, $i->id);
                array_push($arr, [
                    'id' => $i->id,
                    'label' => $i->name,
                    'children' => $children
                ]);
            
            }
        }
        return $arr;
    }
    
    // 权限树
    public static function AuthTree($data)
    {
        $arr = [];
        $parent = [];
        $children = [];
        $keys = array_unique(array_column($data, '_module'));
        foreach($keys as $key) {
            $parent = ['id' => -1, 'label' => $key];
            $children = [];
            foreach($data as $i) {
                if ($i->_module === $key) {
                    array_push($children, ['id' => $i->id, 'label' => $i->name]);
                }
            }
            $parent['children'] = $children;
            array_push($arr, $parent);
        }
        return $arr;
    }
    
}