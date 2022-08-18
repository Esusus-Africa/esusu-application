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
                        <h2>QUI-Loan Product</h2>
                        <ol class="breadcrumb">
                            <li><a href="https://citycore.com.ng">Home</a></li>
                            <li>Quiloan</li>
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
<h3>What is QuiLoan? (No more borrowing from friends, colleagues and family)</h3>
<p>
	QuiLoan allows pre-registered members/borrowers access small cash loans between #1,000 and #10,000 to meet pressing/urgent or embarrassing needs. QuiLoan enable you meet pressing needs to avoid the embarrassment that comes with your inability to meet obligations as at when necessary 
By simply applying on www.citycore.com.ng, you can now obtain a cash loan delivered within 24 hours to your bank account with no collateral required. 
</p>
<h3>Benefit of accessing Borrow Me Cash?</h3>
<p>
	It affords you the opportunity of meeting those small financial emergencies such as payment of subscriptions (TV, Phones, PHCH etc), fuel your car, family emergencies, weekend emergencies, salary delay, transport, payment of emergencies that can’t be deferred and many more. QuiLoan won’t allow those small bills disgrace you.
</p>
                            <div class="img-holder">
                                <img src="file/quiloan.png" alt>
                            </div>
                            <div class="discussion-faq">
                             
                            </div> <!-- end discussion-faq -->
                            <div class="faq">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse-1" aria-expanded="true">How Do I apply for QuiLoan? </a>
                                        </div>
                                        <div id="collapse-1" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <li>Fill out QuiLoan application on https://citycore.com.ng</li>
												<li>Get a loan approval response within 24 hours</li>
												<li>Get funds in your account</li>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-2">How to access QuiLoan?</a>
                                        </div>
                                        <div id="collapse-2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Apply for QuiLoan on https://citycore.com.ng</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-3">How much Money can I borrow?</a>
                                        </div>
                                        <div id="collapse-3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Loan offers on QuiLoan range from <b>#1,000 to #10,000</b> to help you settle those small embarrassing bills. 
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-4">What is the maximum loan available?</a>
                                        </div>
                                        <div id="collapse-4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>The initial maximum loan available to QuiLoan customers is <b>#10,000</b> 
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-5">What is the maximum QuiLoan payback Duration?</a>
                                        </div>
                                        <div id="collapse-5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p><b>QuiLoan</b> payment duration is between 15 and 30 days. The maximum payback duration is 30 days 
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-6">The loan amount is too small?</a>
                                        </div>
                                        <div id="collapse-6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>The loan offer is what we can provide to you at the time of application. As you build a credit history with us, this will vary and increase in the future.
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-7">How to qualify for higher QuiLoan?</a>
                                        </div>
                                        <div id="collapse-7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Making your repayments on time enables you to quickly build a strong QuiLoan credit history. This may allow you to borrow larger sums at cheaper costs in future. Also cultivating a saving culture by signing up for one or more saving/investment plan enables you access higher loans at cheaper cost.
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-8">How long does it take to get QuiLoan?</a>
                                        </div>
                                        <div id="collapse-8" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p><b>QuiLoan</b> is accessed within 24hours after your QuiLoan loan has been approved. Your first loan can however be delayed to afford us ample time to access your credit history, verify your contact information and conduct a background check with your consent. The more you pay your QuiLoan loans early, the faster it becomes to access higher and subsequent loans.
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-9">How do I qualify for QuiLoan?</a>
                                        </div>
                                        <div id="collapse-9" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>In order to qualify for QuiLoan loan, you must:</p>
												<li>be at least 22 years of age</li>
												<li>have a verifiable and regular  source of income</li>
												<li>you must reside in our area of coverage</li>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-10">Do I need to visit the office to receive a loan?</a>
                                        </div>
                                        <div id="collapse-10" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>No, as long as you have filled QuiLoan online application and you fall into QuiLoan approved customers, you are not required to visit the office. Once approved, funds are disbursed into your bank account</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-11">Do I need to provide collateral to receive a QuiLoan?</a>
                                        </div>
                                        <div id="collapse-11" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You do not need any collateral to receive a <b>QuiLoan</b> loan.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-12">How much interest do I pay back?</a>
                                        </div>
                                        <div id="collapse-12" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Interest on your first two <b>QuiLoan</b> loans is charged daily at 1%. The interest payable is pay as you go, meaning we charge only for days you use the loan.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-13">How to repay your QuiLoan?</a>
                                        </div>
                                        <div id="collapse-13" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You can repay your loans by;</p>
												<li>Issuing a post date cheque</li>
												<li>Filling a direct debit mandate/set-off</li>
												<li>You can also pay your QuiLoan via bank deposit, transfers, etc</li>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-14">What happens if I don’t pay back?</a>
                                        </div>
                                        <div id="collapse-14" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You are advised to take a <b>QuiLoan</b> loan only if you are reasonably certain you have the means to repay within the agreed period. If you fail to settle your <b>QuiLoan</b> loan as at when due, it can attract serious consequences such as:</p>
												<li>Default charges.</li>
												<li>Forcing us to employ legal means to recover our money</li>
												<li>Involving external collection agencies</li>
												<li>Causing you embarrassment at your workplace/residence</li>
												<li>Blacklisting you by reporting your details with credit bureau which will impair your ability to get credit in the future</li>
												<li>Any other means we can employ to recover our money</li>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-15">Can I payback my loan before the due date?</a>
                                        </div>
                                        <div id="collapse-15" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>You can repay all or part of your loan before the due date and at anytime. </p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-16">Can I access Quiloan from any location?</a>
                                        </div>
                                        <div id="collapse-16" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p><b>Quiloan</b> is currently available only in Port Harcourt Nigeria</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-17">What checks do you conduct before loans is approved?</a>
                                        </div>
                                        <div id="collapse-17" class="panel-collapse collapse">
                                            <div class="panel-body">
												<li>We verify workplace/business address</li>
												<li>Residential address</li>
												<li>We conduct financial checks to determine capability</li>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-18">Why was my loan application rejected?</a>
                                        </div>
                                        <div id="collapse-18" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p><b>QuiLoan</b> loan application can be rejected due to a number of reasons such as:</p>
												<li>Inadequate information to make a lending decision </li>
												<li>You have a poor credit history</li>
												<li>You did not pay your previous QuiLoan loan</li>
												<li>You do not meet our lending criteria</li>
												<p>Whatever the situation, we recommend checking the site constantly, as the criteria of these decisions are constantly refined.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-19">My loan status shows 'Disbursed', but I am yet to receive funds in my account.</a>
                                        </div>
                                        <div id="collapse-19" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Funds are automatically received within a few minutes of receiving disbursement confirmation. We recommend checking that the bank account provided is a personal account, as we do not process transfers to 3rd party account.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-20">Why is my bank account required?</a>
                                        </div>
                                        <div id="collapse-20" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>It's important for us to send your funds to your preferred account, as quickly as possible. We request bank account details as that is where we will disburse funds to, if approved. Please note that this must be a preferred personal bank account in your name not company’s name.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-21">I don't have an Android device - can I apply on a computer or from another operating system?</a>
                                        </div>
                                        <div id="collapse-21" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p><b>Quiloan</b> is currently available on all devices that can access the internet. All you need is to visit our website and click on log in to get started.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-22">Why is my Bank Verification Number (BVN) required?</a>
                                        </div>
                                        <div id="collapse-22" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>We request your BVN as this is used to verify that the individual applying for a quiloan is the same person as the owner of the provided bank account. This is to ensure that your bank details cannot be used to apply for a loan without your authorization incase a 3rd party has access to your account details. Please note: Your BVN does not provide access to your account. If in doubt, we encourage you to confirm this from your bank before you proceed.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-23">I have submitted my application but I have not yet received any notification on my loan status.</a>
                                        </div>
                                        <div id="collapse-23" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Notifications are automatically sent via SMS/email immediately after your loan application is sent. If you have not received either of these, please check the “Loans status” menu on the website page to confirm the current status of your request.</p>
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
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-24">What is a Account number? How do I find mine?</a>
                                        </div>
                                        <div id="collapse-24" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>This is generated automatically for our client and also serves as a means for which the system uses to identify you. This can also be called an account number with us. It is displayed on the website, sent via SMS when your loan is approved, and will always be in all e-mails we send to you. It can also be found by logging into your page on the website.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-25">Can I update my banking/personal information?</a>
                                        </div>
                                        <div id="collapse-25" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>You can update your banking details and information on our webpage.</p>
												<p>If you have a running loan with us and need to update or change certain details, please contact us via our helpline provided on our webpage.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-26">How do I check my account balance and due date?</a>
                                        </div>
                                        <div id="collapse-26" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Please log into your account on our webpage to view all details concerning your loan.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-27">Why do you need my card details?</a>
                                        </div>
                                        <div id="collapse-27" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>This is very important as to enable us receive your loan repayments as of when due. At Quiloan, we  understands that this is a very delicate information, so we have taken all necessary precautions and secured our platform with world-class encryption and security technology to protect your information.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-28">My debit / ATM card is being declined when I try to complete card setup. What can I do?</a>
                                        </div>
                                        <div id="collapse-28" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>This may be due to a number of reasons:</p>
												<li>You have insufficient funds in your account. Please ensure you have at least NGN 100 in the account linked to the card for setup to be successful</li>
												<li>You haven't fully activated your card for online transactions. For card activation instructions please contact your bank for proper guideline</li>
												<li>Details entered may be incorrect: please check the card number, expiry date and CVV provided to be sure all information is valid</li>
												<li>You are attempting to setup a card already in use by another customer</li>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-28">My debit / ATM card is being declined when I try to complete card setup. What can I do?</a>
                                        </div>
                                        <div id="collapse-28" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>This may be due to a number of reasons:</p>
												<li>You have insufficient funds in your account. Please ensure you have at least NGN 100 in the account linked to the card for setup to be successful</li>
												<li>You haven't fully activated your card for online transactions. For card activation instructions please contact your bank for proper guideline</li>
												<li>Details entered may be incorrect: please check the card number, expiry date and CVV provided to be sure all information is valid</li>
												<li>You are attempting to setup a card already in use by another customer</li>
												<p>If you're still having issues with your card setup, please send us a message and we'll be happy to assist.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-29">How can I make an early repayment?</a>
                                        </div>
                                        <div id="collapse-29" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>If you want to make a payment prior to your due date, please use the 'Make a Repayment' button, found on the 'My Loans' page on the webpage.
