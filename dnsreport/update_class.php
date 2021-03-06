<?php
class gitupdate {
	public $url;
	public $script;
	public $modname;
	public function __construct($user,$script,$modname) {
		global $mysql_conn, $_POST;
		$this->check_include();
		$this->url =  "https://api.github.com/repos/$user/$script/commits";
		$this->script = $script;
		$this->modname = $modname;
		if( mysqli_num_rows(mysqli_query($mysql_conn,"SHOW TABLES LIKE '{$this->script}_settings' ")) == 0 ){		
			$mi_table3= "CREATE TABLE {$this->script}_settings(
			varname VARCHAR(65) NOT NULL UNIQUE,
			varval VARCHAR(65) NOT NULL,
			PRIMARY KEY(`varname`)
			)ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			mysqli_query($mysql_conn, $mi_table3);
		}
		if (isset($_POST['update']) && $_POST['update'] == 'update') {
				$this->doupdate();
		}
	}
	private function check_include() {
		if(!file_exists("/usr/local/cwpsrv/htdocs/admin/admin/pscheck.php")) {
		shell_exec('chattr -i /usr/local/cwpsrv/htdocs/admin/admin/');
			$data = '<?php
function is_process_running($pid)
{
        exec("ps $pid", $ProcessState);
        return(count($ProcessState) >= 2);
}
if (!is_process_running($_GET["pid"])) {
echo "done";
}
?>';
		file_put_contents("/usr/local/cwpsrv/htdocs/admin/admin/pscheck.php",$data);
		}
	}
	private function doupdate() {
	global $_POST;
	$ps = trim(shell_exec("(cd /usr/local/src/{$this->script} && git pull && ./install.sh) > /dev/null 2>&1 & echo $!"));
	$this->setval('sha', $this->readval('newsha'));
	unset($_POST);
	echo <<<EOF
		<div id="updateprog">Update in progress. Please wait.</div>
		<script>
		var intr = setInterval(function() {
    $.get( "pscheck.php", { pid: {$ps} } )
  .done(function( data ) {
	  console.log(data);
		if (data == 'done') {
			clearInterval(intr);
			$("#updateprog").html($("#updateprog").html() + "<br>Update Done!  Please Refresh.");
			document.location.href='index.php?module={$this->modname}';
			}
			else {
			$("#updateprog").html($("#updateprog").html() + '.'); 	
			};
  });
  }, 5000); 

		</script>
		<script>
                if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                }
                </script>
		

EOF;
		unset($_POST);
	}
	private function checkgit() {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->script);
		//curl_setopt($curl, CONNECTTIMEOUT, 1);
		$content = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($content,true);
		if (isset($data[0]['sha'])) {
			return $data[0]['sha'];
		} else {
			var_dump($data);
			return false;
		}
	}
    private function updatemessage() {
	$msg = "<div style='position:absolute; top:80px; z-index:200' class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>";
	$msg .= "<h3>A New Version is available</h3><p>Please follow the directions:<br><code>cd /usr/local/src/$this->script<br>git pull && ./install.sh</code>";
	$msg .= '<h3>A New Version is available</h3><p><form method="post" action="index.php?module='.$this->modname.'" class="inline">
 		<button type="submit" name="update" value="update" class="link-button">Update Now!</button></form>';
	$msg .= "</p></div>";
	return $msg;
	
    }
    private function setval($valname,$valval) {
	global $mysql_conn;
	if (mysqli_query($mysql_conn,"insert into {$this->script}_settings (varname, varval) values ('{$valname}','{$valval}') on duplicate key update varval='{$valval}'") or die(mysqli_error($mysql_conn))) return true;
    }
    private function readval($valname) {
	global $mysql_conn;
	$resp = mysqli_query($mysql_conn,"select varval from {$this->script}_settings where varname='{$valname}'") or die(mysqli_error($mysql_conn));
	if (mysqli_num_rows($resp) > 0) {
		list($var) = mysqli_fetch_row($resp);
		//echo "Variable $var<br>";
		return $var;
	} else {
		return false;
	}
    }
    public function checkupdate($force = "N") {
		// need to check date last checked (varname, varval) $this->script_settings
		if ( ($lastcheck = $this->readval('lastcheck')) && ($sha = $this->readval('sha')) ) {
			//echo "First: $lastcheck<br>";
			if ($force != "N") {
				$newsha = $this->checkgit();
				if ($newsha === false) return false;
				$date = date("Y-m-d H:i:s");
				$this->setval('lastcheck',$date);
				if ($sha != $newsha) {
					echo $this->updatemessage();
				}
				$this->setval('newsha',$newsha);
				return true;
			}
			//echo "Test: $lastcheck $sha";
			$start_date = new DateTime($lastcheck);
			$since_start = $start_date->diff(new DateTime(date("Y-m-d H:i:s")));
			//  Check if we already have an update. 
			if ($this->readval('newsha') != $this->readval('sha')) {
				// Make sure we are setting the latest update. ( Just in case there was more than 1)
				$newsha = $this->checkgit();
				$this->setval('newsha',$newsha);
				echo $this->updatemessage();
				return true;
			}
			if ($since_start->d >= 1) {
				$newsha = $this->checkgit();
				if ($newsha === false) return false;
				$date = date("Y-m-d H:i:s");
                $this->setval('lastcheck',$date);
				if ($sha != $newsha) {
					echo $this->updatemessage();
				}
				$this->setval('newsha',$newsha);
			}
			/*
			 $since_start->days.' days total<br>';
			 $since_start->y.' years<br>';
			 $since_start->m.' months<br>';
			 $since_start->d.' days<br>';
			 $since_start->h.' hours<br>';
			 $since_start->i.' minutes<br>';
			 $since_start->s.' seconds<br>';
			 "LC: " . $lastcheck . "<br>";
			 "Sha: " .$sha;*/
			
		} 
		else {
			// No Response or table not created.  We do the first check and create it.
			$sha = $this->checkgit();
			$date = date("Y-m-d H:i:s");
			$this->setval('lastcheck',$date);
			if ($sha === false) $sha="NA";
			$this->setval('sha',$sha);
		}
		/**/
	}
}
/*To Call
include_once "update_class.php";
$update = new gitupdate('rcschaff82','cwp_2fa','cwp2fa');
$force = (isset($_GET['forceupdate']))?'Y':'N';
$update->checkupdate($force);
*/
?>
