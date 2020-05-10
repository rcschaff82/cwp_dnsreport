<?php


class accountdnscheck {

	private $error = false;

	private $errors = array();

	public function __construct() {}

	public function __destruct() {}

	public function accountdnscheck_error() {
		return $this->error;
	}

	public function getVersion() {
		if(is_file('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/version') === false) {
			$this->error = 'version is missing, please reinstall plugin';
			return false;
		}
		
		$result = @file_get_contents('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/version');
		if($result === false) {
			$this->error = 'version';
			return false;
		}
		
		return trim($result);
	}
///  Use Github url to check version, or forget this horsecrap
public function updateAvailable() {
		$latestVersion = @file_get_contents('https://raw.githubusercontent.com/rcschaff82/cwp_dnsreport/master/dnsreport/version');
		if($latestVersion === false) {
			$this->error = 'Failed to get latest version information from server';
			return false;
		}
		$currentVersion = $this->getVersion();
		if($currentVersion === false) return false;

		if(version_compare($currentVersion,$latestVersion) == -1 ) return true;
		
		return false;
	}


	public function resolverCheck() {
		if(!is_file("/etc/resolv.conf")) {
			$this->error = '/etc/resolv.conf is missing or not accessible';
			return false;
		}
		
		$resolvConf = @file("/etc/resolv.conf");
		if($resolvConf === false) {
			$this->error = 'Failed to read contents of /etc/resolv.conf';
			return false;
		}

		$result = exec("/sbin/ifconfig | /bin/grep inet | /bin/cut -d: -f2 | /bin/awk '{print \$2 }'", $ifconfig_output, $ifconfig_exitstatus);
		if($ifconfig_exitstatus > 1) {
			$this->error = 'execution of /sbin/ifconfig failed';
			return false;
		}
		$interfaceIps = array();
		foreach($ifconfig_output as $line) $interfaceIps[$line] = $line;
		foreach($resolvConf as $line) {
			if(preg_match('/^nameserver (\d*).(\d*).(\d*).(\d*)$/i',$line, $matches)) {
				//var_dump($matches);
				if( array_key_exists($matches[1].".".$matches[2].".".$matches[3].".".$matches[4],$interfaceIps )) {
					return false;
				}
			}
		}
		
		return true;
	}

	public function reports() {
		clearstatcache();

		if(!is_dir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports')) {
			$this->error = 'reports directory is not accessible or does not exist';
			return false;
		}
	
		$reports = array();
		if($dh = opendir('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports')) {
			
			while(($file = readdir($dh)) !== false) {
			if(!is_file('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $file)) continue;
				$tmpstat = stat('/usr/local/cwpsrv/htdocs/resources/admin/modules/dnsreport/reports/' . $file);
				$reports[] = array('filename' => $file, 'date' => $tmpstat['mtime'] );
			}
		}
		
		uasort($reports, function($a, $b) { if($a['date'] == $b['date']) return 0; return ($a['date'] < $b['date']) ? 1 : -1; });

		return $reports;
	}

}
