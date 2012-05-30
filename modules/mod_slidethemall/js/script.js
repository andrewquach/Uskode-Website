if (typeof (stajQuery) == 'undefined') {
	stajQuery = jQuery;
}

/**
Script by http://www.codrops.com/
**/
stajQuery(function() {
if (typeof (stajQuery) == 'undefined') {
	stajQuery = jQuery;
}
/**
* interval : time between the display of images
* playtime : the timeout for the setInterval function
* current  : number to control the current image
* current_thumb : the index of the current thumbs wrapper
* nmb_thumb_wrappers : total number	of thumbs wrappers
* nmb_images_wrapper : the number of images inside of each wrapper
*/
var interval			= 4000;
var playtime;
var current 			= 0;
var current_thumb 		= 0;
var nmb_thumb_wrappers	= stajQuery('#msg_thumbs .msg_thumb_wrapper').length;
var nmb_images_wrapper  = 50;
/**
* start the slideshow
*/
play();

/**
* show the controls when 
* mouseover the main container
*/
slideshowMouseEvent();
function slideshowMouseEvent(){
	stajQuery('#msg_slideshow').unbind('mouseenter')
					   .bind('mouseenter',showControls)
					   .andSelf()
					   .unbind('mouseleave')
					   .bind('mouseleave',hideControls);
	}

/**
* clicking the grid icon,
* shows the thumbs view, pauses the slideshow, and hides the controls
*/
stajQuery('#msg_grid').bind('click',function(e){
	hideControls();
	stajQuery('#msg_slideshow').unbind('mouseenter').unbind('mouseleave');
	pause();
	stajQuery('#msg_thumbs').stop().animate({'top':'0px'},500);
	e.preventDefault();
});

/**
* closing the thumbs view,
* shows the controls
*/
stajQuery('#msg_thumb_close').bind('click',function(e){
	showControls();
	slideshowMouseEvent();
	stajQuery('#msg_thumbs').stop().animate({'top':'-230px'},500);
	e.preventDefault();
});

/**
* pause or play icons
*/
stajQuery('#msg_pause_play').bind('click',function(e){
	var stajQuerythis = stajQuery(this);
	if(stajQuerythis.hasClass('msg_play'))
		play();
	else
		pause();
	e.preventDefault();	
});

/**
* click controls next or prev,
* pauses the slideshow, 
* and displays the next or prevoius image
*/
stajQuery('#msg_next').bind('click',function(e){
	pause();
	next();
	e.preventDefault();
});
stajQuery('#msg_prev').bind('click',function(e){
	pause();
	prev();
	e.preventDefault();
});

/**
* show and hide controls functions
*/
function showControls(){
	stajQuery('#msg_controls').stop().animate({'right':'15px'},500);
}
function hideControls(){
  if (!window.slideThemAllLightbox) {
    stajQuery('#msg_controls').stop().animate({'right':'-110px'},500);
  } else {
    stajQuery('#msg_controls').stop().animate({'right':'-130px'},500);
  }
}

/**
* start the slideshow
*/
function play(){
	next();
	stajQuery('#msg_pause_play').addClass('msg_pause').removeClass('msg_play');
	playtime = setInterval(next,interval)
}

/**
* stops the slideshow
*/
function pause(){
	stajQuery('#msg_pause_play').addClass('msg_play').removeClass('msg_pause');
	clearTimeout(playtime);
}

/**
* show the next image
*/
function next(){
	++current;
	showImage('r');
}

/**
* shows the previous image
*/
function prev(){
	--current;
	showImage('l');
}

/**
* shows an image
* dir : right or left
*/
function showImage(dir){
	/**
	* the thumbs wrapper being shown, is always 
	* the one containing the current image
	*/
	alternateThumbs();
	
	/**
	* the thumb that will be displayed in full mode
	*/
	var stajQuerythumb = stajQuery('#msg_thumbs .msg_thumb_wrapper:nth-child('+current_thumb+')')
				.find('a:nth-child('+ parseInt(current - nmb_images_wrapper*(current_thumb -1)) +')')
				.find('img');
	if(stajQuerythumb.length){
		var source = stajQuerythumb.attr('alt');
		var title = stajQuerythumb.attr('rel');
    if (title) {
      document.getElementById('msg_text').innerHTML = title;
    }
    if (document.getElementById('msg_lightbox')) {
      document.getElementById('msg_lightbox').href=source;
      if (title) {
        document.getElementById('msg_lightbox').title = title;
      }
    }
		var stajQuerycurrentImage = stajQuery('#msg_wrapper').find('img');
		if(stajQuerycurrentImage.length){
			stajQuerycurrentImage.fadeOut(function(){
				stajQuery(this).remove();
				var imgT = stajQuery('<img />').load(function(){
					var stajQueryimage = stajQuery(this);
					resize(stajQueryimage);
					stajQueryimage.hide();
					stajQuery('#msg_wrapper').empty().append(stajQueryimage.fadeIn());
				}).attr('src',source);
        stajQuery('<a href="source" rel="coin-slider">').append(imgT);
			});
		}
		else{
			stajQuery('<img />').load(function(){
					var stajQueryimage = stajQuery(this);
					resize(stajQueryimage);
					stajQueryimage.hide();
					stajQuery('#msg_wrapper').empty().append(stajQueryimage.fadeIn());
			}).attr('src',source);
		}
				
	}
	else{ //this is actually not necessary since we have a circular slideshow
		if(dir == 'r')
			--current;
		else if(dir == 'l')
			++current;	
		alternateThumbs();
		return;
	}
}

/**
* the thumbs wrapper being shown, is always 
* the one containing the current image
*/
function alternateThumbs(){
	stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
					.hide();
	current_thumb = Math.ceil(current/nmb_images_wrapper);
	/**
	* if we reach the end, start from the beggining
	*/
	if(current_thumb > nmb_thumb_wrappers){
		current_thumb 	= 1;
		current 		= 1;
	}	
	/**
	* if we are at the beggining, go to the end
	*/					
	else if(current_thumb == 0){
		current_thumb 	= nmb_thumb_wrappers;
		current 		= current_thumb*nmb_images_wrapper;
	}
	
	stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
					.show();	
}

/**
* click next or previous on the thumbs wrapper
*/
stajQuery('#msg_thumb_next').bind('click',function(e){
	next_thumb();
	e.preventDefault();
});
stajQuery('#msg_thumb_prev').bind('click',function(e){
	prev_thumb();
	e.preventDefault();
});
function next_thumb(){
	var stajQuerynext_wrapper = stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+parseInt(current_thumb+1)+')');
	if(stajQuerynext_wrapper.length){
		stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
						.fadeOut(function(){
							++current_thumb;
							stajQuerynext_wrapper.fadeIn();									
						});
	}
}
function prev_thumb(){
	var stajQueryprev_wrapper = stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+parseInt(current_thumb-1)+')');
	if(stajQueryprev_wrapper.length){
		stajQuery('#msg_thumbs').find('.msg_thumb_wrapper:nth-child('+current_thumb+')')
						.fadeOut(function(){
							--current_thumb;
							stajQueryprev_wrapper.fadeIn();									
						});
	}				
}

