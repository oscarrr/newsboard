(function() {
	
	var NewsBoard = {
		
		url: null,
	
		init: function() {
		
        },
        
        insert: function() {
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, "[NewsBoard]");
		}
		
	};
	
	tinymce.create('tinymce.plugins.NewsBoard', {
		init: function(ed, url) {
			NewsBoard.url = url;
			NewsBoard.init();
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
	
	tinymce.PluginManager.add('NewsBoard', tinymce.plugins.NewsBoard);
})()
