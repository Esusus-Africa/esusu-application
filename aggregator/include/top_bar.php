<header class="main-header">
    <!-- Logo -->
   <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo $row ['abb'];?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo $row['name']; ?></span>
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
      $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
      $call2 = mysqli_query($link, "SELECT * FROM aggregator WHERE aggr_id = '$id'");
      if(mysqli_num_rows($call) == 1){
      $row = mysqli_fetch_assoc($call);
      ?>
            <img src="<?php echo ($row['image'] == "" || $row['image'] == "img/") ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo $row['name'].' '.$row['lname'].' '.$row['mname']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo ($row['image'] == "" || $row['image'] == "img/") ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$row['image']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username:'. $row['username']; ?>
                </p>
        <?php 
        }
        elseif(mysqli_num_rows($call2) == 1){ 
          $get_ag = mysqli_fetch_assoc($call2);
        ?>

            <img src="<?php echo ($get_ag['logo'] == "" || $get_ag['logo'] == "img/") ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$get_ag['logo']; ?>" class="user-image" alt="User Image">

          <span class="hidden-xs"><?php echo strtoupper($myaggname); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo ($get_ag['logo'] == "" || $get_ag['logo'] == "img/") ? $fetchsys_config['file_baseurl'].'image-placeholder.jpg' : $fetchsys_config['file_baseurl'].$get_ag['logo']; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo 'Username: '. $aggusername; ?>
                </p>

      <?php } ?>
                  </li>
        
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
         
                    <a href="profile?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx&&tab=tab_1"><b>Profile Settings</b></a>
          
                  </div>

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
          <button type="button" class="btn btn-flat bg-blue" align="left">
            <strong class="alert bg-orange">
            <?php
            echo "<span id='smsunit_balance'>".number_format(($aggwallet_balance/$fetchsys_config['fax']),2,'.',',')." SMS</span>";
            ?>
        </strong>
        </botton>
          <!-- Control Sidebar Toggle Button -->
        </ul>
      </div>
    </nav>
  </header>