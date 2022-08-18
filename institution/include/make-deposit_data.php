<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>">
            <h3 class="panel-title"><i class="fa fa-money"></i> Customer Deposit Form
            <button type="button" class="btn btn-flat bg-white" align="left">&nbsp;<b style="color: black;">Wallet Balance:</b>&nbsp;
			<strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
			<?php
			echo "<span id='wallet_balance'>".$icurrency.number_format($iassigned_walletbal,2,'.',',')."</span>";
			?>
			</strong>
			  </button>
            </h3>
            </div>
             <div class="box-body">

             <div class="col-md-12 mx-0">
                        <form class="form-horizontal" id="msform" method="post" enctype="multipart/form-data">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Deposit Form</strong></li>
                                <li id="authorize"><strong>Authorization</strong></li>
                                <li id="confirm"><strong>Finish</strong></li>
                            </ul> <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Account Information</h2> 
                                    <input type="email" name="email" placeholder="Email Id" /> 
                                    <input type="text" name="uname" placeholder="UserName" /> 
                                    <input type="password" name="pwd" placeholder="Password" /> 
                                    <input type="password" name="cpwd" placeholder="Confirm Password" />
                                </div> 
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Personal Information</h2>
                                    <input type="text" name="fname" placeholder="First Name" /> 
                                    <input type="text" name="lname" placeholder="Last Name" /> 
                                    <input type="text" name="phno" placeholder="Contact No." /> 
                                    <input type="text" name="phno_2" placeholder="Alternate Contact No." />
                                </div> 
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Signed Up</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
        
</div>	
</div>	
</div>
</div>