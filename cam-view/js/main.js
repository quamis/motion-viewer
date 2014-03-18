slideshow = {
	timer : null,
	interval: 150,
};

slideshow.start = function() {
	slideshow.timer = setInterval(function() {
		if(!slideshow.step()) {
			slideshow.stop();
		}
	}, slideshow.interval);
};
slideshow.stop = function() {
	clearInterval(slideshow.timer);
	slideshow.timer = null;
};
slideshow.isRunning = function() {
	return slideshow.timer!==null;
}
slideshow.step = function() {
	return navigator.next();
};










navigator = {
	animate: false,
};

navigator.next = function() {
	// TODO: select first item if none defined
	var img = $('#thumbnail-container').find("div.lazy.selected");
	var nimg = $(img).next();
	
	if(!nimg.get(0)) {
		return null;
	}

	navigator.select(nimg, this.animate);
	
	navigator.loadImage($(nimg).attr('data-src'));
	
	// TODO: scroll to the selected image
	return nimg;
}

navigator.prev = function() {
	// TODO: select first item if none defined
	var img = $('#thumbnail-container').find("div.lazy.selected");
	var nimg = $(img).prev();
	
	if(!nimg.get(0)) {
		return null;
	}

	navigator.select(nimg, this.animate);
	
	navigator.loadImage($(nimg).attr('data-src'));
	
	// TODO: scroll to the selected image
	return nimg;
}

navigator.select = function(nimg, animate) {
	$('#thumbnail-container').find("div.lazy.selected").each(function(idx, elm) {
		$(elm).removeClass('selected');
	});
	
	$(nimg).addClass('selected');
	
	// $('#thumbnail-container').scrollLeft(  $(nimg).position().left );
	var scrollOffset = 
		$($($('#thumbnail-container').find("div.lazy.selected")).parent().parent()).scrollLeft() 
		- $( $($('#thumbnail-container').find("div.lazy.selected")).parent().parent()).position().left 
		+ $($('#thumbnail-container').find("div.lazy.selected")).position().left
		-128;
	
	if(animate) {
		$('#thumbnail-container').animate({
			scrollLeft: scrollOffset
		}, 250);
	}
	else { 
		$('#thumbnail-container').parent().scrollLeft( scrollOffset );
	}
}

navigator.loadImage = function(src) {
	$($('#image-container').find('img').get(0)).attr('src', src+"&sz=real");
}


navigator._reloadImages_callback = function() {
	var data = navigator._reloadImages_data;
	var nimg = $('#thumbnail-container')
		.find('div.temporary')
		.get(0);
	
	var imgData = null;
	for(var i=0; i<data['files']['jpeg'].length; i++) {
		if($(nimg).hasClass('img-'+data['files']['jpeg'][i]['id'])) {
			imgData = data['files']['jpeg'][i];
			break;
		}
	}
	
	if(imgData === null) {
		clearInterval(navigator._reloadImages_timer);
		navigator._reloadImages_timer = null;
		return;
	}
	
	$(nimg)
		.css({ 
		  'backgroundImage': "url('thumb.php?camera="+navigator._reloadImages_cameraId+"&src="+imgData['path']+"&sz=128x128')"
	})
	.removeClass('temporary');
}

navigator._reloadImages_timer = null;
navigator._reloadImages_data = null;
navigator._reloadImages_cameraId = null;
navigator._reloadImages_callCount = 0;

navigator.reloadImages = function(cameraId, dt) {
	navigator._reloadImages_cameraId = cameraId;
	$.getJSON( "ajax.php?action=getImages&camera="+cameraId+"&date="+dt, function( data ) {
		var firstCall = false;
		if(navigator._reloadImages_callCount==0) {
			$('#thumbnail-container').find('div.lazy').remove();
			firstCall = true;
		}
		
		navigator._reloadImages_callCount++;
		navigator._reloadImages_data = data;
		
		navigator._reloadImages_timer = setInterval(navigator._reloadImages_callback, 140);
		
		for (var i=0; i<data['files']['jpeg'].length; i++ ) {
			var imgData = data['files']['jpeg'][i];
			
			if(!$('#thumbnail-container').find('div.'+'img-'+imgData['id']).length) {
				var img = $("<div>")
					.addClass('lazy')
					.addClass('temporary')
					.addClass('img-'+imgData['id'])
					.attr('data-src', "thumb.php?camera="+cameraId+"&src="+imgData['path'])
					.attr('src', "css/1x1.gif");
					
				if(imgData['event']) {
					img.addClass('evt-'+imgData['event']);
					img.addClass('evt-frame-'+imgData['event_frame']);
					imgData['event']
				}
				if(imgData['snapshot']) {
					img.addClass('snapshot');
				}
				
				if(firstCall && i<5) {
					$(img)
						.css({ 
							'backgroundImage': "url('thumb.php?camera="+navigator._reloadImages_cameraId+"&src="+imgData['path']+"&sz=128x128')"
						})
					.removeClass('temporary');
					
					if(firstCall && i==0) {
						img.addClass('selected');
					}
				}
				
				var label = $("<div>")
					.addClass('label')
					.html( moment(imgData['frameDate'], "YYYY-MM-DD HH:mm:ss").format("DD MMM, HH:mm") );
				img.append(label);
				
				$('#thumbnail-container').append(img);
			}
		}
	});
}