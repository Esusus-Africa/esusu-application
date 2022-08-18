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
                        <a href="#" class="navbar-brand"><img src="../img/<?php echo $row ['image'] ;?>" width="150" height="80"></a>
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
            <div class="container" style="background-image: url('file/quiloan.png'); width: 100%; height: 100%;" alt>
                <div class="row">
                    <div class="col col-xs-12">
                        <h2>Our Savings Plan</h2>
                        <ol class="breadcrumb">
                            <li><a href="https://citycore.com.ng">Home</a></li>
                            <li>Plan</li>
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
<h3>SPECIAL TARGET SAVINGS/MONEY MARKET INVESTMENT</h3>
<p>
	This is designed to offer a combination of superior returns on savings and a vehicle for accumulating wealth. It gives the opportunity to earn better interest on savings than the ordinary bank savings account offer and at the same time have access to take a loan in multiples of your savings.
</p>
<p>
	It allows client save towards specific projects over a specified time frame. A specific lump is targeted at the point of entry by the client, and progressive savings are made periodically until the set target is achieved. With City Special Target Savings, you can be well prepared to meet both your expected and unexpected future needs. 
</p>
<h4>INVESTMENT OBJECTIVES</h4>
<p>
	It affords you the opportunity of meeting those small financial emergencies such as payment of subscriptions (TV, Phones, PHCH etc), fuel your car, family emergencies, weekend emergencies, salary delay, transport, payment of emergencies that can’t be deferred and many more. QuiLoan won’t allow those small bills disgrace you.
</p>
<h4>FEATURES & BENEFITS</h4>
<p>
    <li>Superior yield on savings</li>
	<li>Can be used to meet a future need</li>
	<li>Affordable savings plan</li>
    <li>Quarterly interest payment</li>
	<li>Multiples and convenient payment plan</li>
	<li>Quick access to your money</li>
	<li>View account statement</li>
</p>

                            <div class="discussion-faq">
                             
                            </div> <!-- end discussion-faq -->
                            <div class="faq">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="true">Why City Core? </a>
                                        </div>
                                        <div id="collapse-1" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <p>City Core is a personal finance management platform of City Core Cooperative Investment and Credit Society Limited and City Core Limited (Corporate Investment Adviser) that helps you cultivate a consistent culture of saving and wealth creation.</p>
												<p>Success comes from the support around you, at City Core; we provide you the required support needed to create your desired future</p>
												<p>You can choose to save little amounts of money periodically (Daily, Weekly or Monthly) towards a specific savings target OR lock away funds for a defined duration.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-2">How do I start saving on citycore.com.ng? </a>
                                        </div>
                                        <div id="collapse-2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>To start saving on citycore.com.ng:</p>
												<li>Create a free account</li>
												<li>Save your first N500. (Use a MasterCard or Visa from any bank in Nigeria).</li>
												<li>Then choose a convenient savings plan</li>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-3">How much can I save on City Core?</a>
                                        </div>
                                        <div id="collapse-3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You can start your saving plans with as low as ₦500 (Five hundred Naira)</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-4">How frequently can I save?</a>
                                        </div>
                                        <div id="collapse-4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You can set your saving plan(s) to be daily, weekly or monthly.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-5">How many saving plans can I choose?</a>
                                        </div>
                                        <div id="collapse-5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You can choose as many saving plans as you wish.</p>
                                            </div>
                                        </div>
                                    </div>
									
									
								</div>
                            </div> <!-- end faq -->
                        </div> <!-- end service-single-content -->
                    </div> <!-- end col -->
                    <div class="col col-md-4 col-md-pull-8">
                        <div class="service-sidebar">
                            <?php include ("sidebar.php"); ?>
							<hr>
                            <div class="faq">
                                <div class="panel-group" id="accordion">
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-6">Are there Bank Charges when you deduct from my account? </a>
                                        </div>
                                        <div id="collapse-6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>No! It’s completely FREE. There are no bank charges</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-7">Can I monitor my savings and returns?</a>
                                        </div>
                                        <div id="collapse-7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Absolutely. Our dashboard was designed with this objective in mind. You can monitor the progress on your savings and returns.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-8">What rate of return do I earn on my savings?</a>
                                        </div>
                                        <div id="collapse-8" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>We offer you a minimum of 10% per annum on any every saving you make and this return accrues to you on daily basis. If you choose our Fixed Savings Plan, you can earn up to 15% interest per annum. This compares with 4.2% that banks give on your savings account which you can only get if you don’t withdraw more than 4 times in a month.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-9">How much do you charge?</a>
                                        </div>
                                        <div id="collapse-9" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>City Core is completely free: no hidden charges, no SMS fee, no account maintenance fee.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-10">How secure is my information?</a>
                                        </div>
                                        <div id="collapse-10" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>City Core was built with security in mind. Critical customer data is encrypted and securely stored. We do not store your card information as we work with a PCIDSS-compliant payment processor to handle all our customers’ card details.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-11">When can I withdraw my money?</a>
                                        </div>
                                        <div id="collapse-11" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>For unlocked plans, you can withdraw some or all of your savings together with interest anytime without any restriction or penalties. For locked plans and fixed investments, withdrawals are restricted to a period after plan maturity. The longer your money stays on City Core, the higher the interest you will earn </p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-12">How do I withdraw my money?</a>
                                        </div>
                                        <div id="collapse-12" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Simply send mail to info@citycore.com.ng and funds will be transferred to your pre registered account within 24 hours</p>
                                            </div>
                                        </div>
                                    </div>
					
					
                                </div>
                            </div> <!-- end faq -->
							
                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- end container -->
        </section>
        <!-- end service-single-section -->
<?php include("footer.php"); ?>
