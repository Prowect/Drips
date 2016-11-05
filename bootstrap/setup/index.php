<?php 

require_once __DIR__ . DIRECTORY_SEPARATOR . 'installer.php'; 

$installer = new Installer(DRIPS_DIRECTORY);

if(isset($_GET['install'])){
	$installer->install();
	exit;
}

$requirements = $installer->getRequirements();
$canInstall = (bool)array_product($requirements);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Welcome to Drips | Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css"><?php include(__DIR__ . DIRECTORY_SEPARATOR . 'style.css'); ?></style>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
</head>
<body>
	<header id="header">
		<div class="wrapper">
 			<?php include(__DIR__ . DIRECTORY_SEPARATOR . 'logo.php'); ?>
		</div>
	</header>
	<div class="page-header">
		<div class="wrapper">
			<h1>Welcome to Drips</h1>
		</div>
	</div>

	<div id="welcome" class="page">
		<div class="wrapper">
			<span class="label">Overview</span>
		</div>
		<div class="content">
			<div class="wrapper">
				<h2>Introduction</h2>

				<p>In the following section, you can install Drips.</p>

				<p>If you have already installed Drips, dependencies may be missing - but that does not matter. The installer will attempt to reinstall the missing components. Your development progress remains unchanged.</p>

				<h2>Requirements</h2>

				<table>
					<tr class="requirement">
						<?php if($requirements['permissions']): ?>
							<td><i class="fa fa-check-circle success"></i></td>
							<td>File- and directory permissions</td>
						<?php else: ?>
							<td><i class="fa fa-times-circle error"></i></td>
							<td>The <code><?= DRIPS_DIRECTORY ?></code> directory is not writable (missing permission)</td>
						<?php endif; ?>
					</tr>
					<tr class="requirement">
						<?php if($requirements['phpversion']): ?>
							<td><i class="fa fa-check-circle success"></i></td>
							<td>PHP &gt;= <code>5.6</code> (your version: <code><?= PHP_VERSION ?></code>)</td>
						<?php else: ?>
							<td><i class="fa fa-times-circle error"></i></td>
							<td>Your PHP version is deprecated. You need PHP <code>5.6</code> or later (your version: <code><?= PHP_VERSION ?></code>)</td>
						<?php endif; ?>
					</tr>
					<tr class="requirement">
						<?php if($requirements['shell_exec']): ?>
							<td><i class="fa fa-check-circle success"></i></td>
							<td><code>shell_exec()</code> is available and callable</td>
						<?php else: ?>
							<td><i class="fa fa-times-circle error"></i></td>
							<td><code>shell_exec()</code> is not available but required for automatic setup</td>
						<?php endif; ?>
					</tr>
				</table>

				<?php if($canInstall): ?>
					<p class="success">Your system meets all requirements. You can now start the installation process.</p>
					<button id="install-btn" onclick="install()">Install now</button>
				<?php else: ?>
					<p class="error">Your system does not meet all requirements. Accordingly, drips can not be installed.</p>
					<a href="." class="btn">Try again</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div id="progress" class="page">
		<div class="wrapper">
			<span class="label">Installation</span>
		</div>
		<div class="content">
			<div class="wrapper">
				<h2>Installation</h2>

				<p>Installing Drips. This process can take several minutes.</p>

				<p>The following is an overview of the installation process. More information can be found in the installation log. (<code>bootstrap/setup/install.log</code>)</p>

				<pre id="install-output"></pre>
				<div id="loading">
					<table>
						<tr>
							<td>
								<i class="fa fa-spin fa-2x fa-spinner"></i>
							</td>
							<td>
								<span>Installation in progress ...</span>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<footer id="footer">
		Copyright &copy; <a href="http://prowect.com" target="_blank">Prowect</a> 2015-<?= date('Y') ?>
	</footer>

	<script type="text/javascript">
		function install(){
			document.getElementById('welcome').style.display = 'none';
			document.getElementById('progress').style.display = 'block';
			var terminal = document.getElementById("install-output");
			var client = new XMLHttpRequest();
			client.open("get", "?install");
			client.send();
			client.onprogress = function(){
				terminal.innerHTML = this.responseText;
				terminal.scrollTop = terminal.scrollHeight;           
			};
			client.onerror = function(){
				terminal.innerHTML += "<p class='error'>Installation failed!</p>";
				terminal.scrollTop = terminal.scrollHeight;           
			};
			client.onload = function(){
				document.getElementById('loading').style.display = 'none';
				terminal.scrollTop = terminal.scrollHeight;           
			};
		}
	</script>
</body>
</html>