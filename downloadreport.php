<?php

if(isset($_REQUEST['report'])) {
	if(preg_match('/^[a-zA-Z0-9\-_\.]*$/', $_REQUEST['report'])) {
		if(is_file('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['report'])) {
			// CSV download
			if(preg_match('/\.csv$/', $_REQUEST['report'])) {
				error_reporting(0);
				//ob_start();
				header('Content-type: application/octet-stream');
				header("Pragma: public");
				header("Expires: 0");
				header('Cache-Control: must-revalidate');
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename=' . $_REQUEST['report']);
				header('Content-Length: ' . filesize('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['report']));
				//ob_clean();
				//ob_end_flush();
				readfile('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $_REQUEST['report']);
			}
		}
	}
}
echo "Hello World";
