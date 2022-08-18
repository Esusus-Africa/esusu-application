<?php

class Branch extends User {

    //FETCH USER BRANCH BY ID (INT) or BY BRANCHID (STRING)
    public function fetchBranchById($parameter, $parameter1) {

        $query = "SELECT * FROM branches WHERE (branchid = '$parameter' OR id = '$parameter') AND created_by = '$parameter1'";

        return $this->db->fetchAll($query, $parameter);

    }


    //FETCH ALL USER BRANCHES
    public function fetchAllBranch($parameter) {

        $query = "SELECT * FROM branches WHERE created_by = ?";

        return $this->db->fetchAll($query, $parameter);

    }


    /** ENDPOINT URL FOR THIS BRANCH REGISTRATION ARE:
     * 
     * api/branch/    : To register new branch using PC / Mobile Phone with the following required field:
     * 
     * {
	*   "bname" : "Agege Branch - FCMB",
	*   "bcountry" : "Nigeria",
	*   "currency" : "NGN",
	*   "branch_addrs" : "No. 10 Brown lane",
	*   "branch_city" : "Lagos",
	*   "branch_province" : "Agege",
	*   "branch_zipcode" : "100234",
	*   "branch_landline" : "8999999",
	*   "branch_mobile" : "888778788",
	*   "branchid" : "BR12345"
    *  }
     * 
     * */
    public function insertMyBranch($parameter, $registeral) {

        $query = "INSERT INTO branches (bprefix, bname, bopendate, bcountry, currency, branch_addrs, branch_city, branch_province, branch_zipcode, branch_landline, branch_mobile, branchid, bstatus, stamp, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if(isset($parameter->bname) && isset($parameter->bcountry) && isset($parameter->currency) && isset($parameter->branch_addrs) && isset($parameter->branch_city) && isset($parameter->branch_province) && isset($parameter->branch_zipcode) && isset($parameter->branch_landline) && isset($parameter->branch_mobile) && isset($parameter->branchid)) {

            $bprefix = "BR";
            $bname = $parameter->bname;
            $bopendate = date("Y-m-d");
            $bcountry = $parameter->bcountry;
            $currency = $parameter->currency;
            $branch_addrs = $parameter->branch_addrs;
            $branch_city = $parameter->branch_city;
            $branch_province = $parameter->branch_province;
            $branch_zipcode = $parameter->branch_zipcode;
            $branch_landline = $parameter->branch_landline;
            $branch_mobile = $parameter->branch_mobile;
            $branchid = $parameter->branchid;
            $bstatus = "Operational";
            $stamp = "";

            $verifyBranch = $this->db->verifyBranch($branchid,$registeral);

            if($bname == "" || $bcountry == "" || $currency == "" || $branch_addrs == "" || $branch_city == "" || $branch_province == "" || $branch_zipcode == "" || $branch_landline == "" || $branch_mobile == "" || $branchid == ""){

                return -1;

            }elseif($verifyBranch === 1){

                return [

                    "message" => "Duplicte Entry is not Allowed",

                ];

            }else{

                $this->db->insertBranch($query, $bprefix, $bname, $bopendate, $bcountry, $currency, $branch_addrs, $branch_city, $branch_province, $branch_zipcode, $branch_landline, $branch_mobile, $branchid, $bstatus, $stamp, $registeral);

                return [
                    
                    "responseCode"=> "00",

                    "reg_status" => "Success",

                    "message" => "Branch Registered Successfully",

                    "details" => [

                        "branchid"=> $parameter->branchid,

                        "branch_name"  => $parameter->bname,

                        "branch_currency"  => $parameter->bcountry,

                        "branch_address" => $parameter->branch_addrs,

                        "branch_mobile" => $parameter->branch_mobile,

                        "open_date" => date("Y-m-d"),

                        "branch_status" => "Operational",

                    ]

                ];

            }

        }

    }


    //FETCH USER BRANCH TRANSACTION USING BRANCHID (STRING)
    public function fetchBranchTrans($parameter) {

        $query = "SELECT * FROM transaction WHERE sbranchid = ?";

        return $this->db->fetchAll($query, $parameter);

    }
    

}

$branch = new Branch($db);

?>