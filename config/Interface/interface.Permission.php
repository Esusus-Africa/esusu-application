<?php

namespace Interface\Permission;

interface PermissionInterface {

    /**
     * @param string $parameter
     */

     public static function addRole($parameter);

     public static function viewAllRole($parameter);

     public static function deleteRole($parameter);

     public static function viewModule($parameter);

     public static function addPermission($parameter);

     public static function viewAllPermissionList($parameter);

     public static function deletePermission($parameter);

     public static function viewPermissionSettings($parameter);

}

?>