<?php
include ("config/connect.php");
$PostType = $_GET['PostType'];

if($PostType == "Register_Institution")
{
?>
<input name="reg_under" type="hidden" class="form-control" value="<?php echo $PostType; ?>" id="HideValueFrank"/>
					<div class="wrap-input100">
						<select class="input100 select2" name="inst" class="form-control" required>
								<option selected='selected'>Select Institution&hellip;</option>
<?php
	$search = mysqli_query($link, "SELECT * FROM institution_data WHERE status = 'Approved' AND fontend_reg = 'Enable' ORDER BY id");
	while($get_search = mysqli_fetch_array($search))
	{
	?>
	          			<option value="<?php echo $get_search['institution_id']; ?>"><?php echo $get_search['institution_name']; ?></option>
<?php } ?>
						</select>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Select Institution to Register Under</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="fname" placeholder="FIRST NAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;FIRST NAME</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="lname" placeholder="LAST NAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;LAST NAME</span>
					</div>

					<div class="wrap-input100">
						<select class="input100" name="gender" class="form-control" required>
								<option selected='selected'>SELECT GENDER&hellip;</option>
								<option value="Male">MALE</option>
								<option value="Female">FEMALE</option>
						</select>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-edit"></i>&nbsp;GENDER</span>
					</div>

					<div class="wrap-input100">
						<input type="date" class="form-control" name="dob" placeholder="DATE OF BIRTH" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-calendar"></i></span>
					</div>

					<div class="wrap-input100">
						<input name="email" class="form-control" type="email" placeholder="YOUR EMAIL ADDRESS" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-envelope-o"></i>&nbsp;EMAIL</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="phone" placeholder="YOUR MOBILE NUMBER" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-phone"></i>&nbsp;MOBILE e.g +2348101750845, +12226373738.</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="username" placeholder="PREFERRED USERNAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;USERNAME</span>
					</div>

					<div class="wrap-input100">
						<input type="password" class="form-control" name="pass" placeholder="YOUR PREFERRED PASSWORD" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-lock"></i>&nbsp;PASSWORD</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
					<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required>
							<label class="label-checkbox100" for="ckb1">
								Click here to accept the Terms and Condition of this platform.
							</label>
						</div>
					</div>

					<div align="right">
		              <div class="box-footer">
		                	<button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
		                	<button name="register_under" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Register</i></button>

		              </div>
					  </div>
			
<?php
}
elseif($PostType == "Register_Agent"){
?>

<input name="reg_under" type="hidden" class="form-control" value="<?php echo $PostType; ?>" id="HideValueFrank"/>

					<div class="wrap-input100">
						<select class="input100" name="agent" class="form-control" required>
								<option selected='selected'>SELECT AGENT&hellip;</option>
<?php
	$search = mysqli_query($link, "SELECT * FROM agent_data WHERE status = 'Approved' ORDER BY id");
	while($get_search = mysqli_fetch_array($search))
	{
	?>
	          			<option value="<?php echo $get_search['agentid']; ?>"><?php echo $get_search['fname']; ?></option>
<?php } ?>
						</select>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-asterisk"></i>&nbsp;Select Agent to Register Under</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="fname" placeholder="FIRST NAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;FIRST NAME</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="lname" placeholder="LAST NAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;LAST NAME</span>
					</div>

					<div class="wrap-input100">
						<select class="input100" name="gender" class="form-control" required>
								<option selected='selected'>SELECT GENDER&hellip;</option>
								<option value="Male">MALE</option>
								<option value="Female">FEMALE</option>
						</select>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-edit"></i>&nbsp;GENDER</span>
					</div>

					<div class="wrap-input100">
						<input type="date" class="form-control" name="dob" placeholder="DATE OF BIRTH" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-calendar"></i></span>
					</div>

					<div class="wrap-input100">
						<input name="email" class="form-control" type="email" placeholder="YOUR EMAIL ADDRESS" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-envelope-o"></i>&nbsp;EMAIL</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="phone" placeholder="YOUR MOBILE NUMBER" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-phone"></i>&nbsp;MOBILE e.g +2348101750845, +12226373738.</span>
					</div>

					<div class="wrap-input100">
						<input type="text" class="form-control" name="username" placeholder="PREFERRED USERNAME" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-user"></i>&nbsp;USERNAME</span>
					</div>

					<div class="wrap-input100">
						<input type="password" class="form-control" name="pass" placeholder="YOUR PREFERRED PASSWORD" required>
						<span class="focus-input100"></span>
						<span class="label-input50"><i class="fa fa-lock"></i>&nbsp;PASSWORD</span>
					</div>

					<div class="flex-sb-m w-full p-t-3 p-b-32">
					<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="acpt_policy" required>
							<label class="label-checkbox100" for="ckb1">
								Click here to accept the Terms and Condition of this platform.
							</label>
						</div>
					</div>

					<div align="right">
		              <div class="box-footer">
		                	<button type="reset" class="btn bg-orange btn-flat"><i class="fa fa-times">&nbsp;Reset</i></button>
		                	<button name="register_under" type="submit" class="btn bg-blue btn-flat"><i class="fa fa-save">&nbsp;Register</i></button>

		              </div>
					  </div>
			 
<?php 
}
?>