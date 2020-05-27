<?php
$nav = 'generatereport';
include_once('header.php');
?>

			<div class="page-header">
				<h1>Welcome to the Account DNS Check CWP Plugin!</h1>
			</div>

			<!--?php if($accountdnscheck->updateAvailable() === true) { ?>
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div class="media">
					<div class="pull-left" style="font-size:48px;padding:0px 15px;">
						<span class="glyphicon glyphicon-refresh"></span>
					</div>
					<div class="media-body">
						<h3 class="media-heading">New Version Available!</h3>
						<p>A new version of this plugin is available! <a href="<?php echo $base;?>/upgrade" class="alert-link">Upgrade Now?</a></p>
					</div>
				</div>
			</div>
			<?php } ?-->

			<?php if($accountdnscheck->resolverCheck() === false) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<div class="media">
					<div class="pull-left" style="font-size:48px;padding:0px 15px;">
						<span class="glyphicon glyphicon-exclamation-sign"></span>
					</div>
					<div class="media-body">
						<h3 class="media-heading">Resolver Warning!</h3>
						<p>You have a nameserver entry in your /etc/resolv.conf that matches an IP address that is assigned to this server.  It is recommended that you remove that entry and replace it with a external DNS server.</p>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="well text-center">
				<form action="<?php echo $base; ?>/generatereport" method="post">
					<label class="lead">What would you like to check?</label>
					<div class="form-group">
						<label class="checkbox-inline">
							<input type="checkbox" name="accountdomains" value="yes" checked="checked">
							Account Domains
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="addondomains" value="yes">
							Addon/Parked Domains
						</label>
						<label class="checkbox-inline">
							<input type="checkbox" name="subdomains" value="yes">
							Sub-domains
						</label>
					</div>
					<button type="submit" name="html" value="yes" class="btn btn-primary btn-lg">Generate HTML Report</button>
					<button type="submit" name="csv" value="yes" class="btn btn-default btn-lg">Generate CSV Report</button>
				</form>
			</div>

			<div class="page-header">
				<h2>Recent Reports <small>Your last 6 most recently generated reports</small></h2>
			</div>
			<div class="row text-center">
				<?php 
				$reports = @array_slice($accountdnscheck->reports(), 0, 6);
				if(@count($reports) > 0) {
					foreach($reports as $k => $v) {
				?>
				<div class="col-md-4">
					<h4><?php echo $v['filename']; ?></h4>
					<h5><?php echo date("r", $v['date']); ?></h5>
					<?php if(preg_match('/\.csv$/', $v['filename'])) { ?>
					<a href="downloadreport.php?report=<?php echo $v['filename']; ?>" class="btn btn-primary btn-xs">Download</a>
					<?php } else { ?>
					<a href="<?php echo $base; ?>/viewreport&report=<?php echo $v['filename']; ?>" class="btn btn-primary btn-xs">View</a>
					<?php } ?>
					<a href="<?php echo $base; ?>/managereports&delete=<?php echo $v['filename']; ?>" class="btn btn-danger btn-xs" title="Delete" onClick="return confirm('Are you sure you want to delete this report?')">Delete</a>
					<hr>
				</div>
				<?php
					} 
				} else {
				?>
				<div class="col-md-12">
					<p>No reports found</p>
				</div>
				<?php } ?>
			</div>
			<?php if(count($reports) > 0) { ?>
			<div class="text-center">
				<a href="<?php echo $base; ?>/managereports" title="View all reports">View all Reports</a>
			</div>
			<?php } ?>

<?php include_once('footer.php'); ?>
