$nbpTh=jQuery.noConflict();

(function ($nbpTh) {
    $nbpTh.fn.extend({
        nbpThemePick: function (options) {
            $nbpTh('.theme .theme_lable .override_default').bind('click', function(){
                var filename = $nbpTh(this).attr('id');
                var obj;
                var data = new Object();
                var loader = 800;
                $nbpTh('.theme .theme_lable input').each(function(){
                    $nbpTh(this).attr('checked', false);
                });
                
                $nbpTh('.custom .custom_lable input').attr('checked', true);
                
                data.command = 'jsOverride';
                data.plugin_dir = options.plugin_dir;
                var dataString = JSON.stringify(data);
                $nbpTh.post(options.plugin_dir + 'classes/nbpJsOverride.php', {data: dataString}, function (response) {
                    obj = jQuery.parseJSON(response);
                    
                    
                    $nbpTh.get(options.plugin_dir + 'admin/newsboard-plugin-appearance.tpl', function(data){
                        for(var index in obj) 
                        {
                            var re = new RegExp("{" + index + "}","gi");
                            data = data.replace( re, obj['' + index + ''] );
                        }
                        $nbpTh(this).nbpMsg(loader, 'Please wait...', options.plugin_dir);
                        setTimeout(function(){
                            $nbpTh('.nbp_settings_holder .appearance_settings').html(data);
                        }, loader);
p                    });
                    
                });
            });
            
            $nbpTh('.nbp_settings_holder .theme_picker .left_arrow img').bind('click', function(){
                $nbpTh(this).queue(function(){
                    changeTheme(defSign('+'));
                });
            });
            $nbpTh('.nbp_settings_holder .theme_picker .right_arrow img').bind('click', function(){
                $nbpTh(this).queue(function(){
                    changeTheme(defSign('-'));
                });
                
            });
            
            function changeTheme(direction)
            {
                if(direction!='error')
                    $nbpTh('.nbp_settings_holder .theme_slider').animate({ left: direction+'=' +options.theme_width }, options.speed, 'easeOutQuad', function(){
                        $nbpTh('.nbp_settings_holder .theme_picker .right_arrow img').clearQueue();
                        $nbpTh('.nbp_settings_holder .theme_picker .left_arrow img').clearQueue();
                    });
            }
            
            function defSign(ask)
            {
                if(options.hiddenThemes > 0)
                {
                    if($nbp('.nbp_settings_holder .theme_slider').cssNumber('left') < 0 && $nbp('.nbp_settings_holder .theme_slider').cssNumber('left') > options.hiddenThemes*options.theme_width*(-1))
                        return ask;
                    else if($nbp('.nbp_settings_holder .theme_slider').cssNumber('left') == 0)
                    {
                        if(ask == '-')
                            return '-';
                        else if(ask == '+')
                            return 'error';
                    }
                    else if($nbp('.nbp_settings_holder .theme_slider').cssNumber('left') == options.hiddenThemes*options.theme_width*(-1))
                    {
                        if(ask == '-')
                            return 'error';
                        else if(ask == '+')
                            return '+';
                    }
                }
                else
                    return 'error';
            }
    
        }
    });
})(jQuery);

jQuery.fn.cssNumber = function(prop){
    var v = parseInt(this.css(prop),10);
    return isNaN(v) ? 0 : v;
};