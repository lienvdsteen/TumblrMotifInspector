<!DOCTYPE html>
<html>
  <head>
		<title>Tumblr Motif Inspector</title>
		<script src="global/js/jquery-1.9.1.js"></script>
		<script src="global/js/bootstrap.js"></script>
    <script src="global/js/bootstrap-alert.js"></script>
		<link href="global/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <link href="global/css/bootstrap-responsive.css" rel="stylesheet" media="screen" />
    <link href="global/css/style.css" rel="stylesheet" media="screen" />
  </head>
	<body>
    <div class="container">
      <div class="masthead">
        <h3 class="muted">Tumblr Motif Inspector</h3>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <ul class="nav">
                <li <?php if( $data['action']=="index" ): ?>class="active"<?php endif; ?>><a href="tumblr">Home</a></li>
                <li <?php if( $data['action']=="about" ): ?>class="active"<?php endif; ?>><a href="about">About</a></li>
                <li <?php if( $data['action']=="contact" ): ?>class="active"<?php endif; ?>><a href="contact">Contact</a></li>
              </ul>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>

       
	<!-- Jumbotron -->
      <div class="jumbotron">
        <h1>Tumblr Motif Inspector!</h1>
        <p class="lead">Find out all the stats about your Tumblr.</p>
      	<form method="post" action="tumblr">
        	<input type="text" class="span3" name="blogname">
        	<button type="submit" class="btn btn-success">Go!</button>
      	</form>
      </div>

      <script>
     	//@todo lien: when empty textfield > disable button, else enable button
      </script>


      <hr>

      <div class="footer">
        <p>&copy; Tumblr Motif Inspector 2013</p>
      </div>

    </div
	
    <script src="global/js/bootstrap-transition.js"></script>
    <script src="global/js/bootstrap-modal.js"></script>
    <script src="global/js/bootstrap-dropdown.js"></script>
    <script src="global/js/bootstrap-scrollspy.js"></script>
    <script src="global/js/bootstrap-tab.js"></script>
    <script src="global/js/bootstrap-tooltip.js"></script>
    <script src="global/js/bootstrap-popover.js"></script>
    <script src="global/js/bootstrap-button.js"></script>
    <script src="global/js/bootstrap-collapse.js"></script>
    <script src="global/js/bootstrap-carousel.js"></script>
    <script src="global/js/bootstrap-typeahead.js"></script>
  </body>
</html>