</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-30">I have made my repayment but my account has not been updated - how come?</a>
                                        </div>
                                        <div id="collapse-30" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>You should ensure that your account to be deducted is funded before your due date for successful payments, or repay your loan using the 'Make a Repayment' button on the webpage as this automatically updates your account.</p>
												<p>If you have made a cash transfer, please be sure to include your full name and client ID in the transfer description/narration so your account can be reconciled swiftly. Payments without these details can experience additional delays. After you make the payment, please send us message with the following details:</p>
												<li>Amount Paid</li>
												<li>Date Paid</li>
												<li>Location</li>
												<li>Time</li>
												<li>Client Name and ID</li>
												<li>Bank account funds were transferred to</li>
												<li>The name/details indicated on the description/narration of the transfer</li>
												<li>Mode of repayment (bank deposit, Mobile transfer, ATM transfer etc)</li>
												<p>Please note: Repayments made via cash transfer will be updated on our platform within 1 - 2 business days of receiving your email.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-31">I just paid back my most recent Quiloan. How soon can I apply for another loan?</a>
                                        </div>
                                        <div id="collapse-31" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>You can re-apply for another loan within 24 hours as soon as your previous loan is cleared. This may qualify you for higher credit at a lower rate.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-32">Can I reschedule my loan repayment date?</a>
                                        </div>
                                        <div id="collapse-32" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Due dates are fixed when your loan is approved. We do not reschedule due dates, so please ensure you comply with the repayment schedule in order to continue having this service available to you.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-33">Can I trust Quiloan with my information?</a>
                                        </div>
                                        <div id="collapse-33" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>With Quiloan your information is always secure. When you provide us with information, your privacy becomes our top priority - our loan agreements are always kept confidential.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-34">Who has access to my data?</a>
                                        </div>
                                        <div id="collapse-34" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Quiloan understand the need for privacy. But this can only be violated when reporting a loan defaulters to an authorized Credit Bureaus. It is therefore important to ensure that you repay your loan on time so your credit history is not negatively affected. If you make timely repayments, this increases your chances of accessing credit from financial institutions in future.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-35">What is a credit bureau?</a>
                                        </div>
                                        <div id="collapse-35" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>A credit bureau is a company that collects and maintains individual credit information and sells it to lenders, creditors, and consumers in the form of a credit report. They typically work with financial institutions and other creditors to collate this data, which means, your performance on previous reported accounts impact your ability to obtain loans or other financial services in future. The more loans you repay on time, the better your credit history gets. With this, you'll be able to access better loans at cheaper rates.</p>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-36">Who should contact for support?</a>
                                        </div>
                                        <div id="collapse-36" class="panel-collapse collapse">
                                            <div class="panel-body">
												<p>Feel free to reach out to us at support@citycore.com.ng</p>
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
