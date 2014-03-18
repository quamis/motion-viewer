<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Motion viewer</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="header-container">
            <header class="wrapper clearfix">
                <h1 class="title">Cameras</h1>
                <nav>
                    <ul>
						<?php foreach($scannedFiles as $cam) { ?>
							<li><a href="index.php?view=<?=$cam['camera']['id']?>"><?=$cam['camera']['name']?></a></li>
						<?php } ?>
                    </ul>
                </nav>
            </header>
        </div>

        <div class="main-container">
            <div class="main wrapper clearfix">

                <article>
                    <header>
                        <h1><?=$setup['installation-name']?></h1>
						<h5><?=$setup['installation-address']?></h5>
                        <p>There are <?=count($scannedFiles)?> cameras configured.</p>
                    </header>
					
					<?php foreach($scannedFiles as $cam) { ?>
						<section>
							<h2><a href="index.php?view=<?=$cam['camera']['id']?>"><?=$cam['camera']['name']?></a></h2>
							<h3><?=$cam['camera']['description']?></h3>
							<div>
								Found <?=number_format(count($cam['scanner']->getAllFrames()))?> stored jpeg files.
								Folder size: <?=human_filesize($cam['folder']['size'])?> 
								
								<div class="recordedDays">
									Recorded days: 
									<ul>
										<?php foreach($files['scanner']->getStatsByDay() as $day) { ?> 
											<li>
												<b><?=$day['date']->format("M d")?></b> : <?=number_format($day['frames'])?>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</section>
					<?php } ?>
                </article>
            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div class="footer-container">
            <footer class="wrapper">
                <h3>footer</h3>
            </footer>
        </div>

        <script src="external/jquery/jquery.min.js" type="text/javascript"></script>
		<script src="external/moment.js/moment.js" type="text/javascript"></script>
		<script src="external/underscore.js/underscore.js" type="text/javascript"></script>
		
		<script src="js/main.js"></script>
    </body>
</html>
