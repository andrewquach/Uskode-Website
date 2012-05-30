if (window.MooTools) {
	window.addEvent('load', function() {
		var hldiv = $('HLwrapper');
		var ua = navigator.userAgent;
		if (ua.indexOf('MSIE') == -1) { hldiv.setStyles( {'height':1} ); }
		hldiv.setProperty('open', false);
		var invisible_h=document.getElementById("HLhidden").offsetHeight-hoffset;
		var trigger =  $('HLtrigger');
		if (Fx.Tween) {
			var ani = new Fx.Tween(hldiv, {duration: 700});
		} else {
			var ani = new Fx.Style(hldiv, 'margin-top', {duration: 700});	
		}
		ani.options.transition = Fx.Transitions.Cubic.easeOut;
		if (startopen) {
			hldiv.setProperty('open', true);		
			hldiv.setStyles({'margin-top':0});		
		} else {				
			hldiv.setStyles({'margin-top':-(invisible_h)});		
		}		
		hldiv.setOpacity(HLopacity);


		trigger.addEvent('click', function(event){
			if (Fx.Tween) {
				if (hldiv.getProperty('open')=='true') {
					var invisible_h=document.getElementById("HLhidden").offsetHeight-hoffset;
					ani.start('margin-top',-invisible_h);
					hldiv.setProperty('open', false);            
				} else {
					ani.start('margin-top',0);
					hldiv.setProperty('open', true);   
				}
			} else {
				if (hldiv.getProperty('open')=='true') {
					var invisible_h=document.getElementById("HLhidden").offsetHeight-hoffset;
					ani.start(-invisible_h);
					hldiv.setProperty('open', false);   
				} else {
					ani.start(0);
					hldiv.setProperty('open', true);  				
				}
			}
		});
			
		if (Fx.Tween) {	
			$(document.body).addEvent('click',function(e) { 
				var showingParent = hldiv;
				if(showingParent && !e.target || !$(e.target).getParents().contains(showingParent)) {  
					if (hldiv.getProperty('open')=='true') {
							var invisible_h=document.getElementById("HLhidden").offsetHeight-hoffset;
							ani.start('margin-top',-invisible_h);
							hldiv.setProperty('open', false);            
					}  
				} 
			}); 
		}
		
	});
} else {
	alert('Hotlogin error: MooTools is not loaded!');
}
