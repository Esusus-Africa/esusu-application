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
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" value="+234" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:blue;">Email</label>
                      <div class="col-sm-3">
                        <input type="text" name="email" type="text" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Email Address" required>
                        <div id="myvbemail"></div>
                        </div>  
                    </div>


<?php
}
elseif($PostType == "Corporate"){
?>

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Business Name</label>
                      <div class="col-sm-7">
                        <input name="businessName" type="text" class="form-control" placeholder="Business Name" required>
                      </div>
                      <label for="" class="col-sm-2 control-label"></label>
                  </div>

                  <div class="form-group">
                      <label for="" class="col-sm-3 control-label" style="color:blue;">Mobile Number</label>
                      <div class="col-sm-3">
                      <input name="phone" type="tel" class="form-control" id="phone" value="+234" required>
                      <div id="myvphone"></div>
                      </div><?php echo (isMobileDevice()) ? "<br>" : ""; ?>
                      <label for="" class="col-sm-1 control-label" style="color:blue;">Email</label>
                      <div class="col-sm-3">
                        <input name="businessEmail" type="email" id="vbemail" onkeyup="veryBEmail();" class="form-control" placeholder="Business Email Address" required>
                        <div id="myvbemail"></div>
                        </div>  
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