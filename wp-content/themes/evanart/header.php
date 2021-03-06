<!doctype html>  

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
		<meta charset="utf-8">
		
		<title><?php wp_title(''); ?></title>
		
		<!-- Google Chrome Frame for IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<!-- mobile meta (hooray!) -->
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<!-- icons & favicons (for more: http://themble.com/support/adding-icons-favicons/) -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
				
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
		
		<!--<link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		Other Options: http://www.google.com/webfonts#ReviewPlace:refine/Collection:Lato|Rokkitt|Muli|Rosario
		<link href='http://fonts.googleapis.com/css?family=Rokkitt|Muli:300,300italic' rel='stylesheet' type='text/css'>
		-->
		
		
		<link href='http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic|Rokkitt:700' rel='stylesheet' type='text/css'>
		
		
		<!-- drop Google Analytics Here -->
		<script type="text/javascript">

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-6180140-2']);
		_gaq.push(['_trackPageview']);
	      
		(function() {
		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	      
	      </script>
		


	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/library/js/jquery.zaccordion.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/library/js/enquire.min.js"></script>
	<!--<script src="<?php echo get_template_directory_uri(); ?>/library/js/waypoints.min.js"></script>-->
	

	 

	
		<!-- end analytics -->
	</head>
	
	<body <?php body_class(); ?>>
		
		
					
		<div id="container">
			
			
			
			<header class="header" role="banner">
				<div id="inner-header" class="wrap clearfix">
					
					
					<div id="heading-container" class="clearfix">
						<!-- to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> -->
						<a href="<?php echo home_url(); ?>" rel="nofollow" id="logo">
						<img src="<?php echo get_template_directory_uri(); ?>/library/images/evanart-logo.png" alt="evanart" />
						</a>
						
						<!-- if you'd like to use the site description you can un-comment it below -->
							<!--<p class="site-desciption"><?php  bloginfo('description'); ?></p>-->
								
						
					</div>

				
				</div> <!-- end #inner-header -->
				
				
				<nav role="navigation" class="clearfix">
					
					<div class="wrap clearfix">
						<?php bones_main_nav(); ?>
					</div>
				</nav>
			
				
			
			</header> <!-- end header -->
