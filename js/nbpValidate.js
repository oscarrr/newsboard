$nbpVal=jQuery.noConflict();

$nbpVal(document).ready(function () {
    
    $nbpVal('.pnb_settings_holder #select_all_categories').click(function(){
        $nbpVal('.pnb_settings_holder .cat_select option').each(function(){
            $nbpVal(this).prop('selected', true);
        });
    });
    
    $nbpVal('.pnb_settings_holder .shortcode_input').mouseover(function(){
        $nbpVal(this).select();
    });
});

(function ($nbpVal) {
    $nbpVal.fn.extend({
        nbpValidate: function (options) {            
            var curr_el = options.curr_el;
            $nbpVal(document).delegate(curr_el, 'keydown', function(e) {
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
            $nbpVal.post(options.pluginDir + 'newsboard-constants.php', {data: dataString}, function (response) {
                obj = jQuery.parseJSON(response);
            });
            
            $nbpVal(document).delegate('.color_input', 'keydown', function(){
                $nbpVal(this).farbtastic($nbpVal(this));
            });
            
            $nbpVal(document).delegate('.pickcolor', 'click', function(e) {
                colorPicker = jQuery(this).next('div');
                input = jQuery(this).prev('input');
                $nbpVal(colorPicker).farbtastic(input);
                colorPicker.show();
                e.preventDefault();
                $nbpVal(document).mousedown( function() {
                    $nbpVal(colorPicker).hide();
                    Validation($nbpVal('#' + input.attr('id')));
                });
            });
            
            $nbpVal(document).delegate(curr_el, 'change focusout', function(){
                Validation($nbpVal(this));                  
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
                    if($nbpVal('#msg_' + selector.attr('name')).length <= 0)
                        selector.parent().prepend('<div id="msg_' + selector.attr('name') + '" class="valid_error_holder"><div class="valid_error">' + obj[selector.attr('name')].msg + '</div></div>');
                    $nbpVal('.pnb_submit_btn input:submit').attr('disabled', 'disabled');
                }
                else
                {
                    $nbpVal('#msg_' + selector.attr('name')).remove();
                    selector.css('border-color', '#dfdfdf');
                    selector.focus(function(){
                        selector.css('border-color', '#bbbbbb');    
                    });
                    if($nbpVal('.pnb_settings_holder .valid_error').length <= 0)
                        $nbpVal('.pnb_submit_btn input:submit').removeAttr('disabled');
                }
            }
        }
    });
})(jQuery);