<?php
$nav = 'managereports';

include "header.php";
if(isset($_REQUEST['report'])) {
	if(preg_match('/^[a-zA-Z0-9\-_\.]*$/', $_REQUEST['report'])) {
		if(is_file('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['report'])) {
			readfile('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['report']);
		}
	}
}
