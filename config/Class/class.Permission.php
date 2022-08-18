<?php

namespace Class\Permission;

require_once '../Interface/interface.Permission.php';

use Interface\Permission\PermissionInterface as PermissionInterface;

class PermissionManager extends App implements PermissionInterface {

    //Function to add role
    public static function addRole($parameter){

        $charcter = $this->db->myreference(5);
        $clientId = $parameter['clientId'];
        $role = $parameter['role'];
        $number = count($role);

        if($number >= 1)
        {
            for($i=0; $i<$number; $i++)
            {
                if(trim($role[$i]) != '')
                {
                    $role_name = $charcter.'_'.$role[$i];
                    $queryRole = "INSERT INTO global_role(companyid, role_name) VALUES(?, ?)";
                    $this->db->insertRole($queryRole, $clientId, $role_name);
                }
            }
            return "Success"; //Role Inserted!
        }
        else{
            return "Failed"; //Enter Role
        }

    }

    //Function to view all staff role
    public static function viewAllRole($parameter){

        $column = array('id', 'Role Name');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        //Filltering Parameter
        $clientId = $parameter['clientId'];
        $filterBy = $parameter['filterBy'];

        $query = " ";

        if($filterBy != "" && $filterBy === "all"){
    
            $query .= "SELECT * FROM global_role WHERE companyid = '$clientId'";
            
        }
        
        if($filterBy != "" && $filterBy != "all"){
            
            $query .= "SELECT * FROM global_role WHERE companyid = '$clientId' AND role_name = '$filterBy'";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM global_role
            WHERE role_name LIKE '%'.$searchValue.'%' 
            ";

        }

        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();
            $id = $row['id'];

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = $row['role_name'];
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM global_role WHERE companyid = '$clientId'";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }

    //Function to delete role
    public static function deleteRole($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $this->deleteWithOneParam('global_role', 'id', $selector[$i]);

            }
            return "Success"; //Role Deleted Successfully!

        }

    }

    //Function to view all module
    public static function viewModule($parameter){

        $clientId = $parameter['clientId'];
        $type = $parameter['type']; //client OR backend

        $query = "SELECT * FROM transaction WHERE module_property WHERE mtype = '$type' ORDER BY mname";
        $output = $this->db->fetchAll($query, $type);
        $countOutput = count($output);

        $data = array();

        foreach($output as $row){

            $sub_array = array();

            $sub_array[] = "<tr>";
            $sub_array[] = "<td><b>".$row['mname']."</b></td>";
            $sub_array[] = "<td>".ucfirst(str_replace('_', ' ', $row['mproperty']))."</td>";
            $sub_array[] = "<td>";
            $sub_array[] = "<input name='pnameeee[]' type='text' value='".$row['mproperty']."' width='150px' disabled>";
            $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='pname[]' type='checkbox' value='".$row['mproperty']."'>";
            $sub_array[] = "</td>";
            $sub_array[] = "</tr>";
            $data[] = $sub_array;

        }

        return $data;

    }

    //Function to add Permission
    public static function addPermission($parameter){

        $clientId = $parameter['clientId'];
        $rname = $parameter['rname'];
        $mname = $parameter['mname'];
        $pname = $parameter['pname'];

        $checkPermUniqueness = $this->fetchWithOneParam('my_permission', 'urole', $rname);

        if($checkPermUniqueness === 0){

            $queryPermTbl = "INSERT INTO my_permission(companyid, urole) VALUES(?, ?)";
            $this->db->insertPermission($queryPermTbl, $clientId, $rname);
            for($i=0; $i < count($pname); $i++)
            {
                $pnamee = $pname[$i];
                $this->updateWithOneParam2('my_permission', $pnamee, '1', 'urole', $rname, 'companyid', $clientId);
            }
            return "Success"; //Permission Set Successfully

        }else{

            for($i=0; $i < count($pname); $i++)
            {
                $pnamee = $pname[$i];
                $this->updateWithOneParam2('my_permission', $pnamee, '1', 'urole', $rname, 'companyid', $clientId);
            }
            return "Success"; //Permission Set Successfully

        }

    }

    //Function to view all institution permission list
    public static function viewAllPermissionList($parameter){

        $column = array('id', 'Actions', 'Role Name');

        //Data table Parameter
        $searchValue = $parameter['searchValue']; //$_POST['search']['value']
        $draw = $parameter['draw']; //$_POST['draw']
        $order = $parameter['order']; //$_POST['order']
        $orderColumn = $parameter['orderColumn']; //$_POST['order']['0']['column']
        $start = $parameter['start']; //$_POST['start']
        $length = $parameter['length']; //$_POST['length']

        //Filltering Parameter
        $clientId = $parameter['clientId'];
        $filterBy = $parameter['filterBy'];

        //Theme/Alternate Color
        $theme_color = $parameter['theme_color'];
        $alternate_color = $parameter['theme_color'];

        $query = " ";

        if($filterBy != "" && $filterBy === "all"){
    
            $query .= "SELECT * FROM my_permission WHERE companyid = '$clientId'";
            
        }
        
        if($filterBy != "" && $filterBy != "all"){
            
            $query .= "SELECT * FROM my_permission WHERE companyid = '$clientId' AND urole = '$filterBy'";
            
        }

        if($searchValue != ''){

            $query .= 'SELECT * FROM my_permission
            WHERE urole LIKE "%'.$searchValue.'%" 
            ';

        }

        if(isset($order)){

            $query .= 'ORDER BY '.$column[$orderColumn].' DESC ';

        }else{

            $query .= 'ORDER BY id DESC ';

        }

        $query1 = '';

        if($length != -1){

            $query1 = 'LIMIT ' . $start . ', ' . $length;

        }

        //Query Builder for the filtering
        $statement = $this->db->pdo->prepare($query);
        $statement->execute();
        $number_filter_row = $statement->rowCount();

        $statement = $this->db->pdo->prepare($query . $query1);
        $statement->execute();
        $result = $statement->fetchAll();

        $data = array();

        foreach($result as $row){

            $sub_array = array();
            $id = $row['id'];
            $companyid = $row['companyid'];

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = "<div class='btn-group'>
                                <div class='btn-group'>
                                <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><p><a rel='tooltip' title='Update' href='edit_cperm.php?id=".$id."&&mid=NDEz' class='btn bg-".(($alternate_color == '') ? 'orange' : $alternate_color)."'><i class='fa fa-edit'> Update</i></a></p></li>
                                </ul>
                                </div>
                            </div>";
            $sub_array[] = $row['urole'];
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM my_permission WHERE companyid = '$clientId'";
        $statement2 = $this->db->pdo->prepare($query2);
        $statement2->execute();

        $output = array(
            'draw' => intval($draw),
            'recordsTotal' => $statement2->rowCount(),
            'recordsFiltered' => $number_filter_row,
            'data' => $data
        );

        return json_encode($output);

    }

    //Function to delete Permission List
    public static function deletePermission($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No record was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $this->deleteWithOneParam('my_permission', 'id', $selector[$i]);

            }
            return "Success"; //Role Deleted Successfully!

        }

    }

    //Function to view permission settings
    public static function viewPermissionSettings($parameter){

        $clientId = $parameter['clientId'];
        $type = $parameter['type']; //client OR backend
        $permid = $parameter['permid'];

        $query = "DESCRIBE my_permission";
        $stmt = $this->db->pdo->prepare($query);
        $stmt->execute();
        $table_fields = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $data = array();

        foreach($table_fields as $pgivenName => $value){

            $sub_array = array();
            $pgivenName = $value;

            $fetch_mproperties = $this->fetchWithTwoParam2('module_property', 'mtype', $type, 'mproperty', $pgivenName, 'mname');
            $mproperty = $fetch_mproperties['mproperty'];

            $fetchpermNum = $this->fetchWithTwoParamAll('my_permission', $pgivenName, '1', 'id', $permid);

            if($pgivenName == "id" || $pgivenName == "companyid" || $pgivenName == "urole" || $fetch_mproperties['mname'] == "")
            {

                //Do Nothing

            }else{
                
                $sub_array[] = "<tr>";
                $sub_array[] = "<td><b>".$fetch_mproperties['mname']."</b></td>";
                $sub_array[] = "<td>".ucfirst(str_replace('_', ' ', $pgivenName))."</td>";
                $sub_array[] = "<td>";
                $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='pname[]' type='checkbox' value='$pgivenName' ".(($fetchpermNum == 1) ? 'checked' : '')." width='50px' height='50px'>";
                $sub_array[] = "<input name='pnameeee[]' type='hidden' value='$pgivenName'></td>";
                $sub_array[] = "</td>";
                $sub_array[] = "</tr>";
                $data[] = $sub_array;

            } 

        }

        return $data;

    }

}

?>