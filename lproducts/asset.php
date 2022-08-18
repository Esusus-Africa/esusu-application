<?php include("header.php"); ?>
    <!-- start page-wrapper -->
    <div class="page-wrapper">

        <!-- start preloader -->
        <div class="preloader">
            <div class="inner">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <!-- end preloader -->

        <!-- Start header -->
        <header id="header" class="site-header header-style-1">
            <div class="topbar">
                <div class="container">
                    <div class="row">
                        <div class="col col-sm-6">
                            <ul class="contact-info">
                                <li><i class="fa fa-phone-square"></i> Phone: +234(0)9060000245</li>
                                <li><i class="fa fa-clock-o"></i> Mon - Fri: 8am - 5pm</li>
                            </ul>
                        </div>
                        <div class="col col-sm-6">
                            <div class="language">
                                <span><i class="fa fa-globe"></i> Lang:</span>
                                <div class="select-box">
                                    <select class="selectpicker" id="language-select">
                                        <option>Eng</option>
                                        <option>Ban</option>
                                        <option>Tur</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end container -->
            </div> <!-- end topbar -->
            <nav class="navigation navbar navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="open-btn">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
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
                        <a href="#" class="navbar-brand"><img src="../img/<?php echo $row ['image'] ;?>" width="150" height="100"></a>
 <?php }}?>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse navbar-right navigation-holder">
                        <button class="close-navbar"><i class="fa fa-close"></i></button>
                        <ul class="nav navbar-nav">
                            <li><a href="https://citycore.com.ng">HOME</a></li>
                            <li><a href="https://citycore.com.ng/about-us">ABOUT US</a></li>
							<li><a href="https://citycore.com.ng/contact-us">CONTACT US</a></li>
							<li><a href="https://citycore.com.ng/app">LOGIN</a></li>
							<li><a href="https://citycore.com.ng/app/signup.php">SIGNUP</a></li>
                        </ul>
                    </div><!-- end of nav-collapse -->
                </div><!-- end of container -->
            </nav>
        </header>
        <!-- end of header -->


        <!-- start page-title -->
        <section class="page-title">
            <div class="container" style="background-image: url('file/city2.jpg'); width: 100%; height: 100%;" alt>
                <div class="row">
                    <div class="col col-xs-12">
                        <h2>Asset Acquisition Product</h2>
                        <ol class="breadcrumb">
                            <li><a href="https://citycore.com.ng">Home</a></li>
                            <li>Asset Acquisition</li>
                        </ol>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container -->
        </section>        
        <!-- end page-title -->


        <!-- start service-single-section -->
        <section class="service-single-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col col-md-8 col-md-push-4">
                        <div class="service-single-content">
<h3>CITY CORE ASSETS ACQUISITION LOAN</h3>
<p>
	This product is targeted at business enterprises and individual that requires machineries and physical assets for the expansion, upgrading or diversification of their business. The objective is to enable entrepreneur acquire tangible business assets that will boost their income generating capacity.
</p>
<h5>FEATURES AND BENEFITS</h5>
<p>
	<li>Available to all categories of customers</li>
	<li>Long term and short term basis</li>
	<li>30% contribution towards asset</li>
	<li>Invoice must be issued in favour of City COre</li>
	<li>Weekly and Monthly loan repayment</li>
	<li>Dedicated relationship manager</li>
	<li>Flexible collateral requirement</li>
	<li>Access within 48 hours after completion of application and documentations</li>
</p>
<h5>TENOR</h5>
<p>
	180 days maximum
</p>
                            <div class="img-holder">
                                <img src="file/city2.jpg" alt>
                            </div>
                            <div class="discussion-faq">
							
                            </div> <!-- end discussion-faq -->
                            
                        </div> <!-- end service-single-content -->
                    </div> <!-- end col -->
                    <div class="col col-md-4 col-md-pull-8">
                        <div class="service-sidebar">
                             <?php include ("sidebar.php"); ?>
							
                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container -->
        </section>
        <!-- end service-single-section -->
<?php include("footer.php"); ?>
