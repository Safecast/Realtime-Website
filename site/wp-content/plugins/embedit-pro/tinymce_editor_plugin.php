(function() {
	tinymce.create('tinymce.plugins.htmlsnippet', {
		init : function(ed, url) {
			ed.addButton('htmlsnippet', {
				title : 'htmlsnippet.htmlsnippetembed',
				image : url+'/htmlsnippetembed.png',
				title: 'Embed a HTML Snippet  ',
				onclick : function() {
					 
					var userDataInput = prompt("Insert the name of the HTML Snippet", "<?php
                                        
                                                                            require( '../../../wp-load.php' );
                                                                           $the_query = new WP_Query( 'showposts=1&orderby=date&order=DESC&post_type=html_snippet&name='.$name );
                                                                             
                                                                             // The Loop
                                                                             while ( $the_query->have_posts() ) : $the_query->the_post();
                                                                                   $out=$post->post_name; 
                                                                             endwhile;
                                                                             
                                                                             // Reset Post Data
                                                                             wp_reset_postdata();
                                                                             
                                                                            echo strip_tags($out);//stampa il titolo dell'ultimo custom post creato
                                                                            
       
       
       ?>");
					 
					 if (userDataInput != null && userDataInput != 'undefined')
						ed.execCommand('mceInsertContent', false, '[embedit snippet="'+userDataInput +'"]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		
	});
	tinymce.PluginManager.add('htmlsnippet', tinymce.plugins.htmlsnippet);
})();

