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
        All Cardholder
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">cardholder</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/list_newcard_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_indivcardholder_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_indivcardholder_report.php",
    'data': function(data){
        // Read values
        var myptype = $('#filterBy').val();

        // Append to data
        data.filterBy = myptype;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>


<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_agcorpcardholder_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_agcorpcardholder_report.php",
    'data': function(data){
        // Read values
        var myptype = $('#filterBy').val();

        // Append to data
        data.filterBy = myptype;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
  });
  
  $('#filterBy').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>