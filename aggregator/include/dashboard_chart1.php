<div class="row">

<div class="col-md-12">
<div class="slideshow-container">
<div class="alert bg-orange" style="font-size:17px;" align="center">
    <p>
        <?php
        //echo $walletafrica_status;
        if($mo_virtualacct_status == "Active" && ($walletafrica_status == "NotActive" || $walletafrica_status == "Active")){
            
            include("../config/monnify_virtualaccount_esusu.php");
            
        }
        elseif($mo_virtualacct_status == "NotActive" && $walletafrica_status == "Active"){
            
            include("../config/walletafrica_restfulapis_call.php");
            include("walletafrica_virtulaccount.php");
            
        }
        ?>
    </p>
</div>

<a class="myprev" onclick="plusSlides(-1)">&#10094;</a>
<a class="mynext" onclick="plusSlides(1)">&#10095;</a>
    
</div>

<!--TRACK TOTAL WALLET COMMISSION ON ALL AGGREGRATED AGENT TRANSACTION -->

   <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php
$systemset = mysqli_query($link, "SELECT * FROM systemset");
$row1 = mysqli_fetch_object($systemset);

  echo "<span id='wallet_balance'>".$aggcurrency.number_format($aggwallet_balance,2,'.',',')."</span>";

?>      </h4>
              <p>Wallet Balance</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
             <a href="mywallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1" class="small-box-footer"> View Transaction <i class="fa fa-arrow-circle-right"></i></a>
          </div>      
        </div>



<!--TRACK TOTAL CLIENT AGGREGRATED-->

        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
$selecte = mysqli_query($link, "SELECT * FROM institution_data WHERE aggr_id = '$aggr_id'") or die (mysqli_error($link));
$nume = mysqli_num_rows($selecte);
echo $nume;
?>
      </h4>
              <p>Total Client Registered </p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
             <a href="listagents?tid=<?php echo $_SESSION['tid']; ?>&&mid=NDQw" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->



<!--TRACK TOTAL AGENT WALLET CREATED-->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
$selecte = mysqli_query($link, "SELECT * FROM user WHERE acctOfficer = '$aggr_id'") or die (mysqli_error($link));
$nume = mysqli_num_rows($selecte);
echo $nume;
?>
      </h4>
              <p>Total Wallet Created </p>
            </div>
            <div class="icon">
              <i class="fa fa-briefcase"></i>
            </div>
            <a href="listWallet.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_1" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        

<!--TRACK OVERALL TERMINAL ASSIGNED-->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
      
      <h4>
<?php 
  $selecte3 = mysqli_query($link, "SELECT * FROM terminal_reg WHERE initiatedBy = '$aggr_id' AND terminal_status = 'Assigned'") or die (mysqli_error($link));
  $nume3 = mysqli_num_rows($selecte3);
  echo $nume3;
?>
      </h4>
              <p>Total Terminal Assigned </p>
            </div>
            <div class="icon">
              <i class="fa fa-mobile"></i>
            </div>
            <a href="terminal_assigned?id=<?php echo $_SESSION['tid']; ?>&&mid=NzAw" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>



<div class="col-lg-12 col-xs-12">
     <div class="box box-info">
    <span style="font-size: 20px;">Your Aggregator ID: <b><?php echo $aggr_id; ?></b></span>
 </div>
</div>
    
   
</div>
    <!--  Event codes starts here-->