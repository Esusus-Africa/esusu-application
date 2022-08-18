 <header class="main-header">
    <!-- Logo -->
   <a href="dashboard?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $row ['abb'];?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo ($row ['name'] == true) ? $row['name'] : $aname; ?></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
      <?php
              $check_sms = mysqli_query($link, "SELECT * FROM sms WHERE status = 'Activated' AND smsuser = ''") or die (mysqli_error($link));
              if(mysqli_num_rows($check_sms) == 1)
              {
              $get_sms = mysqli_fetch_array($check_sms);
              $ozeki_user = $get_sms['username'];
              $ozeki_password = $get_sms['password'];
              $ozeki_url = $get_sms['api'];

              $url = 'username='.$ozeki_user;
              $url.= '&password='.$ozeki_password;
              $url.= '&balance='.'true&';

              $urltouse = $ozeki_url.$url;
              $response = file_get_contents($urltouse);
              ?>
              <label class="alert bg-orange btn-flat"><p><b>SMS Units:<b>: <b><?php echo number_format($response,2,'.',','); ?></b></p> </label>
              <?php
              }
              else{
              ?>
              <label class="alert alert-danger"><p><b>SMS Not Activated</b></p> </label>
              <?php }  ?>
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
      $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
      $call2 = mysqli_query($link, "SELECT * FROM agent_data WHERE agentid = '$id'");
      if(mysqli_num_rows($call) == 1){
      $row = mysqli_fetch_assoc($call);
      ?>
            <img src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row ['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $row['username']; ?>
                </p>
        <?php 
        }
        elseif(mysqli_num_rows($call2) == 1){ 
          $get_ag = mysqli_fetch_assoc($call2);
        ?>

            <img src="<?php echo $fetchsys_config['file_baseurl']; ?>Electronic 1.png" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $get_ag['fname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $fetchsys_config['file_baseurl']; ?>Electronic 1.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $get_ag['username']; ?>
                </p>

      <?php } ?>
                  </li>
        
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
         
                    <a href="profile?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><b>Profile Settings</b></a>
          
                  </div>

<div class="col-xs-4 text-center"><a href="inboxmessage?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("406"); ?>">All Tickets</a></div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
              
                <div class="pull-right">
                    <a href="../logout.php" class="btn bg-orange"><i class="fa fa-sign-out"></i>Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>