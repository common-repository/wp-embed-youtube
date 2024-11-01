(function() {
	
	tinymce.PluginManager.requireLangPack('wey');
	tinymce.create('tinymce.plugins.WEYPlugin', {
		init : function(ed,url) {
			ed.addCommand('mceWEY', function() {
				ed.windowManager.open( {
					file : ajaxurl+'?action=show_diaglogbox',
					width : 670 ,
					height : 560,
					inline : 1}, {
						plugin_url : url,
					}
				)}
			);

			ed.addButton('wey', {
				title : 'Insert Youtube/Vimeo',
				cmd : 'mceWEY',
				image : url + '/../img/video.png',
			});

			ed.onNodeChange.add
				(function(ed,cm,n) {
					cm.setActive('wey',n.nodeName=='IMG')
				})
		}
		
	});
	tinymce.PluginManager.add('wey',tinymce.plugins.WEYPlugin)
})();