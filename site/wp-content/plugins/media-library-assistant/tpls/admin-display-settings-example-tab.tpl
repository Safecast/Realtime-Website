<!-- template="before-example-table" -->
<h2>[+Example Plugins+][+results+]</h2>
<p>[+In this tab+]</p>
<p>[+You can find+]</p>
[+views+]
<form action="[+form_url+]" method="get" id="mla-search-example-form">
	<input type="hidden" name="page" value="mla-settings-menu-documentation" />
	<input type="hidden" name="mla_tab" value="documentation" />
	[+_wpnonce+]
	<p class="search-box" style="margin-top: 1em">
		<label class="screen-reader-text" for="mla-search-example-input">[+Search Example Plugins+]:</label>
		<input type="search" id="mla-search-example-input" name="s" value="[+s+]" />
		<input type="submit" name="mla-example-search" id="mla-search-example-submit" class="button" value="[+Search Plugins+]" />
		<span class="description"><br />[+Search help+]</span>
	</p>
</form>
<br class="clear" />
<div id="col-container">
	<form action="[+form_url+]" method="post" id="mla-search-example-filter">
		<input type="hidden" name="page" value="mla-settings-menu-documentation" />
		<input type="hidden" name="mla_tab" value="documentation" />
		<input type="hidden" name="mla-example-display" value="true" />
		[+_wpnonce+]

<!-- template="after-example-table" -->
		<p class="submit mla-settings-submit">
		<input name="mla-example-cancel" type="submit" class="button-primary" value="Cancel" />&nbsp;
		</p>
	</form><!-- /id=mla-search-example-filter --> 
</div><!-- /col-container -->
