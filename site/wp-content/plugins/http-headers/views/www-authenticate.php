<tr valign="top">
	<th scope="row">WWW-Authenticate
		<p class="description">HTTP supports the use of several authentication mechanisms to control access to pages and other resources. These mechanisms are all based around the use of the 401 status code and the WWW-Authenticate response header.</p>
	</th>
	<td>
		<fieldset>
			<legend class="screen-reader-text">WWW-Authenticate</legend>
			<?php
			$www_authenticate = get_option ( 'hh_www_authenticate', 0 );
			foreach ( $bools as $k => $v ) {
				?><p>
					<label><input type="radio" class="http-header" name="hh_www_authenticate" value="<?php echo $k; ?>" <?php checked($www_authenticate, $k, true); ?> /> <?php echo $v; ?></label>
				</p><?php
			}
			?>
		</fieldset>
	</td>
	<td>
		<?php settings_fields( 'http-headers-wwa' ); ?>
		<?php do_settings_sections( 'http-headers-wwa' ); ?>
		<table>
			<tbody>
				<tr>
					<td>Type</td>
					<td>
						<select name="hh_www_authenticate_type" class="http-header-value"<?php echo $www_authenticate == 1 ? NULL : ' readonly'; ?>>
						<?php
						$items = array ('Basic', 'Digest');
						$www_authenticate_type = get_option ( 'hh_www_authenticate_type' );
						foreach ( $items as $item ) {
							?><option value="<?php echo $item; ?>" <?php selected($www_authenticate_type, $item); ?>><?php echo $item; ?></option><?php
						}
						?>		
						</select>
					</td>
				</tr>
				<tr>
					<td>Realm</td>
					<td><input type="text" name="hh_www_authenticate_realm" class="http-header-value" value="<?php echo esc_attr(get_option('hh_www_authenticate_realm')); ?>"<?php echo $www_authenticate == 1 ? NULL : ' readonly'; ?> placeholder="Restricted area"></td>
				</tr>
				<tr>
					<td>User</td>
					<td><input type="text" name="hh_www_authenticate_user" class="http-header-value" value="<?php echo esc_attr(get_option('hh_www_authenticate_user')); ?>"<?php echo $www_authenticate == 1 ? NULL : ' readonly'; ?>></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="text" name="hh_www_authenticate_pswd" class="http-header-value" value="<?php echo esc_attr(get_option('hh_www_authenticate_pswd')); ?>"<?php echo $www_authenticate == 1 ? NULL : ' readonly'; ?>></td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>