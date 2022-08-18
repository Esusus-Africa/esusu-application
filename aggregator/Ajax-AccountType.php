<?php
include ("../config/session.php");
$PostType = $_GET['PostType'];

if($PostType == "Agent")
{
?>          
                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">First Name</label>
                      <div class="col-sm-7">
                          <input name="fname" type="text" class="form-control" placeholder="First Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  
                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Last Name</label>
                      <div class="col-sm-7">
                          <input name="lname" type="text" class="form-control" placeholder="Last Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Middle Name</label>
                      <div class="col-sm-7">
                          <input name="mname" type="text" class="form-control" placeholder="Middle Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Email<?php echo ($PostType == "Agent") ? "" : "(Optional)"; ?></label>
                      <div class="col-sm-7">
                        <input type="text" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email Address">
                        <div id="myvbemail"></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>


                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" value="+234" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:blue;">Gender</label>
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
                  <label for="" class="col-sm-3 control-label" style="color:blue;">Date of Birth</label>
                  <div class="col-sm-7">
                  <input name="dob" type="date" class="form-control" placeholder="Date Format: mm/dd/yyyy" id="txtDate" min="<?php echo $mymin_date; ?>" max="<?php echo $mymax_date; ?>" required>
                  </div>
                  <label for="" class="col-sm-2 control-label"></label>
                </div>


                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">BVN Number</label>
                      <div class="col-sm-7">
                          <input name="bvn" type="text" class="form-control" placeholder="BVN Number" required>
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

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Upload Valid ID</label>
                      <div class="col-sm-7">
                        <input name="uploaded_file[]" type="file" class="btn bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>" multiple/>
                        <div style="color:<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">UPLOAD <b>GOVERNMENT ISSUED ID CARD</b></div>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                </div>
                

                <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Username</label>
                      <div class="col-sm-7">
                          <input name="username" type="text" class="form-control" id="vbusername" onkeyup="veryBUsername();" placeholder="Your Username" required>
                          <div id="myusername"></div>
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


