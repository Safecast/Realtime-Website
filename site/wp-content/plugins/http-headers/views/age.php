<tr>
	<th scope="row">Age
		<p class="description">The Age header contains the time in seconds the object has been in a proxy cache.</p>
	</th>
	<td>
        <fieldset>
        	<legend class="screen-reader-text">Age</legend>
	    <?php
        $age = get_option('hh_age', 0);
        foreach ($bools as $k => $v)
        {
        	?><p><label><input type="radio" class="http-header" name="hh_age" value="<?php echo $k; ?>"<?php checked($age, $k); ?> /> <?php echo $v; ?></label></p><?php
        }
        ?>
		</fieldset>
	</td>
	<td>
		<?php settings_fields( 'http-headers-age' ); ?>
		<?php do_settings_sections( 'http-headers-age' ); ?>
		<input type="text" name="hh_age_value" class="http-header-value" value="<?php echo esc_attr(get_option('hh_age_value')); ?>"<?php echo $age == 1 ? NULL : ' checked'; ?>>
		seconds
	</td>
</tr>