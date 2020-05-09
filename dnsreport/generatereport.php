<?php
$nav = 'generatereport';
$reportdir = "/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports";
$reportdate = time();
$reportoptions = array();
$verbose="true";
if(isset($_REQUEST['accountdomains']) && $_REQUEST['accountdomains'] == "yes") $reportoptions["account"] = "SELECT username,domain FROM user";
if(isset($_REQUEST['addondomains']) && $_REQUEST['addondomains'] == "yes") $reportoptions["addon"] = "SELECT user,domain from domains";
if(isset($_REQUEST['subdomains']) && $_REQUEST['subdomains'] == "yes") $reportoptions["sub"] = "SELECT user,domain,subdomain from subdomains";
if(isset($_REQUEST['csv']) && $_REQUEST['csv'] == "yes") $getopts['format']='csv';
if(isset($_REQUEST['html']) && $_REQUEST['html'] == "yes") $getopts['format']='html';
$getopts['filename'] = date("Y-m-d_G-i-s", $reportdate);
include_once('header.php');
?>
<script type="text/javascript">
$(document).ready(function() {
document.getElementById("content").classList.add("sidebar-page");
});
</script>

			<div class="page-header">
				<h1>Generating new Report</h1>
			</div>

			<pre><?php
try {
			$res = mysqli_query($mysql_conn,"SELECT username,ip_address FROM user");
			while ($row = mysqli_fetch_assoc($res)) {
				$accounts[$row['username']] = [ 'domains' => [], 'ip' => $row['ip_address'] ];
				// Check Account Domains
				ksort( $accounts );
			}
			foreach($reportoptions as $k => $v){

				$res = mysqli_query($mysql_conn,$v);
	                        while ($row = mysqli_fetch_row($res)) {
					$sub = ( isset($row[2]) )? "$row[2].":"";
					$accounts[ $row[0] ]['domains'][ $sub.$row[1] ] = [ 'type' => $k, 'status' => 'unknown', 'resolvedto' => 'unknown' ];
                                // Check Account Domains
                        }

			}

		        if($verbose) print ".";
// Check Domains
        if($verbose) print "Checking to see if domains resolve to correct IP...";
        foreach($accounts as $k => $v) {
                foreach($v['domains'] as $kk => $vv) {
                        $result = gethostbyname($kk);
                        if($verbose) print ".";
                        if($result == $kk) {
                                $accounts[$k]['domains'][$kk]['status'] = 'error';
                                $accounts[$k]['domains'][$kk]['resolvedto'] = 'Failed to Resolve';
                        } elseif($result == $v['ip']) {
                                $accounts[$k]['domains'][$kk]['status'] = 'ok';
                                $accounts[$k]['domains'][$kk]['resolvedto'] = $result;
                        } else {
                                $accounts[$k]['domains'][$kk]['status'] = 'error';
                                $accounts[$k]['domains'][$kk]['resolvedto'] = $result;
                        }
                }
	 }
         if($verbose) print "done\n";
// Create Reports Folder
        if(!is_dir('$reportdir')) {
                @mkdir('$reportdir', 0700);
        }

        // Create Report
        if($verbose) print "Writing report...";
        if($getopts['format'] == 'csv') {
$output = "Username,Domain,Type,Account IP,Resolved To,Status";
                foreach($accounts as $k => $v) {
                        foreach($v['domains'] as $kk => $vv) {
                                $output .= "\n" . $k . "," . $kk . "," . $vv['type'] . "," . $v['ip'] . "," . $vv['resolvedto'] . "," . $vv['status'];
                        }
                }
        } else {
                if(!is_dir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/templates_c')) {
                        @mkdir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/templates_c', 0700);
                }
                try {
                        require_once('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/lib/Smarty/Smarty.class.php');
                        $smarty = new Smarty();
                        $smarty->setTemplateDir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/templates');
                        $smarty->setCompileDir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/templates_c');
                        $smarty->assign('reportname', $getopts['filename']);
                        $smarty->assign('reportdate', $reportdate);
                        $smarty->assign('accounts', $accounts);
                        $output = $smarty->fetch('report.tpl');
                } catch(Exception $e) {
                        print "failed, " . $e->getMessage() . "\n";
                        exit(1);
                }
        }
        file_put_contents($reportdir .'/'. $getopts['filename'] . '.' . $getopts['format'], $output, LOCK_EX);
        @touch($reportdir .'/' . $getopts['filename'] . '.' . $getopts['format'], $reportdate);
        if($verbose) print "done\n";
        if($verbose) print "\nReport has been saved to $reportdir/" . $getopts['filename'] . "." . $getopts['format'] . "\n";
        exit(0);
} catch (Exception $e) {
        print "\nAn Exception was caught!  " . $e->getMessage() . "\n";
        exit(1);
}
?>
</pre>
<?php include_once('footer.php'); ?>
