
(function() {
    tinymce.create('tinymce.plugins.st_cf_embed', {
        init : function(ed, url) {
            ed.addButton('st_cf_embed', {
                title : 'st_cf_embed.cf_embed',
                image : url+'/htmlcustomfieldembed.png',
                title: 'Embed from  Custom Field  ',
                onclick : function() {
                    
                    var vidId = prompt("Embed HTML from Custom Field", "Enter custom field name");
                  //var m = idPattern.exec(vidId);
					 if (vidId != null && vidId != 'undefined')
						ed.execCommand('mceInsertContent', false, '[embedit cf="'+vidId +'"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    
    });
    tinymce.PluginManager.add('st_cf_embed', tinymce.plugins.st_cf_embed);
})();
