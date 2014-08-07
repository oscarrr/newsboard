(function() {
	
	var NewsBoard = {
		
		url: null,
        
        insert: function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, "[NewsBoard]");
		}
		
	};
	
	if(tinymce.majorVersion >= 4 )
    {
    	tinymce.create('tinymce.plugins.NewsBoard', {
    		init: function(editor, url) {
                NewsBoard.url = url;
                
                editor.addButton('NewsBoard', {
                    type: 'menubutton',
                    text: '',
                    icon : 'NewsBoardMCEIcon',
                    menu: [{
                    	text: 'Insert NB item',
                    	onclick: NewsBoard.insert
                    }],
                    onPostRender: function(){
                        jQuery('.mce-i-NewsBoardMCEIcon').css('background', "url(" + NewsBoard.url + "/../images/icon_tinymce.png)");
                    }
                }); 
                
    		}
		
		});
    }
    else
    {
		tinymce.create('tinymce.plugins.NewsBoard', {
			init: function(ed, url) {
				NewsBoard.url = url;
			},
			
			createControl: function(n, cm) {
				switch (n) {
					case 'NewsBoard':
						var c = cm.createSplitButton('NewsBoard', {
							title : 'Insert NewsBoard',
							image : NewsBoard.url + '/../images/icon_tinymce.png',
							onclick : function() {
								c.showMenu();
							}
						});
						c.onRenderMenu.add(function(c,m) {
	                        m.add({title : 'Insert NB item', onclick : NewsBoard.insert});
						});
					return c;
				}
				return null;
			},
		});
    }
	tinymce.PluginManager.add('NewsBoard', tinymce.plugins.NewsBoard);
})()
