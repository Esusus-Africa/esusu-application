<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../vendor/jquery/jquery-1.9.1.min.js"><\/script>')</script>
<script src="../vendor/jquery/main.js"></script>

<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0
    </div>
	<?php $sql = "SELECT footer FROM systemset";
		$result = mysqli_query($link,$sql);
  		if(!$result)
  		{
		echo '
		<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>Warning!</strong> Unable to select! check database con.
		</div>';
		}
		else
		{
while ($row=mysqli_fetch_array($result))
		{
?>
    <strong> <?php echo $row ['footer'];?>   </strong>
	<?php }}?>
  </footer>

  <!-- Control Sidebar -->
  

<script>
    // SUBSCRIBE Youtube Channel
// https://www.youtube.com/channel/UCQM5ye1xR4vJgtga0ryud2Q/

$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
</script>

<!-- jQuery 2.2.3 -->
<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

<script type="text/javascript">
        function readIMG(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

<script type="text/javascript">
        function readGURIMG(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
 

<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery 3 FOR SLIDER -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- bootstrap color picker -->
<script src="../plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="../plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>
<!-- DataTables -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>

 <script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "dom": 'lBfrtip',
      "buttons": [
        'excel', 'csv', 'pdf', 'copy'
       ],
       "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
    });
  });
</script>


<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
  
<script>
function payrollAdd() {
       var txtFirstNumberValue = document.getElementById('basic_pay').value;
       var txtSecondNumberValue = document.getElementById('overtime').value;
	   var txtThirdNumberValue = document.getElementById('paid_leave').value;
	   var txtForthNumberValue = document.getElementById('transport_allowance').value;
	   var txtFifthNumberValue = document.getElementById('medical_allowance').value;
	   var txtSixthNumberValue = document.getElementById('bonus').value;
	   var txtSeventhNumberValue = document.getElementById('other_allowance').value;
	   var txtPensionValue = document.getElementById('pension').value;
	   var txtHealthInsuranceValue = document.getElementById('health_insurance').value;
	   var txtUnpaidLeaveValue = document.getElementById('unpaid_leave').value;
	   var txtTaxDeductionValue = document.getElementById('tax_deduction').value;
	   var txtSalaryLoanValue = document.getElementById('salary_loan').value;
	   
       if (txtFirstNumberValue == "")
           txtFirstNumberValue = 0;
       if (txtSecondNumberValue == "")
           txtSecondNumberValue = 0;
	   if (txtThirdNumberValue == "")
           txtThirdNumberValue = 0;
	   if (txtForthNumberValue == "")
           txtForthNumberValue = 0;
	   if (txtFifthNumberValue == "")
           txtFifthNumberValue = 0;
	   if (txtSixthNumberValue == "")
           txtSixthNumberValue = 0;
	   if (txtSeventhNumberValue == "")
           txtSeventhNumberValue = 0;
	   if (txtPensionValue == "")
           txtPensionValue = 0;
	   if (txtHealthInsuranceValue == "")
           txtHealthInsuranceValue = 0;
	   if (txtUnpaidLeaveValue == "")
           txtUnpaidLeaveValue = 0;
	   if (txtTaxDeductionValue == "")
           txtTaxDeductionValue = 0;
	   if (txtSalaryLoanValue == "")
           txtSalaryLoanValue = 0;

       var result = parseFloat(txtFirstNumberValue) + parseFloat(txtSecondNumberValue) + parseFloat(txtThirdNumberValue) + parseFloat(txtForthNumberValue) + parseFloat(txtFifthNumberValue) + parseFloat(txtSixthNumberValue) + parseFloat(txtSeventhNumberValue);   
	   var deductresult = parseInt(txtPensionValue) + parseInt(txtHealthInsuranceValue) + parseInt(txtUnpaidLeaveValue) + parseInt(txtTaxDeductionValue) + parseInt(txtSalaryLoanValue);;
	   var finalresult = result - deductresult;
	   if (!isNaN(result)) {
           document.getElementById('total_output').value = result;
		   document.getElementById('total_deduct').value = deductresult;
		   document.getElementById('final_output').value = finalresult;
		   document.getElementById('paid_amt').value = finalresult;
       }
   }
</script>

