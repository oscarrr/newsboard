$nbp=jQuery.noConflict();
(function ($nbp) {
    $nbp.fn.extend({
        nbpThemePick: function (options) {
            $nbp('.theme .theme_lable .override_default').bind('click', function(){
                var filename = $nbp(this).attr('id');
                var obj;
                var data = new Object();
                var loader = 800;
                $nbp('.theme .theme_lable input').each(function(){
                    $nbp(this).attr('checked', false);
                });
                
                $nbp('.custom .custom_lable input').attr('checked', true);
                console.log(options); 
                data.command = 'jsOverride';
                data.plugin_dir = options.plugin_dir;
                var dataString = JSON.stringify(data);
                $nbp.post(options.plugin_dir + 'classes/nbpJsOverride.php', {data: dataString}, function (response) {
                    obj = $nbp.parseJSON(response);
                    
                    
                    $nbp.get(options.plugin_dir + 'admin/newsboard-appearance.tpl', function(data){
                        for(var index in obj) 
                        {
                            var re = new RegExp("{" + index + "}","gi");
                            data = data.replace( re, obj['' + index + ''] );
                        }
                        $nbp(this).nbpMsg(loader, 'Please wait...', options.plugin_dir);
                        setTimeout(function(){
                            $nbp('.nbp_settings_holder .appearance_settings').html(data);
                        }, loader);
p                    });
                    
                });
            });
            
            $nbp('.nbp_settings_holder .theme_picker .left_arrow img').bind('click', function(){
                $nbp(this).queue(function(){
                    changeTheme(defSign('+'));
                });
            });
            $nbp('.nbp_settings_holder .theme_picker .right_arrow img').bind('click', function(){
                $nbp(this).queue(function(){
                    changeTheme(defSign('-'));
                });
                
            });
            
            function changeTheme(direction)
            {
                if(direction!='error')
                    $nbp('.nbp_settings_holder .theme_slider').animate({ left: direction+'=' +options.theme_width }, options.speed, 'easeOutQuad', function(){
                        $nbp('.nbp_settings_holder .theme_picker .right_arrow img').clearQueue();
                        $nbp('.nbp_settings_holder .theme_picker .left_arrow img').clearQueue();
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
})($nbp);
$nbp.fn.cssNumber = function(prop){
    var v = parseInt(this.css(prop),10);
    return isNaN(v) ? 0 : v;
};