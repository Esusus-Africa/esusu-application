<?php include "../config/session.php"; ?>  

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <?php 
$call = mysqli_query($link, "SELECT * FROM systemset");
if(mysqli_num_rows($call) == 0)
{
echo "<script>alert('Data Not Found!'); </script>";
}
else
{
while($row = mysqli_fetch_assoc($call)){
?>

<link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
<?php }}?>
  <?php 
	$call = mysqli_query($link, "SELECT * FROM systemset");
	while($row = mysqli_fetch_assoc($call)){
	?>
  <title><?php echo $row ['title']?></title>
  <?php }?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <br>
  <?php 
		 $result= mysqli_query($link,"select * from systemset")or die(mysqli_error());
		 while($row=mysqli_fetch_array($result))
		 {
		 ?>
		   <div align="center"><img src="../img/<?php echo $row['image'];?>" width="80" height="80" class="user-image" alt="User Image">
		 <?php }?>
		 </div>
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
		 <?php 
		 $sql = "SELECT * FROM systemset";
		 $result = mysqli_query($link,$sql);
		 while ($row=mysqli_fetch_array($result))
		{
      $cid = $_GET['cid'];
      $search_cid = mysqli_query($link, "SELECT * FROM cooperatives WHERE coopid = '$cid'");
      $fetch_cid = mysqli_fetch_object($search_cid);
?>
            <div style="color:blue;"><div style="font-size:21px"><div align="center"><b><?php echo '&nbsp;&nbsp;&nbsp;'. $row ['name'];?></b></div></div></div>
 		   <div style="color:orange; font-size:19px;" align="center"><b><br><p>(All Cooperatives Members of <?php echo strtoupper($fetch_cid->coopname); ?>)</p></b></div>
           <small class="pull-right"><div style="color:blue;"><?php $today = date ('y:m:d'); 
 		  								  $new = date ('l, F, d, Y', strtotime($today));	
 										      echo $new;?></div>
		</small>
        </h2>
		<?php  
		}
		?>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    
    <!-- /.row -->

    <!-- Table row -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
            <div class="box-header bg-blue">
              <!--<h3 class="box-title">Payment table</h3>-->

			  
            </div>
	            <div class="box-body table-responsive">
<form method="post">

			 <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><input type="checkbox" id="select_all"/></th>
                  <th>Member ID</th>
                  <th>Full Name</th>
				          <th>Phone</th>
				          <th>Email</th>
				          <th>M.Frequency</th>
                  <th>Role</th>
				          <th>Reg. Date</th>
                  <th>Action</th>
                 </tr>
                </thead>
                <tbody>
<?php
$cid = $_GET['cid'];
$select = mysqli_query($link, "SELECT * FROM coop_members WHERE coopid = '$cid'") or die (mysqli_error($link));
if(mysqli_num_rows($select)==0)
{
echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
}
else{
while($row = mysqli_fetch_array($select))
{
$id = $row['id'];
$memberid = $row['memberid'];
?>    
                <tr>
                  <td><input id="optionsCheckbox" class="checkbox"  name="selector[]" type="checkbox" value="<?php echo $id; ?>"></td>
                  <td width="150"><img class="img-circle" src="../<?php echo $row ['member_image'];?>" width="30" height="30" align="center"> <b><?php echo $memberid; ?></b></td>
				          <td><?php echo $row['fullname']; ?></td>
				          <td><?php echo $row['phone']; ?></td>
          				<td><?php echo $row['email']; ?></td>
          				<td><?php echo $row['meeting_freq']; ?></td>
          				<td><?php echo $row['member_role']; ?></td>
          				<td><?php echo $row['reg_date']; ?></td>
                  <td align="center">
                  <div class="btn-group bg-blue">
                      <div class="btn-group">
                        <button type="button" class="btn bg-blue dropdown-toggle" data-toggle="dropdown">
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php echo ($update_coop_members == 1) ? '<li><p><a href="edit_coopmembers?id='.$_SESSION['tid'].'&&idm='.$id.'&&cid='.$_GET['cid'].'&&mid=NDE4&&tab=tab_1" class="btn bg-orange btn-flat"><i class="fa fa-edit">&nbsp;Edit</i></a></p></li>' : '----------'; ?>
                        </ul>
                      </div>
                  </div>
                  </td>	    
			          </tr>
<?php } } ?>
             </tbody>
                </table>  

            <?php
            if(isset($_POST['delete'])){
            $idm = $_GET['id'];
              $id=$_POST['selector'];
              $N = count($id);
            if($id == ''){
            echo "<script>alert('Row Not Selected!!!'); </script>"; 
            echo "<script>window.location='view_coopmembers?id=".$_SESSION['tid']."&&mid=NDE4'; </script>";
              }
              else{
              for($i=0; $i < $N; $i++)
              {
                $search_mem = mysqli_query($link,"SELECT * FROM coop_members WHERE id ='$id[$i]'");
                $fetch_mem = mysqli_fetch_array($search_mem);
                $member_image = "../".$fetch_mem['member_image'];

                unlink($member_image);
                $result = mysqli_query($link,"DELETE FROM coop_members WHERE id ='$id[$i]'");
                echo "<script>alert('Row Delete Successfully!!!'); </script>";
                echo "<script>window.location='view_coopmembers?id=".$_SESSION['tid']."&&mid=NDE4'; </script>";
              }
              }
              }
            ?>

</form>

				</div>
				
              <div class="box-footer">
	 <?php echo ($print_coop_members == 1) ? '<button type="button" onClick="window.print();" class="btn bg-blue pull-right" ><i class="fa fa-print"></i> Print</button>' : ''; ?>
   <?php echo ($delete_coop_members == 1) ? '<button type="button" class="btn bg-orange pull-left" ><i class="fa fa-times"></i> Delete</button>' : ''; ?>

            <!-- /.box-body -->
          </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<!-- ./wrapper -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Bootstrap 3.3.6 -->
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- page script --><script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>
</body>
</html>
