<tr>
	<th scope="row">Access-Control-Allow-Origin
		<p class="description">The Access-Control-Allow-Origin header indicates whether a resource can be shared.</p>
	</th>
	<td>
	    <fieldset>
	    	<legend class="screen-reader-text">Access-Control-Allow-Origin</legend>
	        <?php
	        $access_control_allow_origin = get_option('hh_access_control_allow_origin', 0);
	        foreach ($bools as $k => $v)
	        {
	        	?><p><label><input type="radio" class="http-header" name="hh_access_control_allow_origin" value="<?php echo $k; ?>"<?php checked($access_control_allow_origin, $k); ?> /> <?php echo $v; ?></label></p><?php
	        }
	        ?>
	    </fieldset>
	</td>
	<td>
		<?php settings_fields( 'http-headers-acao' ); ?>
		<?php do_settings_sections( 'http-headers-acao' ); ?>
		<select name="hh_access_control_allow_origin_value" class="http-header-value"<?php echo $access_control_allow_origin == 1 ? NULL : ' readonly'; ?>>
		<?php
		$items = array('*', 'HTTP_ORIGIN', 'origin');
		$access_control_allow_origin_value = get_option('hh_access_control_allow_origin_value');
		foreach ($items as $item) {
			?><option value="<?php echo $item; ?>"<?php selected($access_control_allow_origin_value, $item); ?>><?php echo $item; ?></option><?php
		}
		?>
		</select>
		<input type="text" name="hh_access_control_allow_origin_url" class="http-header-value" placeholder="http://domain.com" value="<?php echo esc_attr(get_option('hh_access_control_allow_origin_url')); ?>"<?php echo $access_control_allow_origin == 1 && $access_control_allow_origin_value == 'origin' ? NULL : ' style="display: none" readonly'; ?> />
	</td>
</tr>