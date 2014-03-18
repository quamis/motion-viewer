<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/normalize.min.css">
		
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/gallery.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="header-container">
            <header class="wrapper clearfix">
                <h1 class="title"><a href="index.php">Cameras</a></h1>
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
                        <h1><?=$camera['camera']['name']?></h1>
                        <p><?=$camera['camera']['description']?></p>
                    </header>
					
					<section>
						<div id="image-container">
							<?php $imgInitial=reset($camera['files']['jpeg']);?>
							<img class='visible' 
								style="background-image: url('thumb.php?camera=<?=$camera['camera']['id']?>&src=<?=$imgInitial?>&sz=real');" 
								src="css/1x1.gif" 
							/>
						</div>
						
						<div style="height: 155px; width: 640px; overflow-x: scroll;">
							<div id="thumbnail-container" style="white-space: nowrap;">
								<?php foreach($camera['files']['jpeg'] as $imgPath) { 
									$img = new \Scanner\Image($imgPath);
								?>
									<div class='lazy snapshot img-<?=$img->id?> <?=($imgInitial==$imgPath?'selected':'')?>'
										style="background-image: url('thumb.php?camera=<?=$camera['camera']['id']?>&src=<?=$img->path?>&sz=128x128');" 
										src="css/1x1.gif" 
										data-src="thumb.php?camera=<?=$camera['camera']['id']?>&src=<?=$img->path?>"
									>
										<div class='label'>
											<?=$img->frameDate->format("d M, H:i")?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</section>
				</article>

				<aside>
					<h3>Recorded days</h3>
					<div class="recordedDays">
						<ul>
							<?php foreach($files['scanner']->getStatsByDay() as $day) { ?> 
								<li data-date="<?=$day['date']->format("Y-m-d")?>">
									<b><?=$day['date']->format("M d")?></b> : <?=number_format($day['frames'])?>
								</li>
							<?php } ?>
						</ul>
					</div>
					
					<div>
						<a href="#;" onClick="$.get( 'cleanup.php', function( data ) { window.location.reload(); });" >cleanup</a>
					</div>
                </aside>

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
		<script src="lib/gallery.js" type="text/javascript"></script>
		
		<script>
			$(function(){
				navigator.reloadImages("<?=$camera['camera']['id']?>", "<?=date("Y-m-d")?>");

				$('#thumbnail-container').on('click', 'div.lazy', function(evt) {
					var img = evt.currentTarget;
					navigator.select(img, true);
					navigator.loadImage($(img).attr('data-src'));
				});
			});
			
			$("aside div.recordedDays").on('click', 'li', function(evt) {
				if(navigator._reloadImages_timer) {
					clearInterval(navigator._reloadImages_timer);
					navigator._reloadImages_timer = null;
				}
			
				navigator._reloadImages_timer = null;
				navigator._reloadImages_data = null;
				navigator._reloadImages_cameraId = null;
				navigator._reloadImages_callCount = 0;
				
				$('#thumbnail-container').find('div.lazy').remove();
				
				setTimeout(function(){
					navigator.reloadImages("<?=$camera['camera']['id']?>", $(evt.currentTarget).attr('data-date'));
				}, 50);
			});
			
			
			$($("body").get(0)).keypress(function(evt) {
				if(evt.key=="Left") {
					navigator.prev();
				}
				else if(evt.key=="Right") {
					navigator.next();
				}
				else if(evt.key=="Home") {
				}
				else if(evt.key=="End") {
				}
				else if(evt.key=="Spacebar") {
					if(slideshow.isRunning()) {
						slideshow.stop();
					}
					else {
						slideshow.start();
					}
				}
			});
		</script>
        
    </body>
</html>