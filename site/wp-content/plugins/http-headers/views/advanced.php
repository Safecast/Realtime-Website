<?php 
include dirname(__FILE__) . '/includes/config.inc.php';
include dirname(__FILE__) . '/includes/breadcrumbs.inc.php';
?>
<section class="hh-panel">
	<form method="post" action="options.php">
	    <table class="form-table hh-table">
			<tbody>
				<tr valign="top">
					<th scope="row">Advanced
						<p class="description">Choose a method for sending of headers. Usually, the PHP method works perfectly. However, some third-party plugins like WP Super Cache may require switching to Apache method.</p>
					</th>
					<td>&nbsp;</td>
		        	<td>
		        		<fieldset>
			        	<?php settings_fields( 'http-headers-mtd' ); ?>
						<?php do_settings_sections( 'http-headers-mtd' ); ?>
						<?php
						$items = array(
							'php' => 'Use PHP to send headers (deprecated)',
							'htaccess' => 'Use Apache (mod_headers) to send headers', 
						);
						$method = get_option('hh_method');
						foreach ($items as $key => $val) {
							?><p><label><input type="radio" name="hh_method" value="<?php echo $key; ?>"<?php checked($method, $key, true); ?>><?php echo $val; ?></label></p><?php
						}
						?>
						</fieldset>
					</td>
		        </tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
</section>