<?php 
$content_security_policy = get_option('hh_content_security_policy', 0);
?>
<tr valign="top">
	<th scope="row">Content Security Policy
		<p class="description">Content Security Policy (CSP) is an added layer of security that helps to detect and mitigate certain types of attacks, including Cross Site Scripting (XSS) and data injection attacks. These attacks are used for everything from data theft to site defacement or distribution of malware.</p>
		
		<p>
		<label><input type="checkbox" class="http-header-value"
			name="hh_content_security_policy_report_only" value="1"
			<?php checked(get_option('hh_content_security_policy_report_only'), 1, true); ?>
			<?php echo $content_security_policy == 1 ? NULL : ' readonly'; ?> /> "Report-Only" (for reporting-only purposes)</label>
		</p>
	</th>
	<td>
       	<fieldset>
        	<legend class="screen-reader-text">Content-Security-Policy</legend>
	    <?php
        foreach ($bools as $k => $v)
        {
        	?><p><label><input type="radio" class="http-header" name="hh_content_security_policy" value="<?php echo $k; ?>"<?php checked($content_security_policy, $k, true); ?> /> <?php echo $v; ?></label></p><?php
        }
        ?>
       	</fieldset>
	</td>
	<td>
	<?php settings_fields( 'http-headers-csp' ); ?>
	<?php do_settings_sections( 'http-headers-csp' ); ?>
		<table>
			<thead>
				<tr>
					<th>Directive</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$directives = array('default-src', 'script-src', 'style-src', 'img-src', 'connect-src', 
				'font-src', 'media-src', 'sandbox', 'report-uri', 'child-src', 'form-action',
				'frame-ancestors', 'plugin-types');
			$csp = get_option('hh_content_security_policy_value');
			foreach ($directives as $item)
			{
				?>
				<tr>
        			<td><?php echo $item; ?></td>
        			<td class="hh-td-inner" valign="middle">
        				<input type="text" name="hh_content_security_policy_value[<?php echo $item; ?>]" class="http-header-value" 
        					value="<?php echo esc_attr(@$csp[$item]); ?>"<?php echo $content_security_policy == 1 ? NULL : ' readonly'; ?>>
        			</td>
        		</tr>
				<?php
			}
			?>
        	</tbody>
		</table>
	</td>
</tr>