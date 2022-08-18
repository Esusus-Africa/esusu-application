<?php
include ("../config/session1.php");
$PostType = $_GET['PostType'];

$myaltcall = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$institution_id'");
$myaltrow = mysqli_fetch_array($myaltcall);

if($PostType == "Individual" || $PostType == "Agent")
{
?>          

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                      <div class="col-sm-7">
                          <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                      <div class="col-sm-7">
                          <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                      <div class="col-sm-7">
                          <input name="mname" type="text" class="form-control" placeholder="Middle Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Email<?php echo ($PostType == "Agent") ? "" : "(Optional)"; ?></label>
                      <div class="col-sm-7">
                        <input type="text" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email Address">
                        <div id="myvbemail"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" value="+234" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-3">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
                    </div>

<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P18Y'));
$mymax_date = $max_date->format('Y-m-d');
?>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>


                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number<?php echo ($PostType == "Agent") ? "" : "(Optional)"; ?></label>
                      <div class="col-sm-7">
                          <input name="bvn" type="text" class="form-control" placeholder="BVN Number" <?php echo ($PostType == "Agent") ? "required" : ""; ?>>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label" style="color:blue;">Address</label>
                    <div class="col-sm-7">
                    <input name="addrs" type="text" class="form-control" onFocus="geolocate()" placeholder="Location" required>
                    </div>
                    <label for="" class="col-sm-2 control-label"></label>
                </div>
          
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:blue;">State</label>
                  <div class="col-sm-7">
                  <input name="state" type="text" onFocus="geolocate()" class="form-control" placeholder="State" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                  </div>


                <?php
                if($PostType == "Agent"){
                ?>

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Valid ID</label>
                      <div class="col-sm-7">
                        <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD <b>GOVERNMENT ISSUED ID CARD</b></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                </div>
                
                <?php
                }
                else{
                    //Do nothing
                }
                ?>

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                      <div class="col-sm-7">
                          <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Your Username" required>
                          <div id="myusername"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                </div>

                <?php
                if($PostType == "Individual"){
                ?>

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Lock Account</label>
                      <div class="col-sm-7">
                        <select name="lockAcct" class="form-control" id="lockAcct" required>
                            <option value="" selected='selected'>Select Settings&hellip;</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <div style="color: <?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>NOTE:</b> If Account is Locked, it means customer won't be able to access the account himself as the account will be managed by the agent/account officer only.</div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                <span id='ShowValueFrankLockAcct'></span>
                <span id='ShowValueFrankLockAcct'></span>

                <?php
                }
                else{
                    //Do nothing
                }
                ?>


<?php
}
elseif($PostType == "Corporate"){
?>


<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>Contact Person</b></div>

                   <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">First Name</label>
                      <div class="col-sm-7">
                          <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Last Name</label>
                      <div class="col-sm-7">
                          <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Middle Name</label>
                      <div class="col-sm-7">
                          <input name="mname" type="text" class="form-control" placeholder="Middle Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" value="+234" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Gender</label>
                      <div class="col-sm-3">
                            <select name="gender" class="form-control" required>
                                        <option value="" selected='selected'>Select Gender&hellip;</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                            </select>
                        </div>  
                    </div>

<?php
//MINIMUM DATE
$min_date = new DateTime(date("Y-m-d"));
$min_date->sub(new DateInterval('P60Y'));
$mymin_date = $min_date->format('Y-m-d');

//MAXIMUM DATE
$max_date = new DateTime(date("Y-m-d"));
$max_date->sub(new DateInterval('P15Y'));
$mymax_date = $max_date->format('Y-m-d');
?>
                
                <div class="form-group">
                  <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>


                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">BVN Number</label>
                      <div class="col-sm-7">
                          <input name="bvn" type="text" class="form-control" placeholder="BVN Number" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Username</label>
                      <div class="col-sm-7">
                          <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Your Username" required>
                          <div id="myusername"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                </div>


<div class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>"><b>Business Information</b></div>
                

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Name</label>
                      <div class="col-sm-7">
                        <input name="businessName" type="text" class="form-control" placeholder="Business Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Type</label>
                      <div class="col-sm-7">
                        <select name="businessType" class="form-control" required>
                            <option value="" selected='selected'>Select Business Type&hellip;</option>
                            <option value="Financial Service">Financial Service</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Manufacturing">Manufacturing</option>
                            <option value="Leisure, Travel and Tourism">Leisure, Travel and Tourism</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Events">Events</option>
                            <option value="Education">Education</option>
                            <option value="Health">Health</option>
                            <option value="Agriculture">Agriculture</option>
                            <option value="Charity and NGO">Charity and NGO</option>
                            <option value="Oil and Gas">Oil and Gas</option>
                            <option value="Other">Other</option>
                        </select>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Other Type</label>
                      <div class="col-sm-7">
                        <input name="otherBusinessType" type="text" class="form-control" placeholder="Your Business Type">
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">You're required to fill this if <b>Other</b> is selected in Business Type Field</div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Email</label>
                      <div class="col-sm-7">
                        <input name="businessEmail" type="email" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Business Email Address" required>
                        <div id="myvbemail"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Phone</label>
                      <div class="col-sm-7">
                        <input name="businessPhone" type="text" class="form-control" placeholder="Business Phone Number" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Registration Number</label>
                      <div class="col-sm-7">
                        <input name="rcNumber" type="text" class="form-control" placeholder="Registration Number" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Business Address</label>
                      <div class="col-sm-7">
                        <textarea name="businessAddrs" class="form-control" rows="2" cols="80" placeholder="Business Address" required></textarea>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>;">Upload Document</label>
                      <div class="col-sm-7">
                        <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD <b>CAC CERTIFICATE & GOVERNMENT ISSUED ID CARD</b></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>
                  
      
<?php
}else{

    //DO NOTHING

}
?>




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
         input.value = '';
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


<script>
     $('#lockAcct').change(function(){
         var PostType=$('#lockAcct').val();
         $.ajax({url:"Ajax-lockAcct.php?PostType="+PostType,cache:false,success:function(result){
             $('#ShowValueFrankLockAcct').html(result);
         }});
     });
 </script>