<div class="row">     
        <section class="content">  
          <div class="box box-success">
            <div class="box-body">
              <div class="table-responsive">
             <div class="box-body">
<form>        
<div class="col-md-14">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

             <li <?php echo ($_GET['tab'] == 'tab_1') ? "class='active'" : ''; ?>><a href="list_card.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NTUw&&tab=tab_1">Individual Cardhodler</a></li>
                
             <li <?php echo ($_GET['tab'] == 'tab_2') ? "class='active'" : ''; ?>><a href="list_card.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=NTUw&&tab=tab_2">Agent/Corporate Cardholder</a></li>

            </ul>
             <div class="tab-content">

<?php
if(isset($_GET['tab']) == true)
{
  $tab = $_GET['tab'];
  if($tab == 'tab_1')
  {
 
  ?>
             <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_1') ? 'active' : ''; ?>" id="tab_1">

             <div class="box-body">
			
             <form class="form-horizontal" method="post" enctype="multipart/form-data">

                <div class="box-body">

                <div class="form-group">
                    <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                    <div class="col-sm-4">
                    <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
                    <option value="" selected="selected">Filter By...</option>
                    <?php echo ($create_wallet == "1") ? '<option value="all">All Individual Wallet</option>' : ''; ?>

                    <option disabled>Filter By Individual Wallet User</option>
                    <?php
                    ($individual_wallet != "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY id DESC") or die (mysqli_error($link)) : "";
                    ($individual_wallet === "1" && $branch_wallet != "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND lofficer = '$iuid' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY id DESC") or die (mysqli_error($link)) : "";
                    ($individual_wallet != "1" && $branch_wallet === "1") ? $get = mysqli_query($link, "SELECT * FROM borrowers WHERE branchid = '$institution_id' AND sbranchid = '$isbranchid' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY id DESC") or die (mysqli_error($link)) : "";
                    while($rows = mysqli_fetch_array($get))
                    {
                    ?>
                    <option value="<?php echo $rows['account']; ?>"><?php echo $rows['virtual_acctno'].' - '.$rows['lname'].' '.$rows['fname'].' '.$rows['mname']; ?></option>
                    <?php } ?>   

                </select>
                    </div>
                </div>
            </div>

                <hr>
                <div class="table-responsive">
                <table id="fetch_indivcardholder_data" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all"/></th>
                        <th>S/No.</th>
                        <th>Branch</th>
                        <th>Account Officer</th>
                        <th>Account Number</th>
                        <th>Account Name</th>
                        <th>Bank Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Card ID</th>
                        <th>Wallet Balance</th>
                        <th>Status</th>
                        </tr>
                    </thead>
                </table>
                </div>
                
                </form> 
       
             </div>
             
             </div>
             <!-- /.tab-pane -->             
<?php
  }
  elseif($tab == 'tab_2')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_2') ? 'active' : ''; ?>" id="tab_2">

        <div class="box-body">
           
        <form class="form-horizontal" method="post" enctype="multipart/form-data">

            <div class="box-body">

            <div class="form-group">
                <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Filter By</label>
                <div class="col-sm-4">
                <select name="filter_by" id="filterBy" class="form-control select2" style="width:100%">
                <option value="" selected="selected">Filter By...</option>
                <?php echo ($create_wallet == "1") ? '<option value="all1">All Agent Wallet</option>' : ''; ?>
                <?php echo ($create_wallet == "1") ? '<option value="all2">All Corporate Wallet</option>' : ''; ?>

                <option disabled>Filter By Agent Wallet User</option>
                <?php
                ($individual_wallet != "1" && $branch_wallet != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND reg_type = 'agent' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                ($individual_wallet === "1" && $branch_wallet != "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND acctOfficer = '$iuid' AND reg_type = 'agent' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                ($individual_wallet != "1" && $branch_wallet === "1") ? $get2 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND reg_type = 'agent' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                while($rows2 = mysqli_fetch_array($get2))
                {
                ?>
                <option value="<?php echo $rows2['id']; ?>"><?php echo $rows2['virtual_acctno'].' - '.$rows2['name'].' '.$rows2['fname'].' '.$rows2['mname']; ?></option>
                <?php } ?>
                
                <option disabled>Filter By Corporate Wallet User</option>
                <?php
                ($individual_wallet != "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND reg_type = 'corporate' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                ($individual_wallet === "1" && $branch_wallet != "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND acctOfficer = '$iuid' AND reg_type = 'corporate' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                ($individual_wallet != "1" && $branch_wallet === "1") ? $get3 = mysqli_query($link, "SELECT * FROM user WHERE created_by = '$institution_id' AND branchid = '$isbranchid' AND reg_type = 'corporate' AND virtual_acctno != '' AND card_id != 'NULL' AND card_reg = 'Yes' ORDER BY userid DESC") or die (mysqli_error($link)) : "";
                while($rows3 = mysqli_fetch_array($get3))
                {
                ?>
                <option value="<?php echo $rows3['id']; ?>"><?php echo $rows3['virtual_acctno'].' - '.$rows3['businessName']; ?></option>
                <?php } ?>    				
            </select>
                </div>
            </div>
        </div>

            <hr>
            <div class="table-responsive">
            <table id="fetch_agcorpcardholder_data" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"/></th>
                    <th>Branch</th>
                    <th>Account Officer</th>
                    <th>Account Number</th>
                    <th>Account Name</th>
                    <th>Bank Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Card ID</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    </tr>
                </thead>
            </table>
            </div>
            
            </form> 
       
             </div>
             
             </div>
             <!-- /.tab-pane --> 
             
<?php
  }
  elseif($tab == 'tab_3')
  {
  ?>
            <div class="tab-pane <?php echo ($_GET['tab'] == 'tab_3') ? 'active' : ''; ?>" id="tab_3">

        <div class="box-body">
           
       
       
             </div>
             
             </div>
             <!-- /.tab-pane --> 
             
<?php 
} 
} 
?>
</div>
</div>
</div>  
</form>       

              </div>


  
</div>  
</div>
</div> 
<hr>
    
</div>