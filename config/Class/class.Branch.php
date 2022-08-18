<?php

namespace Class\BranchMgr;

require_once '../Interface/interface.Branch.php';

use Interface\Branch\BranchInterface as BranchInterface;

class BranchManager extends App implements BranchInterface {

    //Function to add branch
    public static function addBranch($parameter){

        $clientId = $parameter['clientId'];
        $bname = $this->db->sanitizeInput($parameter['bname']);
        $bopendate = $this->db->sanitizeInput($parameter['bopendate']);
        $overrideAS = $this->db->sanitizeInput($parameter['overrideAS']);
        $bcountry = $this->db->sanitizeInput($parameter['bcountry']);
        $bcurrency = $this->db->sanitizeInput($parameter['bcurrency']);
        $branch_addrs = $this->db->sanitizeInput($parameter['branch_addrs']);
        $branch_city = $this->db->sanitizeInput($parameter['branch_city']);
        $branch_province = $this->db->sanitizeInput($parameter['branch_province']);
        $branch_zipcode = $this->db->sanitizeInput($parameter['branch_zipcode']);
        $branch_landline = $this->db->sanitizeInput($parameter['branch_landline']);
        $branch_mobile = $this->db->sanitizeInput($parameter['branch_mobile']);
        $clientId = $this->db->sanitizeInput($parameter['clientId']);
        $branchid = 'BR'.time();

        $sysSetting = $this->db->fetchSystemSet();
        $country = ($overrideAS == "0") ? $sysSetting['bcountry'] : $bcountry;
        $currency = ($overrideAS == "0") ? $sysSetting['currency'] : $bcurrency;

        try{

            $queryBranchInsertion = "INSERT INTO branches(bprefix, bname, bopendate, bcountry, currency, branch_addrs, branch_city, branch_province, branch_zipcode, branch_landline, branch_mobile, branchid, bstatus, stamp, created_by) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $this->db->insertBranch($queryBranchInsertion, 'BR', $bname, $bopendate, $country, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, 'Operational', '', $clientId);

            return "Success";

        }catch(PDOException $exception){

            return "Error: " . $exception->getMessage();

        }

    }

    //Function to update branch info
    public static function updateBranchInfo($parameter){

        $clientId = $parameter['clientId'];
        $bid = $this->db->sanitizeInput($parameter['bid']);
        $bname = $this->db->sanitizeInput($parameter['bname']);
        $bopendate = $this->db->sanitizeInput($parameter['bopendate']);
        $bcountry = $this->db->sanitizeInput($parameter['bcountry']);
        $bcurrency = $this->db->sanitizeInput($parameter['bcurrency']);
        $branch_addrs = $this->db->sanitizeInput($parameter['branch_addrs']);
        $branch_city = $this->db->sanitizeInput($parameter['branch_city']);
        $branch_province = $this->db->sanitizeInput($parameter['branch_province']);
        $branch_zipcode = $this->db->sanitizeInput($parameter['branch_zipcode']);
        $branch_landline = $this->db->sanitizeInput($parameter['branch_landline']);
        $branch_mobile = $this->db->sanitizeInput($parameter['branch_mobile']);
        $clientId = $this->db->sanitizeInput($parameter['clientId']);

        try{

            $this->updateManytoMany('branches', ['bname', 'bopendate', 'bcountry', 'currency', 'branch_addrs', 'branch_city', 'branch_province', 'branch_zipcode', 'branch_landline', 'branch_mobile'], [$bname, $bopendate, $bcountry, $bcurrency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile], 'id', $bid);
            
        }catch(PDOException $exception){

            return "Error: " . $exception->getMessage();

        }

    }

    //Function to delete Permission List
    public static function deleteBranch($parameter){

        $clientId = $parameter['clientId'];
        $selector = $parameter['selector'];
        $number = count($selector);

        if($selector == ''){

            return -1; //No branch was selected;

        }else{

            for($i=0; $i<$number; $i++){

                $this->deleteWithOneParam('branches', 'id', $selector[$i]);

            }
            return "Success"; //Branch Deleted Successfully!

        }

    }

    //Function to view all branch
    public static function viewAllBranch($parameter){

        $column = array('id', 'Actions', 'Branch ID', 'Branch Name', 'Phone', 'Transaction Count', 'Currency', 'Created On', 'Status');

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

        //Permission Settings
        $update_branches = $parameter['update_branches'];
        $view_all_transaction = $parameter['view_all_transaction'];

        $query = " ";

        if($filterBy != "" && $filterBy === "all"){
    
            $query .= "SELECT * FROM branches WHERE created_by = '$clientId'";
            
        }
        
        if($filterBy != "" && $filterBy != "all"){
            
            $query .= "SELECT * FROM branches WHERE created_by = '$clientId' AND branchid = '$filterBy'";
            
        }

        if($searchValue != ''){

            $query .= "SELECT * FROM branches
            WHERE branchid LIKE '%'.$searchValue.'%' 
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
            $createdby = $row['created_by'];
            $branchid = $row['branchid'];

            $myQuery = "SELECT * FROM transaction WHERE sbranchid = '$branchid'";
            $selectBranchTrans = $this->db->fetchAll($myQuery, $branchid);
            $countTrans = count($selectBranchTrans);

            $sub_array[] = "<input id='optionsCheckbox' class='checkbox' name='selector[]' type='checkbox' value='".$row['id']."'>";
            $sub_array[] = "<div class='btn-group'>
                                <div class='btn-group'>
                                <button type='button' class='btn bg-blue btn-flat dropdown-toggle' data-toggle='dropdown'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                ".(($update_branches  == '1') ? "<li><p><a href='edit_branches.php?id=".$_SESSION['tid']."&&idm=".$id."&&mid=NDAy' class='btn bg-".(($theme_color == '') ? 'blue' : $theme_color)."' target='_blank'><i class='fa fa-edit'> Update Branch</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                                ".(($view_all_transaction == '1') ? "<li><p><a href='view_transaction?id=".$_SESSION['tid']."&&idm=".$branchid."&&mid=NDAy' class='btn bg-".(($theme_color == '') ? 'blue' : $theme_color)."' target='_blank'><i class='fa fa-search'> View Transaction</i></a></p></li>" : '<span style="color: red;">--Not Authorized--</span>')."
                                </ul>
                                </div>
                            </div>";
            $sub_array[] = $branchid;
            $sub_array[] = $row['bname'];
            $sub_array[] = $row['branch_mobile'];
            $sub_array[] = '<div class="alert bg-orange" align="center"><b>'.$countTrans.'</b></div>';
            $sub_array[] = $row['currency'];
            $sub_array[] = $row['bopendate'];
            $sub_array[] = $row['bstatus'];
            $data[] = $sub_array;

        }

        $query2 = "SELECT * FROM branches WHERE created_by = '$clientId'";
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

}

?>