$nbp(document).ready(function () {
    
    $nbp('.pnb_settings_holder #select_all_categories').click(function(){
        $nbp('.pnb_settings_holder .cat_select option').each(function(){
            $nbp(this).prop('selected', true);
        });
    });
    
    $nbp('.pnb_settings_holder .shortcode_input').mouseover(function(){
        $nbp(this).select();
    });
});
(function ($nbp) {
    $nbp.fn.extend({
        nbpValidate: function (options) {            
            var curr_el = options.curr_el;
            $nbp(document).delegate(curr_el, 'keydown', function(e) {
                if(e.keyCode == 13)
                    e.preventDefault();
            });
            
            var dirPlugin;
            var obj;
            var data = new Object();
            if(options.curr_el == '#pnb_items_holder input:text')
                data.command = 'getItems';
            else
                data.command = 'getElements';
            var dataString = JSON.stringify(data);
            $nbp.post(options.pluginDir + 'newsboard-constants.php', {data: dataString}, function (response) {
                obj = $nbp.parseJSON(response);
            });
            
            $nbp(document).delegate('.color_input', 'keydown', function(){
                $nbp(this).farbtastic($nbp(this));
            });
            
            $nbp(document).delegate('.pickcolor', 'click', function(e) {
                colorPicker = $nbp(this).next('div');
                input = $nbp(this).prev('input');
                $nbp(colorPicker).farbtastic(input);
                colorPicker.show();
                e.preventDefault();
                $nbp(document).mousedown( function() {
                    $nbp(colorPicker).hide();
                    Validation($nbp('#' + input.attr('id')));
                });
            });
            
            $nbp(document).delegate(curr_el, 'change focusout', function(){
                Validation($nbp(this));                  
            });
                
            function Validation(param)
            {
                var selector = param;
                var re = new RegExp(obj[selector.attr('name')].pattern,"gi");
                if(re.test(selector.val()) == false)
                {
                    selector.css('border-color', 'red');
                    selector.focus(function(){
                        selector.css('border-color', 'red');    
                    });
                    if($nbp('#msg_' + selector.attr('name')).length <= 0)
                        selector.parent().prepend('<div id="msg_' + selector.attr('name') + '" class="valid_error_holder"><div class="valid_error">' + obj[selector.attr('name')].msg + '</div></div>');
                    $nbp('.pnb_submit_btn input:submit').attr('disabled', 'disabled');
                }
                else
                {
                    $nbp('#msg_' + selector.attr('name')).remove();
                    selector.css('border-color', '#dfdfdf');
                    selector.focus(function(){
                        selector.css('border-color', '#bbbbbb');    
                    });
                    if($nbp('.pnb_settings_holder .valid_error').length <= 0)
                        $nbp('.pnb_submit_btn input:submit').removeAttr('disabled');
                }
            }
        }
    });
})($nbp);