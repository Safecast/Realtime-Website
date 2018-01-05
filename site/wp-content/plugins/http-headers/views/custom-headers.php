<tr>
	<th scope="row">Custom headers
		<p class="description">Common non-standard response fields:
			<br>X-Pingback
			<br>X-Cache
			<br>X-Edge-Location
			<br>X-HTTP-Method-Override
			<br>X-Csrf-Token
			<br>X-Request-ID
			<br>X-Correlation-ID
			<br>X-Content-Duration
			<br>X-Robots-Tag
		</p>
	</th>
	<td>
		<fieldset>
			<legend class="screen-reader-text">Custom headers</legend>
        <?php
        $custom_headers = get_option('hh_custom_headers', 0);
        foreach ($bools as $k => $v)
        {
        	?><p><label><input type="radio" class="http-header" name="hh_custom_headers" value="<?php echo $k; ?>"<?php checked($custom_headers, $k); ?> /> <?php echo $v; ?></label></p><?php
		}
		?>
		</fieldset>
	</td>
	<td>
	<?php settings_fields( 'http-headers-che' ); ?>
	<?php do_settings_sections( 'http-headers-che' ); ?>
	<?php
	$custom_headers_value = get_option('hh_custom_headers_value');
	if (!$custom_headers_value) {
		$custom_headers_value = array();
	}
	?>
		<table>
			<thead>
				<tr>
					<th>Header</th>
					<th>Value</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if (empty($custom_headers_value))
			{
				?>
				<tr>
					<td><input type="text" name="hh_custom_headers_value[name][]" class="http-header-value" placeholder="X-Custom-Name"></td>
					<td><input type="text" name="hh_custom_headers_value[value][]" class="http-header-value" placeholder="some value"></td>
					<td></td>
				</tr>
				<?php 
			} else {
				foreach ($custom_headers_value['name'] as $key => $name)
				{
					if (empty($name) || empty($custom_headers_value['value'][$key]))
					{
						continue;
					}
					?>
					<tr>
						<td><input type="text" name="hh_custom_headers_value[name][]" class="http-header-value" placeholder="X-Custom-Name" value="<?php echo esc_attr($name); ?>"<?php echo $custom_headers == 1 ? NULL : ' readonly'; ?>></td>
						<td><input type="text" name="hh_custom_headers_value[value][]" class="http-header-value" placeholder="some value" value="<?php echo esc_attr($custom_headers_value['value'][$key]); ?>"<?php echo $custom_headers == 1 ? NULL : ' readonly'; ?>></td>
						<td><button type="button" class="button button-small hh-btn-delete-header" title="Delete">x</button></td>
					</tr>
					<?php
				}
			}
			?>
				<tr>
					<td colspan="2"><button type="button" class="button" id="hh-btn-add-header">+ Add header</button></td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>