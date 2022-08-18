<div class="box">
	       <div class="box-body">
			<div class="panel panel-success">
            <div class="panel-heading bg-blue">
            <h3 class="panel-title"><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=NDAx"><button type="button" class="btn btn-flat bg-orange"><i class="fa fa-mail-reply-all"></i>&nbsp;Back</button> </a>  <i class="fa fa-money"></i> Top-up SMS</h3>
            </div>
             <div class="box-body">		
	  
				<div class="box-body">
					<?php
					$search_set = mysqli_query($link, "SELECT * FROM systemset");
					$fetch_query = mysqli_fetch_object($search_set);
					?>
					<p>
						<h4><b style="color: blue;">SMS Pricing: <?php echo $fetch_query->currency.$fetch_query->fax.' / SMS'; ?></b></h4>
					</p>
					<p>
						Our SMS Gateway is highly robust with Excellent Delivery and 99.9% Server Uptime.
					</p>
					<p><h4 style="color:orange; font-size: 19px;"><b> Access Bank Plc </b></h4></p>
					<p> Account name: <span style="color:orange; font-size: 18px"> ZEEZZPLANET SOLUTIONS </span></p>
					<p> Account number: <span style="color:orange; font-size: 18px"> 0709583673 </span></p>
					</p>
					<p>
						<h4><b style="color: blue;">Note: remember to use your Account ID as your depositor's name for instant crediting.</b></h4>
					</p>
				</div>
				<hr>


</div>	
</div>	
</div>
</div>