<script>
function settleAdd() {
    var txtpendingAmt = document.getElementById('pbal').value;
    var txtCharges = document.getElementById('mycharges').value;
	   
    if (txtpendingAmt == "")
        txtpendingAmt = 0;
    if (txtCharges == "")
        txtCharges = 0;
    
    var calcharge = parseFloat(txtpendingAmt) * parseFloat(txtCharges);
    var amountToSettled = parseFloat(txtpendingAmt) - calcharge;

	  if (!isNaN(calcharge)) {
      document.getElementById('scharge').value = calcharge;
		  document.getElementById('sbal').value = amountToSettled;
    }
  }
</script>

   
   <!-- Bootstrap slider -->
   <script src="../plugins/bootstrap-slider/bootstrap-slider.js"></script>

 	<script>
     $('#loan_products').change(function(){
         var PostType=$('#loan_products').val();
         $.ajax({url:"Ajax-ShowPostLoanP.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 	</script>
	 
 <script>
     $('#loan_products2').change(function(){
         var PostType=$('#loan_products2').val();
		 var BAcctNo=$('#BAcctNo').val();
         $.ajax({url:"Ajax-ShowPostLoanP2.php?PostType="+PostType+"&&BAcctNo="+BAcctNo,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 	</script>
 	
 	<script>
     $('#reg_type').change(function(){
         var PostType=$('#reg_type').val();
         $.ajax({url:"Ajax-RegType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
    </script>
 	
    <script>
     $('#mygroup').change(function(){
         var PostType=$('#mygroup').val();
         $.ajax({url:"Ajax-Mygroup.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank4').html(result);
         }});
     });
 	</script>

  <script>
     $('#directorate_type').change(function(){
         var PostType=$('#directorate_type').val();
         $.ajax({url:"Ajax-ShowDirectorate.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#cto').change(function(){
         var PostType=$('#cto').val();
         $.ajax({url:"Ajax-ShowPhoneNumbers.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>


 <script>
     $('#settlementType').change(function(){
         var PostType=$('#settlementType').val();
         $.ajax({url:"Ajax-SettlementType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 </script>
 
 <script>
     $('#terminalChannel').change(function(){
         var PostType=$('#terminalChannel').val();
         $.ajax({url:"Ajax-terminalChannel.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank2').html(result);
         }});
     });
 </script>

<script>
     $('#settlementMethod').change(function(){
         var PostType=$('#settlementMethod').val();
         var PostType2=$('#tidoperator').val();
         $.ajax({url:"Ajax-settlementMethod.php?PostType="+PostType+"&&TIDOperator="+PostType2,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 </script>

   <script>
     $('#cmRole').change(function(){
         var PostType=$('#cmRole').val();
         $.ajax({url:"Ajax-cmRole.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#accountType').change(function(){
         var PostType=$('#accountType').val();
         $.ajax({url:"Ajax-AccountType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 </script>

<script>
     $('#terminalSerial').change(function(){
         var PostType=$('#terminalSerial').val();
         $.ajax({url:"Ajax-terminalSerial.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
 </script>

<!--
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyAHpWqBfhPU-owmEigD_YhwyURN9h1j7eo"></script>
-->

<script>
            var autocomplete1;
            var autocomplete2;
            var autocomplete3;
            function initialize() {
              autocomplete1 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete1')),
                  { types: ['geocode'] });
              autocomplete2 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete2')),
                  { types: ['geocode'] });
              autocomplete3 = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */
                  (document.getElementById('autocomplete3')),
                  { types: ['geocode'] });

              google.maps.event.addListener(autocomplete, 'place_changed', function() {
              });
            }
</script>

<script type="text/javascript">
  $("#btnprint").click(function () {
    //Hide all other elements other than printarea.
    $("#printarea").show();
    window.print();
});
</script>

<script type="text/javascript">
$(document).ready(function() {
$("#sendc_email").click(function() {
var receiver = $("#receiver").val();
var subject = $("#subject").val();
var message = $("#message").val();
if (subject == '' || message == '') {
alert("Insertion Failed...Please Enter Message..!!");
} else {
// Returns successful data submission message when the entered information is stored in database.
$.post("send_coop_email.php", {
rcv: receiver,
subj: subject,
msg: message
}, function(data) {
alert(data);
$('#coop_email')[0].reset(); // To reset form fields
});
}
});
});
</script>

<script>
$(document).ready(function(){
setInterval(function(){
$("#sendnotification").load('../cron/send_general_sms.php')
$("#sendnotification").load('../cron/send_regemail.php')
}, 2000);
});
</script>

<script>
  $(document).ready(function() {
    var i = 1;
    $('#add').click(function() {
      i++;
      $('#dynamic_field').append('<tr id="row'+i+'"><td><input name="name[]" type="text" class="form-control" placeholder="Enter Module Name"></td><td><button name="remove" id="row'+i+'" type="button" class="btn bg-orange btn_remove"><i class="fa fa-trash"></button></td></tr>');
    });
   $(document).on('click', '.btn_remove', function() {
      $(this).closest('tr').remove();
    });
    $('#submit').click(function() {
      $.ajax({
        url:"save_module.php",
        method:"POST",
        data:$('#add_name').serialize(),
        success:function(data)
        {
          alert(data);
          $('#add_name')[0].reset();
        }
      });
    });
  });
</script>


<script>
  $(document).ready(function() {
    var i = 1;
    $('#add_mycat').click(function() {
      i++;
      $('#my_campaign_cat').append('<tr id="row'+i+'"><td><input name="mycat_name[]" type="text" class="form-control" placeholder="Enter Module Name"></td><td><button name="remove" id="row'+i+'" type="button" class="btn bg-orange btn_remove"><i class="fa fa-trash"></button></td></tr>');
    });
   $(document).on('click', '.btn_remove', function() {
      $(this).closest('tr').remove();
    });
    $('#mysubmit').click(function() {
      $.ajax({
        url:"save_campaign_cat.php",
        method:"POST",
        data:$('#add_cat').serialize(),
        success:function(data)
        {
          alert(data);
          $('#add_cat')[0].reset();
        }
      });
    });
  });
</script>


<script>
  $(document).ready(function() {
    var i = 1;
    $('#myteam_submit').click(function() {
      i++;
      $('#my_team_cat').append('<tr id="row'+i+'"><td><input name="myteam_name[]" type="text" class="form-control" placeholder="Enter Team Name"></td><td><button name="remove" id="row'+i+'" type="button" class="btn bg-orange btn_remove"><i class="fa fa-trash"></button></td></tr>');
    });
   $(document).on('click', '.btn_remove', function() {
      $(this).closest('tr').remove();
    });
    $('#mytsubmit').click(function() {
      $.ajax({
        url:"save_team_cat.php",
        method:"POST",
        data:$('#add_teamcat').serialize(),
        success:function(data)
        {
          alert(data);
          $('#add_teamcat')[0].reset();
        }
      });
    });
  });
</script>





<script>
  $(document).ready(function() {
    $(document).on('click', '.add2', function() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="text" name="item_type[]" class="form-control item_type" value="client" readonly/></td>';
    html += '<td><input type="text" name="item_name[]" class="form-control item_name"/></td>';
    html += '<td><select name="mobule_name[]" class="form-control module_name"><option value="">Select Module</option><?php echo fill_branches_select_box($connect); ?></select></td>';
    html += '<td><button type="button" name="remove2" class="btn bg-blue remove2"><i class="fa fa-trash"></i></button></td></tr>';
    $('#item_table').append(html);
  });
    $(document).on('click', '.remove2', function() {
      $(this).closest('tr').remove();
    });
    $('#insert_form').on('submit', function(event) {
      event.preventDefault();
      var error = '';
      $('.item_type').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Enter User Type at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      $('.item_name').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Enter Property Name at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      $('.mobule_name').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Select Module Name at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      var form_data = $(this).serialize();
      if(error == '')
      {
        $.ajax({
          url:"save_module2.php",
          method:"POST",
          data:form_data,
          success:function(data)
          {
            if(data == 'ok')
            {
              $('#item_table').find("tr:gt(0)").remove();
              $('#error').html('<div class="alert bg-blue">Module Property Added Successfully</div>');
            }
          }
        })
      }
      else
      {
        $('#error').html('<div class="alert bg-orange">'+error+'</div>');
      }
    });
  })
</script>




<script>
  $(document).ready(function() {
    $(document).on('click', '.add3', function() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="text" name="item_type1[]" class="form-control item_type1" value="backend" readonly/></td>';
    html += '<td><input type="text" name="item_name1[]" class="form-control item_name1"/></td>';
    html += '<td><select name="mobule_name1[]" class="form-control module_name"><option value="">Select Module</option><?php echo fill_branches_select_box($connect); ?></select></td>';
    html += '<td><button type="button" name="remove2" class="btn bg-blue remove2"><i class="fa fa-trash"></i></button></td></tr>';
    $('#item_table1').append(html);
  });
    $(document).on('click', '.remove2', function() {
      $(this).closest('tr').remove();
    });
    $('#insert_form1').on('submit', function(event) {
      event.preventDefault();
      var error = '';
      $('.item_type1').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Enter User Type at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      $('.item_name1').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Enter Property Name at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      $('.mobule_name1').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Select Module Name at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      var form_data = $(this).serialize();
      if(error == '')
      {
        $.ajax({
          url:"save_module3.php",
          method:"POST",
          data:form_data,
          success:function(data)
          {
            if(data == 'ok')
            {
              $('#item_table1').find("tr:gt(0)").remove();
              $('#error1').html('<div class="alert bg-blue">Module Property Added Successfully</div>');
            }
          }
        })
      }
      else
      {
        $('#error1').html('<div class="alert bg-orange">'+error+'</div>');
      }
    });
  })
</script>


<script>
  $(document).ready(function() {
    $(document).on('click', '.add4', function() {
    var html = '';
    html += '<tr>';
    html += '<td><input type="text" name="item_type2[]" class="form-control item_type2" value="client" readonly/></td>';
    html += '<td><select name="mobule_name2[]" class="form-control module_name2"><option value="">Select Module</option><?php echo fill_appmodule_select_box($connect); ?></select></td>';
    html += '<td><button type="button" name="remove2" class="btn bg-blue remove2"><i class="fa fa-trash"></i></button></td></tr>';
    $('#item_table2').append(html);
  });
    $(document).on('click', '.remove2', function() {
      $(this).closest('tr').remove();
    });
    $('#insert_form2').on('submit', function(event) {
      event.preventDefault();
      var error = '';
      $('.item_type2').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Enter User Type at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      $('.mobule_name2').each(function(){
        var count = 1;
        if($(this).val == '')
        {
          error += "<p>Select Module Name at "+count+" Row</p>";
          return false;
        }
        count = count + 1;
      });
      var form_data = $(this).serialize();
      if(error == '')
      {
        $.ajax({
          url:"save_module4.php",
          method:"POST",
          data:form_data,
          success:function(data)
          {
            if(data == 'ok')
            {
              $('#item_table2').find("tr:gt(0)").remove();
              $('#error2').html('<div class="alert bg-blue">Price Configured Successfully</div>');
            }
          }
        })
      }
      else
      {
        $('#error2').html('<div class="alert bg-orange">'+error+'</div>');
      }
    });
  })
</script>


<script>
  $(document).ready(function() {
    var i = 1;
    $('#addrole').click(function() {
      i++;
      $('#dynamic_field2').append('<tr id="row2'+i+'"><td><input name="role[]" type="text" class="form-control" placeholder="Enter Role Name"></td><td><button name="remove" id="row2'+i+'" type="button" class="btn bg-orange btn_remove2"><i class="fa fa-trash"></button></td></tr>');
    });
   $(document).on('click', '.btn_remove2', function() {
      $(this).closest('tr').remove();
    });
    $('#submit2').click(function() {
      $.ajax({
        url:"save_role.php",
        method:"POST",
        data:$('#add_role').serialize(),
        success:function(data)
        {
          alert(data);
          $('#dynamic_field2').find("tr:gt(0)").remove();
          $('#add_role')[0].reset();
        }
      });
    });
  });
</script>

<script>
     $('#list_module').change(function(){
         var PostType=$('#list_module').val();
         $.ajax({url:"Ajax-ShowModules.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

<script>
     $('#list_module1').change(function(){
         var PostType=$('#list_module1').val();
         $.ajax({url:"Ajax-ShowModules1.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

<script language="JavaScript">
  function selectAll(source) {
    checkboxes = document.getElementsByName('pname[]');
    for(var i in checkboxes)
      checkboxes[i].checked = source.checked;
  }
</script>

<script>
     $('#company_sector').change(function(){
         var PostType=$('#company_sector').val();
         $.ajax({url:"Ajax-ShowCSector.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
</script>

<script>
     $('#plan_category').change(function(){
         var PostType=$('#plan_category').val();
         $.ajax({url:"Ajax-ShowPCategory.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
</script>

<script>
     $('#dividend_type').change(function(){
         var PostType=$('#dividend_type').val();
         $.ajax({url:"Ajax-ShowDType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank1').html(result);
         }});
     });
</script>

<script>
     $('#savings_plan').change(function(){
         var PostType=$('#savings_plan').val();
         $.ajax({url:"Ajax-ShowSavingsPlan.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

<script>
     $('#pcat').change(function(){
         var PostType=$('#pcat').val();
         $.ajax({url:"Ajax-ShowPCat.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#saas_category').change(function(){
         var PostType=$('#saas_category').val();
         var PostType2=$('#saas_subplan2').val();
         $.ajax({url:"Ajax-ShowSubPlan.php?PostType="+PostType+"&&InstType="+PostType2,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#saas_subplan1').change(function(){
         var PostType=$('#saas_subplan1').val();
         $.ajax({url:"Ajax-ShowSubPlan1.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#transfer_to').change(function(){
         var PostType=$('#transfer_to').val();
         $.ajax({url:"Ajax-ShowTransferTo.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#coupontype').change(function(){
         var PostType=$('#coupontype').val();
         $.ajax({url:"Ajax-ShowCouponType.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#bill_type').change(function(){
         var PostType=$('#bill_type').val();
         $.ajax({url:"Ajax-ShowBPProductList.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>

  <script>
     $('#bill_type1').change(function(){
         var PostType=$('#bill_type1').val();
         $.ajax({url:"Ajax-ShowBPProductList1.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>
  
  <script>
     $('#my_cto').change(function(){
         var PostType=$('#my_cto').val();
         $.ajax({url:"Ajax-ShowCto.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
  </script>
  
  <script>
     $('#card_issuer').change(function(){
         var PostType=$('#card_issuer').val();
         $.ajax({url:"Ajax-CardIssuer.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrank').html(result);
         }});
     });
    </script>
    
<script>
$(document).ready(function(){
setInterval(function(){
$("#vervewallet_balance").load('verve_balance_sync.php')
}, 2000);
});
</script>
  
<script>
$('#field').keyup(function () {
  var max = 153;
  var len = $(this).val().length;
  var result = Math.ceil(len / max);
  if(len >= max){
      $('#charNum').text(len + ' characters - ' + result + ' page(s)');
  }else{
      var char = max - len;
      $('#charNum').text(char + ' characters left - ' + result + ' page');
  }
  }
);
</script>

  <script src="../plugins/pace.js"></script>

 <script type="text/javascript">
function myFunction() {
  var checkBox = document.getElementById("optionsCheckbox");
  var id = $("#id").val();
  var c_name = document.getElementById("c_name[]");
  var pname = document.getElementById("pname[]");
  var text = document.getElementById("text");
  if (checkBox.checked == true){
    $.post("setvalue.php", {
    r_id: id,
    pn: pname,
    cname: c_name
  },function(data) {
    //alert(data);
    text.style.display = "block";
    //$('#coop_email')[0].reset(); // To reset form fields
  });
  }else{
    text.style.display = "none";
  }
}
</script>

<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>


<script src="../intl-tel-input-master/build/js/intlTelInput.js"></script>
  <script>
        var countryData = window.intlTelInputGlobals.getCountryData(),
        input = document.querySelector("#phone");       
        var iti = window.intlTelInput(input, {
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
              var countryCode = (resp && resp.country) ? resp.country : "";
              callback(countryCode);
            });
          },

         //hiddenInput: "full",
        initialCountry: "ng",
          // localizedCountries: { 'de': 'Deutschland' },
         //nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
        //separateDialCode: true,
        utilsScript: "../intl-tel-input-master/build/js/utils.js",
        });

        input.addEventListener("countrychange", function(e) {
          // do something with iti.getSelectedCountryData()
         //var countryData = iti.getSelectedCountryData().dialCode + document.getElementById("phone").value;
         input.value = '+' + iti.getSelectedCountryData().dialCode + document.getElementById("phone").value;
        });     

        function myFunction() {
          phonefull = document.getElementById("phone").value;
          if(phonefull == ""){
            input.value = '+' + iti.getSelectedCountryData().dialCode;
          }else{
            input.value = document.getElementById("phone").value;
          }
        }
        
        document.addEventListener("DOMContentLoaded", function() {
          myFunction();
        });
        
  </script>


<!-- FastClick -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<!-- FastClick -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<!-- FastClick -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<script src="../bower_components/fastclick/lib/fastclick.js"></script>


<!-- live chat 3 widget -->
<script type="text/javascript">
	(function(w, d, s, u) {
		w.id = 1; w.lang = ''; w.cName = ''; w.cEmail = ''; w.cMessage = ''; w.lcjUrl = u;
		var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
		j.async = true; j.src = 'https://esusu.africa/cs/js/jaklcpchat.js';
		h.parentNode.insertBefore(j, h);
	})(window, document, 'script', 'https://esusu.africa/cs/');
</script>
<div id="jaklcp-chat-container"></div>
<!-- end live chat 3 widget -->

</body>
</html>