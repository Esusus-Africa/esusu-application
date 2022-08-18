        <!-- start site-footer -->
        <footer class="site-footer">
            <div class="copyright-info">
                <div class="container">
                    <div class="row">
                        <div class="col col-xs-6">
                            <div class="copyright-area">
	<?php $sql = "SELECT footer FROM systemset";
		$result = mysqli_query($link,$sql);
  		if(!$result)
  		{
		echo '
		<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>Warning!</strong> Unable to select! check database con.
		</div>';
		}
		else
		{
while ($row=mysqli_fetch_array($result))
		{
?>
                                <p><?php echo $row ['footer'];?></p>
<?php }}?>
                            </div>
                        </div>
                        <div class="col col-xs-6">
                            <div class="footer-social">
                                <span>Follow us: </span>
                                <ul class="social-links">
                                    <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin-square"></i></a></li>
                                    <li><a href="#"><i class="fa fa-google-plus-square"></i></a></li>
                                    <li><a href="#"><i class="fa fa-rss-square"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end site-footer -->        
    </div>
    <!-- end of page-wrapper -->



    <!-- All JavaScript files
    ================================================== -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Plugins for this template -->
    <script src="assets/js/jquery-plugin-collection.js"></script>

    <!-- Custom script for this template -->
    <script src="assets/js/script.js"></script>
</body>
</html>