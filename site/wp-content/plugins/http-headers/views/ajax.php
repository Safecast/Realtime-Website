<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (isset($_POST['do']))
	{
		switch ($_POST['do'])
		{
			case 'inspect':
				
				if (!(isset($_POST['url']) && preg_match('|^https?://|', $_POST['url'])))
				{
					?>
					<section class="hh-panel">
						<h3><span class="hh-highlight">URL malformed</span></h3>
					</section>
					<?php
					exit;
				}
				
				include '../../../../wp-load.php';
				include 'includes/http.class.php';
				include 'includes/config.inc.php';
				$http = new Http();
				
				if (isset($_POST['authentication'], $_POST['auth_type'], $_POST['username'], $_POST['password'])
					&& in_array($_POST['auth_type'], array('basic', 'digest', 'gss', 'ntlm'))
					&& !empty($_POST['username'])
					&& !empty($_POST['password'])
				)
				{
					$http->setAuthType($_POST['auth_type']);
					$http->setPassword($_POST['password']);
					$http->setUsername($_POST['username']);
				}
				
				$http->request($_POST['url']);
				$responseHeaders = $http->getResponseHeaders();
				$status = $http->getHttpCode();
				$error = $http->getError();
				if ($status !== 200)
				{
					?>
					<section class="hh-panel">
						<h3><span class="hh-highlight">HTTP Status: <?php echo $status; ?></span></h3>
						<p><?php 
						switch ($status)
						{
							case 400:
								echo 'Bad Request';
								break;
							case 401:
								echo 'Unauthorized';
								break;
							case 403:
								echo 'Forbidden';
								break;
							case 404:
								echo 'Not Found';
								break;
							case 405:
								echo 'Method Not Allowed';
								break;
							default:
						}
						?></p>
					</section>
					<?php
					exit;
				}
				?>
				<section class="hh-panel">
					<h3><span class="hh-highlight">Response headers</span></h3>
					<table class="hh-results">
						<thead>
							<tr>
								<th style="width: 30%">Header</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						$reportOnly = array('content-security-policy-report-only', 'public-key-pins-report-only');
						foreach ($responseHeaders as $k => $v)
						{
							$k = strtolower($k);
							$found = in_array($k, $reportOnly);
							?>
							<tr<?php echo array_key_exists($k, $headers) || $found ? ' class="hh-found"' : NULL; ?>>
								<td><?php echo htmlspecialchars($k); ?></td>
								<td><?php echo htmlspecialchars($v); ?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
				</section>
				<?php
				$special = array('content-security-policy', 'public-key-pins');
				$missing = array();
				foreach ($headers as $k => $v)
				{
					if (!array_key_exists($k, $responseHeaders) && !(in_array($k, $special) && array_key_exists($k . '-report-only', $responseHeaders) ))
					{
						$missing[$k] = @$categories[$v[2]];
					}
				}
				
				if (!empty($missing))
				{
					asort($missing);
					?>
					<section class="hh-panel">
						<h3><span class="hh-highlight">Missing headers</span></h3>
						<table class="hh-results">
							<thead>
								<tr>
									<th style="width: 30%">Header</th>
									<th>Category</th>
								</tr>
							</thead>
							<tbody>
							<?php
							foreach ($missing as $k => $v)
							{
								?>
								<tr>
									<td><a href="<?php echo get_admin_url(); ?>options-general.php?page=http-headers&amp;header=<?php echo htmlspecialchars($k); ?>"><?php echo $k; ?></a></td>
									<td><?php echo $v; ?></td>
								</tr>
								<?php
							}
							?>
							</tbody>
						</table>
					</section>
					<?php
				}
				break;
		}
	}
}