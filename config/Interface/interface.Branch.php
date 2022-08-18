<?php

namespace Interface\Branch;

interface BranchInterface {

    /**
     * @param string $bname
     * @param string $bopendate
     * @param string $overrideAS
     * @param string $bcountry
     * @param string $bcurrency
     * @param string $branch_addrs
     * @param string $branch_city
     * @param string $branch_province
     * @param string $branch_zipcode
     * @param string $branch_landline
     * @param string $branch_mobile
     * @param string $clientId
     */

     public static function addBranch($parameter);

     public static function updateBranchInfo($parameter);

     public static function viewAllBranch($parameter);

}

?>