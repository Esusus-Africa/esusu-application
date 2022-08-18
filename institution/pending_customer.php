<?php include("include/header.php"); ?>
<div class="wrapper">

<?php include("include/top_bar.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
<?php include("include/side_bar.php"); ?>
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        All Pending Customers
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List</li>
      </ol>
    </section>
    <section class="content">
		<?php
      include("include/pending_customer_data.php");
    ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_allpendingcustomer_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_allpendingcustomer.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var mybycust = $('#byCustomer').val();
        var myfilterby = $('#filterBy').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.byCustomer = mybycust;
        data.filterBy = myfilterby;
    }
   },
   dom: 'lBfrtip',
   <?php 
   if($irole == 'agent_manager' || $irole == 'institution_super_admin' || $irole == 'merchant_super_admin'){
   ?>
   buttons: [
     'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   <?php
   }
   else{
     echo "";
   }
   ?>
   "lengthMenu": [ [10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"] ]
  });
  
  $('#startDate').change(function () {
    dataTable.draw();
  });

  $('#endDate').change(function () {
    dataTable.draw();
  });
  
  $('#byCustomer').change(function(){
    dataTable.draw();
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>