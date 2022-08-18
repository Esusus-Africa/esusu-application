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
        Terminal Report
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>&&mid=NDAx"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">report</li>
      </ol>
    </section>
	
    <section class="content">
		<?php 
      include("include/terminal_report_data.php");
    ?>
	</section>
</div>

<?php include("include/footer.php"); ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#terminal_report_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_terminal_report.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#transType').val();
        var myrole = $('#iRole').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.transType = myptype;
        data.iRole = myrole;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').change(function() {
    dataTable.draw();
  });

  $('#endDate').change(function() {
    dataTable.draw();
  });
  
  $('#transType').change(function(){
    dataTable.draw();
  });
  
  $('#iRole').change(function(){
    dataTable.draw();
  });
  
 });
 
</script>