/**
* clicking on a thumb, displays the image (alt attribute of the thumb)
*/
stajQuery('#msg_thumbs .msg_thumb_wrapper > a').bind('click',function(e){
	var stajQuerythis 		= stajQuery(this);
	stajQuery('#msg_thumb_close').trigger('click');
	var idx			= stajQuerythis.index();
	var p_idx		= stajQuerythis.parent().index();
	current			= parseInt(p_idx*nmb_images_wrapper + idx + 1);
	showImage();
	e.preventDefault();
}).bind('mouseenter',function(){
	var stajQuerythis 		= stajQuery(this);
	stajQuerythis.stop().animate({'opacity':1});
}).bind('mouseleave',function(){
	var stajQuerythis 		= stajQuery(this);	
	stajQuerythis.stop().animate({'opacity':0.5});
});

/**
* resize the image to fit in the container (400 x 400)
*/
function resize(stajQueryimage){
	var theImage 	= new Image();
	theImage.src 	= stajQueryimage.attr("src");
	var imgwidth 	= theImage.width;
	var imgheight 	= theImage.height;
	/*
	var containerwidth  = 400;
	var containerheight = 400;

	if(imgwidth	> containerwidth){
		var newwidth = containerwidth;
		var ratio = imgwidth / containerwidth;
		var newheight = imgheight / ratio;
		if(newheight > containerheight){
			var newnewheight = containerheight;
			var newratio = newheight/containerheight;
			var newnewwidth =newwidth/newratio;
			theImage.width = newnewwidth;
			theImage.height= newnewheight;
		}
		else{
			theImage.width = newwidth;
			theImage.height= newheight;
		}
	}
	else if(imgheight > containerheight){
		var newheight = containerheight;
		var ratio = imgheight / containerheight;
		var newwidth = imgwidth / ratio;
		if(newwidth > containerwidth){
			var newnewwidth = containerwidth;
			var newratio = newwidth/containerwidth;
			var newnewheight =newheight/newratio;
			theImage.height = newnewheight;
			theImage.width= newnewwidth;
		}
		else{
			theImage.width = newwidth;
			theImage.height= newheight;
		}
	}
	*/
	stajQueryimage.css({
		'width'	:theImage.width,
		'height':theImage.height
	});
}
});
