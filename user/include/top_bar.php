<header class="main-header"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <?php
     $call_memset = mysqli_query($link, "SELECT * FROM member_settings WHERE companyid = '$bbranchid'");
     $fetch_msmset = mysqli_fetch_array($call_memset);
   ?>
    <!-- Logo -->
   <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo ($bbranchid == "") ? $row['abb'] : $fetch_msmset['sender_id']; ?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo ($bbranchid == "") ? $row['name'] : $fetch_msmset['cname']; ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
		
		
		
		
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          
            <ul class="dropdown-menu">
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  
                 
                  
                  
                </ul>
              </li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
           
            <ul class="dropdown-menu">
              
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    
                  </li>
                  <!-- end task item -->
                  
                  <!-- end task item -->
                  
                  <!-- end task item -->
                  
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#"></a>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<?php 
			$id = $_SESSION['tid'];
			$call = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'");
			if(mysqli_num_rows($call) == 0)
			{
			echo "<script>alert('Data Not Found!'); </script>";
			}
			else
			{
			while($row = mysqli_fetch_assoc($call))
			{
			?>
              <img src="<?php echo ($row['image'] != '') ? $fetchsys_config['file_baseurl'].$row['image'] : $fetchsys_config['file_baseurl'].'avatar.png'; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row['lname'].' '.$row['fname'] ;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo ($row['image'] != '') ? $fetchsys_config['file_baseurl'].$row['image'] : $fetchsys_config['file_baseurl'].'avatar.png'; ?>" class="img-circle" alt="User Image">
                <p style="color: black;">
                  <?php echo 'Username:'. $row ['username'];?>
                </p>
					<?php }}?>
                  </li>
			  
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
				 
                    <!--<a href="profile.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("405"); ?>"><b>Profile Settings</b></a>-->
					
                  </div>
				<div class="col-xs-4 text-center">
				
					<!--<a href="inboxmessage.php?tid=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("406"); ?>"><b>My Tickets</b></a>-->
				
				</div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
				<!--<div class="pull-left"><a href="newmessage.php?id=<?php echo $_SESSION['tid']; ?>&&acn=<?php echo $_SESSION['acctno']; ?>&&mid=<?php echo base64_encode("406"); ?>" class="btn bg-blue">Create Tickets</a></div>-->
                <div class="pull-right">
                  <a href="../logout.php<?php echo ($bbranchid == '') ? '' : '?id='.$bbranchid; ?>" class="btn bg-orange"><i class="fa fa-sign-out"></i>Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <?php
            if($issurer == "Mastercard" && $card_id != "NULL")
            {
          ?>
          <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <span id='mastercardwallet_balance'>Loading...</span>
            </strong>
          </botton>
          <?php
            }
            elseif($issurer == "VerveCard" && $card_id != "NULL")
            {
          ?>
          <button type="button" class="btn btn-flat bg-<?php echo ($myaltrow['theme_color'] == '') ? 'blue' : $myaltrow['theme_color']; ?>" align="left">
            <strong class="alert bg-<?php echo ($myaltrow['alternate_color'] == '') ? 'orange' : $myaltrow['alternate_color']; ?>">
                <span id='vervewallet_balance'>Loading...</span>
            </strong>
          </botton>
          <?php 
          } 
          ?>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>