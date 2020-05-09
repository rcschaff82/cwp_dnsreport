<?php
$nav = 'managereports';

if(isset($_REQUEST['delete'])) {
	if(preg_match('/^[a-zA-Z0-9\-_\.]*$/', $_REQUEST['delete'])) {
		if(is_file('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['delete'])) {
			@unlink('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['delete']);
			$success = 'Successfuly deleted report stored at reports/' . $_REQUEST['delete'];
		} else {
			$error = 'The report you are trying to delete is not a file or does not exist';
		}
	} else {
		$error = 'The report you are trying to delete contains invalid characters in it\'s name';
	}
}
		

include_once('header.php');
?>

			<div class="page-header">
				<h1>Manage Reports <small>A full list of all reports generated and stored on your server</small></h1>
			</div>


			<table class="table table-condensed table-striped">
				<thead>
					<tr>	
						<th>Name</th>
						<th>Date</th>
						<th style="width:25%">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$reports = $accountdnscheck->reports();
					if( @count($reports) > 0 ) {
						foreach($reports as $k => $v) {
					?>
					<tr>
						<td><?php echo $v['filename']; ?></td>
						<td><?php echo date("r", $v['date']); ?></td>
						<td>
							<?php if(preg_match('/\.csv$/', $v['filename'])) { ?>
							<a href="downloadreport.php?report=<?php echo $v['filename']; ?>" class="btn btn-primary btn-xs">Download</a>
							<?php } else { ?>
							<a href="<?php echo $baseurl; ?>/viewreport&report=<?php echo $v['filename']; ?>" class="btn btn-primary btn-xs">View</a>
							<?php } ?>
							<a href="<?php echo $baseurl; ?>/managereports&delete=<?php echo $v['filename']; ?>" class="btn btn-danger btn-xs" title="Delete" onClick="return confirm('Are you sure you want to delete this report?')">Delete</a>
						</td>
					</tr>
					<?php
						}
					} else {
					?>
					<tr>
						<td colspan="3" class="text-center">No reports found...</td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>

<?php include_once('footer.php'); ?>
