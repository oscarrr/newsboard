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
                
            });
        }
    });
})($nbp);

$nbp.fn.cssNumber = function(prop){
    var v = parseInt(this.css(prop),10);
    return isNaN(v) ? 0 : v;
};