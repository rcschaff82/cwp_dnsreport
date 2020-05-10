<?php 
$down = strtok($_SERVER['REQUEST_URI'],"/")."/admin/downloadreport.php";
$base = strtok($_SERVER['REQUEST_URI'],'?')."?module=dnsreport";
$baseurl = $base;
try {
	include_once('lib/accountdnscheck.class.php');
	$accountdnscheck = new accountdnscheck();
} catch (exception $e) {
	$exception = $e->getMessage();
}

?>
<script type="text/javascript">
$(document).ready(function() {
$("#mn-15").addClass("highlight-menu");
$("#mn-15cond").removeClass("notExpand").addClass("expand");
$("#mn-15-sub").show();

});
</script>
		<div class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="<?php echo $base; ?>/index">Account DNS Check</a>
				</div>
				<ul class="nav navbar-nav">
					<li<?php if($nav == 'generatereport') echo ' class="active"'; ?>><a href="<?php echo $baseurl; ?>/index" title="Generate Report">Generate Report</a></li>
					<li<?php if($nav == 'managereports') echo ' class="active"'; ?>><a href="<?php echo $baseurl; ?>/managereports" title="Manage Reports">Manage Reports</a></li>
				</ul>
				<p class="navbar-text navbar-right">Version <?php echo $accountdnscheck->getVersion(); ?></p>
			</div>
		</div>
		<div class="container-fluid">
			<?php if(isset($error)) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div class="media">
					<div class="pull-left" style="font-size:36px;padding:0px 10px;">
						<span class="glyphicon glyphicon-thumbs-down"></span>
					</div>
					<div class="media-body">
						<h3 class="media-heading">Error!</h3>
						<p><?php echo $error; ?></p>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if(isset($success)) { ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div class="media">
					<div class="pull-left" style="font-size:36px;padding:0px 10px;">
						<span class="glyphicon glyphicon-thumbs-up"></span>
					</div>
					<div class="media-body">
						<h3 class="media-heading">Success!</h3>
						<p><?php echo $success; ?></p>
					</div>
				</div>
			</div>
			<?php } ?>

