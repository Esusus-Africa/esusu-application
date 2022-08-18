<div class="box">
           <div class="box-body">
            <div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="transfer_fund.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDA0&&tab=tab_7" class="btn bg-orange"><i class="fa fa-reply-all"></i> Retry</a> <i class="fa fa-money"></i> OTP Confirmation Form</h3>
            </div>
             <div class="box-body">
            
             <form class="form-horizontal" method="post" enctype="multipart/form-data">
             <div class="box-body">
<?php
if(isset($_POST['save']))
{
    include("../config/restful_apicalls.php");
    
    $result = array();
    $otp_code =  mysqli_real_escape_string($link, $_POST['otp_code']);
    $today = date("Y-m-d");
        
    $google_details = mysqli_query($link, "SELECT * FROM bulk_pmt_confirmation WHERE otp_code = '$otp_code'");
    if(mysqli_num_rows($google_details) == 0)
    {
        echo "<div class='alert bg-red'>Invalid OPT!!</div>";
    }
    else{
        while($get_details1 = mysqli_fetch_array($google_details))
        {
            $id = $get_details1['id'];
            $refid = $get_details1['refid'];
            $t_title = $get_details1['t_title'];
            $acctno = $get_details1['acctno'];
            $bank = $get_details1['bank'];
            $amount = $get_details1['amount'];
            $narration = $get_details1['reasons'];
            $phone = $get_details1['phone'];
            $date_time = $get_details1['date_time'];
            
            $systemset = mysqli_query($link, "SELECT * FROM systemset");
            $row1 = mysqli_fetch_object($systemset);
            $seckey = $row1->secret_key;
            $transfer_charges = $row1->transfer_charges;
        
                $search_restapi = mysqli_query($link, "SELECT * FROM restful_apisetup WHERE api_name = 'bulktransfer'");
                $fetch_restapi = mysqli_fetch_object($search_restapi);
                $api_url = $fetch_restapi->api_url;

                // Pass the parameter here
                $postdata =  array(
                    "seckey"    =>  $seckey,
                    "title"     =>  $t_title,
                    "bulk_data" =>  [
                        "Bank"          =>  $bank,
                        "Account Number"=>  $acctno,
                        "Amount"        =>  $amount,
                        "Currency"      =>  $ccurrency,
                        "Narration"     =>  $narration,
                        "reference"     =>  $refid
                    ]
                );

                $make_call = callAPI('POST', $api_url, json_encode($postdata));
                $result = json_decode($make_call, true);

                if($result['status'] == "success"){

                    $transfer_id = $result['data']['id'];
                    $transfers_fee = $result['data']['fee'];
                    $status = $result['data']['approver'];

                    echo '<meta http-equiv="refresh" content="6;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&trid='.$transfer_id.'tab=tab_7">';
                    echo '<br>';
                    echo "<div align='center'>Trasnfer Initiated Successful!...</div>";
                }
                else{
                    echo '<meta http-equiv="refresh" content="5;url=transfer_fund.php?id='.$_SESSION['tid'].'&&mid=NDA0&&tab=tab_7">';
                    echo '<br>';
                    echo'<span class="itext" style="color: blue;">'.$result['message'].'</span>';
                }
            }
        }
    }
?>

<hr>
<div class="alert bg-orange">Kindly confirm with the OTP Code send to Your Phone Number to complete this Transfer.</div>
</hr>
            
            <div class="form-group">
                  <label for="" class="col-sm-2 control-label" style="color:blue;">OTP Code</label>
                  <div class="col-sm-10">
                  <input name="otp_code" type="text" class="form-control" placeholder="Enter OPT Code you received on your phone">
                  </div>
                  </div>

             </div>
             
              <div align="right">
              <div class="box-footer">
                                <button name="save" type="submit" class="btn bg-blue"><i class="fa fa-refresh">&nbsp;Confirm</i></button>
              </div>
              </div>
            
             </form> 


</div>  
</div>  
</div>
</div>