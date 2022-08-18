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
            <div class="container" style="background-image: url('file/workplace.png'); width: 100%; height: 100%;" alt>
                <div class="row">
                    <div class="col col-xs-12">
                        <h2>Workplace-Loan Product</h2>
                        <ol class="breadcrumb">
                            <li><a href="https://citycore.com.ng">Home</a></li>
                            <li>Workplace Loan</li>
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
<h3>CITY CORE WORKPLACE LOAN</h3>
<p>
	Is designed to meet the personal financial needs of members who are confirmed employees of selected and pre-approved companies who earn consistent and verifiable income. It is a short term loan with repayment period of between 3 to 6 months
</p>
<p>
	It is available to salary earners in both public and private establishment.
</p>
<h5>FEATURES AND BENEFITS</h5>
<p>
	<li>Dedicated relationship manager</li>
	<li>No collateral requirement</li>
	<li>Access within 48 hours after completion of application and documentations</li>
</p>
<h5>TENOR</h5>
<p>
	180 days maximum/between 1-6 months
</p>
<h5>INTEREST</h5>
<p>
	5% payable monthly
</p>
                            <div class="img-holder">
                                <img src="file/workplace.png" alt>
                            </div>
                            <div class="discussion-faq">
							
                            </div> <!-- end discussion-faq -->
							
						 <h5>WORKPLACE LOAN CHECKLIST</h5>
						 <p>
						 	<li>Duly Completed application form</li>
							<li>Most current 6 months statement of account of the applicant (With primary bank/bank where salary is paid)</li>
							<li>Three(3) months’ pay slip within the last three (3) months or letter of employment/last salary review</li>
							<li>BVN Registration details</li>
							<li>Valid copy of acceptable identification e.g Driver’s license, International passport or National Identity card + Employer Identity card</li>
							<li>Post dated cheques for the period (For current account holders) Or Irrevocable Direct Debit Mandate in the prescribed format, duly executed with City Core Limited for savings accounts</li>
							<li>Letter of recommendation from Human resources/supervisor Or  Letter of Guarantee from a credible colleague or individual</li>
							<li>Utility bill/proof of residence (e.g- PHCN, Water bills)</li>
						 </p>
                            
                        </div> <!-- end service-single-content -->
                    </div> <!-- end col -->
                    <div class="col col-md-4 col-md-pull-8">
                        <div class="service-sidebar">
                             <?php include ("sidebar.php"); ?>
							<hr>
   						 <h5>ELIGIBILITY REQUIREMENTS</h5>
   						 <p>
   						 	<li>Age  above 22 years</li>
   							<li>Domicile salary account with commercial bank</li>
   							<li>Three(3) months’ pay slip within the last three (3) months or letter of employment/last salary review</li>
   							<li>BVN Registration details</li>
   							<li>Minimum of 1 year paid employment</li>
   							<li>Must be a confirmed staff of the organization</li>
   							<li>Maximum amount is 200% of average monthly earnings</li>
   						 </p>
							
                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container -->
        </section>
        <!-- end service-single-section -->
<?php include("footer.php"); ?>
