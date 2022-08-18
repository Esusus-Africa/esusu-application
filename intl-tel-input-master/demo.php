<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>International Telephone Input</title>
  <link rel="stylesheet" href="build/css/intlTelInput.css">
  <link rel="stylesheet" href="build/css/demo.css">
</head>

<body>
  <h1>International Telephone Input</h1>
  <form method="post" enctype="multipart/form-data">
    <input id="phone" name="phone" type="tel">
    <button name="sub" onclick="myFunction()" type="submit">Submit</button>
    <?php
    if(isset($_POST['sub'])){
      $phone = $_POST['phone'];

      echo $phone;
    }
    ?>
  </form>

  <!--<script src="https://code.jquery.com/jquery-latest.min.js"></script>-->
  <script src="build/js/intlTelInput.js"></script>
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
        separateDialCode: true,
        utilsScript: "build/js/utils.js",
        });

        input.addEventListener("countrychange", function(e) {
          // do something with iti.getSelectedCountryData()
         var countryData = iti.getSelectedCountryData().dialCode + document.getElementById("phone").value;
        });      

        function myFunction() {
          var phonefull = document.getElementById("phone").value;
          input.value = '+' + iti.getSelectedCountryData().dialCode + phonefull;
        }
  </script>
</body>

</html>
