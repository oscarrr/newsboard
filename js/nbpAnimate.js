(function ($nbp) {
    $nbp.fn.extend({
        nbpAnimate: function (options) {
            var mSel = $nbp(this);
            var settings = options;
            return this.each(function () {
                var interval = null;
                var mouseEnterTime = 0;
                var mouseoverTime = 0;
                var mouseIn = false;
                var isScrolling = false;
                var h;
                var delDelay = false;
                var hiddenNews = settings.max_news - settings.board_fit;
                
                h = parseFloat($nbp('.nbp_slider .new_holder:eq(0)', mSel).outerHeight())+settings.margin_bottom;
                if(settings.max_news>settings.board_fit)
                    $nbp('.nbp_slider', mSel).css('top', '-' + settings.invisible_news_top*h + 'px');
                
                $nbp('.bar #btn_up', mSel).bind('click', function(){scrollUp()});
                $nbp('.bar #btn_down', mSel).bind('click', function(){scrollDown()});
                
                
                if(settings.max_news>settings.board_fit)
                {
                    $nbp('.nbp_news', mSel).bind('mouseenter mouseleave', function(evt) {
                        var currentTime = new Date();
                        if (evt.type == 'mouseenter') 
                        {
                            mouseIn = true;
                            if (!isScrolling) 
                            {
                                $nbp('.nbp_slider .new_holder', mSel).stop(true, false);
                                clearTimeout(interval);
                                mouseEnterTime = currentTime.getTime();
                            }
                        } 
                        else if (evt.type == 'mouseleave') 
                        {
                            mouseIn = false;
                            var timeToReturn;
                            if(mouseEnterTime != 0)
                                mouseoverTime = mouseoverTime + (currentTime.getTime() - mouseEnterTime);
                            else
                                mouseoverTime = settings.stay/2;
                            if(mouseoverTime >= settings.stay)
                                timeToReturn = 1;
                            else
                                timeToReturn = settings.stay - mouseoverTime;
                            interval = setTimeout(scrollDown, timeToReturn);
                        }
                    });
                    interval = setTimeout(scrollDown, settings.stay);
                }
                function scrollUp() {
                    if (!mouseIn && !isScrolling) {
                        isScrolling = true;
                        mouseoverTime = 0;
                        mouseEnterTime = 0;
                        $nbp('.nbp_slider', mSel).animate({ top: '-=' +h }, settings.speed, 'easeOutQuad', function () {
                            clearTimeout(interval);
                            var current = $nbp('.nbp_slider .new_holder:eq(0)', mSel).clone(true);
                            $nbp('.nbp_slider', mSel).append(current);
                            $nbp('.nbp_slider .new_holder:eq(0)', mSel).remove();
                            
                            isScrolling = false;
                            interval = setTimeout(scrollDown, settings.stay);
                            $nbp('.nbp_slider', mSel).css('top', '-' + settings.invisible_news_top*h + 'px');
                        });
                    }
                }
                
                function scrollDown() {
                    if (!mouseIn && !isScrolling) {
                        isScrolling = true;
                        mouseoverTime = 0;
                        mouseEnterTime = 0;
                        $nbp('.nbp_slider', mSel).animate({ top: '+=' +h }, settings.speed, 'easeOutQuad', function () {
                            
                            clearTimeout(interval);
                            var current = $nbp('.nbp_slider .new_holder', mSel).last().clone(true);
                            $nbp('.nbp_slider', mSel).prepend(current);
                            $nbp('.nbp_slider .new_holder', mSel).last().remove();
                            isScrolling = false;
                            interval = setTimeout(scrollDown, settings.stay);
                            $nbp('.nbp_slider', mSel).css('top', '-' + settings.invisible_news_top*h + 'px');
                        });
                    }
                }
                
                $nbp('.nbp_slider .new_holder .image .new_thumbnail img.nbp_real_img', mSel).each(function(){
                    var irSel = $nbp(this).parent();
                    var $this = $nbp(this);
                    $this.attr("src", $this.attr("data-src"));                    $this.attr("data-src", "");                                        var tmpImg = new Image() ;                    tmpImg.onload = function(){                                                var imgWidth = $this.getNatProp('naturalWidth');                        var imgHeight = $this.getNatProp('naturalHeight');                        var backWidth = irSel.cssNumber('width');                        var backHeight = irSel.cssNumber('height');                        var resizeDim = resizeThumb(imgWidth, imgHeight, backWidth, backHeight);                        var resizeWidth = resizeDim[0];                        var resizeHeight = resizeDim[1];                        var imgTop = intval((resizeHeight - backHeight)/-2);                        var imgLeft = intval((resizeWidth - backWidth)/-2);
                        $this.css({                            width: resizeWidth,                            height: resizeHeight,                            top: imgTop,                            left: imgLeft                        });
                        $this.css({opacity: 0, display: 'block'}).animate({opacity: 1}, 500);                        irSel.css("background","");                                            };                                        tmpImg.src = $this.attr('src');
                });
                
            });
        }
    });
})($nbp);
function resizeThumb(width, height, min_width, min_height)
{
    var pic_ratio;
    var box_ratio;
    var ratio;
    var new_width;
    var new_height;
    
    pic_ratio = width / height;
    box_ratio = min_width / min_height;
    
    if(box_ratio >= pic_ratio)
    {
       ratio = width / min_width;
       new_width = min_width;
       new_height = intval(height / ratio); 
    }
    else
    {
       ratio = height / min_height;
       new_width = intval(width / ratio);
       new_height = min_height; 
    }
    
    return [new_width, new_height];
}
function intval( mixed_var, base ) 
{
	var tmp;
	if( typeof( mixed_var ) == 'string' ){
		tmp = parseInt(mixed_var);
		if(isNaN(tmp)){
			return 0;
		} else{
			return tmp.toString(base || 10);
		}
	} else if( typeof( mixed_var ) == 'number' ){
		return Math.floor(mixed_var);
	} else{
		return 0;
	}
}
$nbp.fn.cssNumber = function(prop){
    var v = parseInt(this.css(prop),10);
    return isNaN(v) ? 0 : v;
};
      
$nbp.fn.getNatProp = function(prop){
    var node = this[0];
    var img;
    var value;
    
    if (node.tagName.toLowerCase() === 'img') 
    {
        img = new Image();
        img.src = node.src,
        value = img[prop];
    }
    return value;
};