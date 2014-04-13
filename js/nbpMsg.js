$nbp.fn.nbpMsg = function(stay, text, plugin_dir)
{
    var isOpen = false;
    
    $nbp('#nbp_wrap').append('<div class="nbp_pre_holder"><div class="nbp_pre_bgCover"><div class="nbp_pre_overlayBox" align="center"><div class="nbp_pre_loader"></div><div class="nbp_pre_text">' + text + '</div></div></div></div>');
    $nbp('.nbp_pre_loader').css({
        'background-image':'url(' + plugin_dir + 'images/loader.gif)'
    });
    doOverlayOpen(stay);
        
    function showoverlayBox() 
    {
    	if( isOpen == false ) return;
    	
        $nbp('.nbp_pre_overlayBox').css({
  		    display:'block'
   	    });
    	
        $nbp('.nbp_pre_bgCover').css({
  		    display:'block',
    		width: $nbp('#nbp_wrap').width(),
    		height: $nbp('#wpbody').height()
   	    });
    }
    function doOverlayOpen(stay) 
    {
    	isOpen = true;
    	showoverlayBox();
    	//$nbp('.nbp_pre_bgCover').css({opacity:0}).animate( {opacity:1} );
        setTimeout(doOverlayClose, stay);
        return false;
    }
    function doOverlayClose() 
    {
    	isOpen = false;
        $nbp('.nbp_pre_holder').remove();
    }
    $nbp('#nbp_wrap').bind('resize',showoverlayBox);   
    
}
