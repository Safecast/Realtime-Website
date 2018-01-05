<?php 
include dirname(__FILE__) . '/includes/config.inc.php';
?>
<div class="hh-wrapper">
	<div class="hh-categories">
	<?php
	$tmp = array();
	foreach ($headers as $item)
	{
		if (!isset($tmp[$item[2]]))
		{
			$tmp[$item[2]] = array('total' => 0, 'on' => 0);
		}
		$tmp[$item[2]]['total'] += 1;
		if (get_option($item[1]) == 1)
		{
			$tmp[$item[2]]['on'] += 1;
		}
	}
	foreach ($categories as $key => $val)
	{
		?>
		<a href="<?php echo get_admin_url(); ?>options-general.php?page=http-headers&amp;category=<?php echo $key; ?>" class="hh-category">
			<i></i>
			<span><?php echo $val[0]; ?></span>
			<strong><?php echo $val; ?></strong>(<?php printf('%u/%u', @$tmp[$key]['on'], @$tmp[$key]['total']); ?>)</a>
		<?php 
	}
	?>
    </div>

	<div class="hh-sidebar">
		<div class="hh-sidebar-inner">
			<h3>Rate us</h3>
			<p>Tell us what you think about this plugin <a href="https://wordpress.org/support/plugin/http-headers/reviews/?rate=5#new-post">writing a review</a>.</p>
			<h3>Contribute</h3>
			<p>Help us to continue developing this plugin with a small donation.</p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
            	<input type="hidden" name="cmd" value="_xclick">
            	<input type="hidden" name="business" value="biggie@abv.bg">
            	<input type="hidden" name="item_name" value="HTTP Headers Donation">
            	<input type="hidden" name="no_shipping" value="1">
            	<input type="hidden" name="lc" value="US">
            	<input type="hidden" name="currency_code" value="USD">
            	<input type="hidden" name="item_number" value="">
            	$ <input type="text" name="amount" value="5" size="3">
            	<button type="submit" class="button">Donate</button>
            </form>
		</div>
	</div>
</div>