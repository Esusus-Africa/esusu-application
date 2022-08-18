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
        General Loan Report
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Rpt.</li>
      </ol>
    </section>
    <section class="content">
		<?php include("include/loanCollectionSheet_data.php"); ?>
	</section>
</div>	

<?php include("include/footer.php"); ?>

<script type="text/javascript" language="javascript">
 $(document).ready(function(){

  var dataTable = $('#fetch_loanrpt_data').DataTable({
   "processing" : true,
   "serverSide" : true,
   'serverMethod': 'post',
   "ajax" : {
    url:"fetch_loanrpt_report.php",
    'data': function(data){
        // Read values
        var mydfrom = $('#startDate').val();
        var mydto = $('#endDate').val();
        var myptype = $('#filterBy').val();
        var mylproduct = $('#loanProduct').val();

        // Append to data
        data.startDate = mydfrom;
        data.endDate = mydto;
        data.filterBy = myptype;
        data.loanProduct = mylproduct;
    }
   },
   dom: 'lBfrtip',
   buttons: [
    'copy', 'csv', 'excel', 'pdf', 'print'
   ],
   "lengthMenu": [ [10, 25, 50, 100, 200, 300, 500, -1], [10, 25, 50, 100, 200, 300, 500, "All"] ]
  });
  
  $('#startDate').change(function () {
    dataTable.draw();
  });

  $('#endDate').change(function () {
    dataTable.draw();
  });
  
  $('#filterBy').change(function() {
    dataTable.draw();
  });

  $('#loanProduct').change(function() {
    dataTable.draw();
  });
  
 });
 
</script>