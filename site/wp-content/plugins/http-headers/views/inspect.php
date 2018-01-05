<?php 
include dirname(__FILE__) . '/includes/config.inc.php';
include dirname(__FILE__) . '/includes/breadcrumbs.inc.php';
?>
<section class="hh-panel">
	<h3><span class="hh-highlight">Inspect headers</span></h3>
	<p>Use this tool to inspect the HTTP headers of your website or your competitor's website.</p>
    <div class="form-wrap">
		<form action="<?php echo plugin_dir_url( __FILE__ ); ?>ajax.php" method="get" id="frmIspect">
			<input type="hidden" name="do" value="inspect">
			<div class="form-row">
				<div class="form-field form-col-6">
					<label class="form-label">URL:</label>
					<input type="text" name="url" size="40" placeholder="<?php echo home_url('/'); ?>" value="<?php echo home_url('/'); ?>">
				</div>
				<div class="form-field form-col-6">
					<label class="form-label">&nbsp;</label>
					<label><input type="checkbox" name="authentication" id="authentication">Authentication</label>
				</div>
			</div>
			<div id="box-authentication" style="display: none">
				<div class="form-field">
					<label class="form-label">Auth Type:</label>
					<label class="form-lbl"><input type="radio" name="auth_type" value="basic" checked>Basic</label>
					<label class="form-lbl"><input type="radio" name="auth_type" value="digest">Digest</label>
					<label class="form-lbl"><input type="radio" name="auth_type" value="gss">GSS</label>
					<label class="form-lbl"><input type="radio" name="auth_type" value="ntlm">NTLM</label>
				</div>
				<div class="form-row">
					<div class="form-field form-col-6">
						<label class="form-label" for="username">Username:</label>
						<input type="text" name="username">
					</div>
					<div class="form-field form-col-6">
						<label class="form-label" for="password">Password:</label>
						<input type="text" name="password">
					</div>
				</div>
			</div>
			<?php submit_button('Inspect'); ?>
		</form>
	</div>
</section>

<div id="hh-result"></div>