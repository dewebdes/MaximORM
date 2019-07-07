<?php

function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}

class stdObject {
    public function __construct(array $arguments = array()) {
        if (!empty($arguments)) {
            foreach ($arguments as $property => $argument) {
                $this->{$property} = $argument;
            }
        }
    }

    public function __call($method, $arguments) {
        $arguments = array_merge(array("stdObject" => $this), $arguments); // Note: method argument 0 will always referred to the main class ($this).
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $arguments);
        } else {
            throw new Exception("Fatal error: Call to undefined method stdObject::{$method}()");
        }
    }
}

class dpost
{
public $id;
public $part;
public $tit;
public $img1;
public $stx;
public $dat;
public $riter;
}
class dads
{
public $id;
public $part;
public $tit;
public $img1;
public $link;
public $expir;
}
class dconf
{
public $id;
public $part;
public $tit;
public $val;
}

?>

<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);


global $wpdb;
$wpdb->show_errors = true;

$cmd=$_REQUEST['cmd'];
$api=$_REQUEST['api'];
$rtv = "";
$dnow = (new \DateTime())->format('Y-m-d H:i:s');

switch ($api) {

	case "dwp":
		//switch_to_blog( $dblog );
		//switch ($cmd) {

		//}

	break;	
	case "sql":

		switch ($cmd) {
			case "getdivelist":
				//die("get device list...");
				//$us = $_REQUEST["dus"];
				//$ps = $_REQUEST["dps"];
				$project = $_REQUEST["project"];
				//$device = $_REQUEST["device"];

				//$usid = "0";
				$query = "SELECT * FROM wp_cu_device WHERE prj='" . $project . "';";
				//echo $query;
				$results = $wpdb->get_results( $query, OBJECT );
				//echo "<br>" . $results[0];
				$rtv = json_encode($results);
				
				//die($rtv);

				//$qobj = $wpdb->get_results("SELECT * FROM wp_cu_device WHERE prj='" . $project . "';");//" AND devid='" . $device . 
				//$rtv = json_encode($qobj);


			break;
			case "regme":
				$name = $_REQUEST["name"];
				$us = $_REQUEST["us"];
				$ps = $_REQUEST["ps"];
				$autyp = $_REQUEST["autyp"];
				$project = $_REQUEST["project"];
				$device = $_REQUEST["device"];

				$usid = "0";
				$qobj = $wpdb->get_results("SELECT * FROM wp_cu_user WHERE project='" . $project . "' AND duser='" . $us . "' AND dpass='" . $ps . "';");//" AND devid='" . $device . "';");

					foreach( $qobj as $key => $row) {
						$usid = $row->id;
					}

				if($usid == "0"){
					$query="insert into wp_cu_user (sid,dmail,dtel,dfullname,lastlogin,authtyp,project,dpass,duser,devid,ownerTbl,ownerRec,isSingle,isExist,isNew) values(6,#sq##sq#,#sq##sq#,#sq#" . $name . "#sq#,#sq#" . $dnow . "#sq#,#sq#" .$autyp . "#sq#,#sq#" . $project . "#sq#,#sq#" . $ps . "#sq#,#sq#" . $us . "#sq#,#sq#" . $device . "#sq#,0,0,1,0,0)";

					$query=str_replace("#sq#","'",$query);
					$results = $wpdb->query( 
							        	$query
									);

					$qobj = $wpdb->get_results("SELECT * FROM wp_cu_user WHERE project='" . $project . "' AND duser='" . $us . "' AND dpass='" . $ps . "';");//" AND devid='" . $device . "';");

					foreach( $qobj as $key => $row) {
						$usid = $row->id;
					}
				}

				$rtv = $usid;


			break;
			case "select":
				//tablename f1,f2 | v1,v2 | where
				$tablename = $_REQUEST["tablename"];
				$where = $_REQUEST["where"];
				$fls = $_REQUEST["flst"];
				//var $vls = $_REQUEST["vls"];
				$tablename = $_REQUEST["tablename"];
				$device = $_REQUEST["device"];

				//$skid = $_REQUEST["skid"];
				$project = $_REQUEST["project"];
				//$ip = $_REQUEST["ip"];
				//$sys = $_REQUEST["sys"];
				//$tkn = $_REQUEST["tkn"];


				$retid = "0";

$query="SELECT " . $fls . " FROM wp_" . $tablename . " WHERE " . $where . ";";

					$query=str_replace("#sq#","'",$query);
					//$query=str_replace("{skid}",$skid,$query);
					$query=str_replace("{project}",$project,$query);
					//$query=str_replace("{ip}",$ip,$query);
					//$query=str_replace("{sys}",$sys,$query);
					//$query=str_replace("{tkn}",$tkn,$query);
					//$device
					$query=str_replace("{device}",$device,$query);


				$qobj = $wpdb->get_results($query);

				
				

				$rtv = json_encode($qobj);


			break;
			
			case "runquery":
				//insert into wp_sam_client (sid,wifipass,wifius,hubip,devid,ownerTbl,ownerRec,isSingle,isExist,isNew) values(6,#sq#pass#sq#,#sq#us#sq#,#sq#hub#sq#,#sq#devid#sq#,0,0,1,0,0)

				//skid: socketId, project: socket.project, ip: socket.ip, sys: socket.sys, tkn: socket.tkn, query: "DetectDevice"
				$skid = $_REQUEST["skid"];
				$project = $_REQUEST["project"];
				$ip = $_REQUEST["ip"];
				$sys = $_REQUEST["sys"];
				$tkn = $_REQUEST["tkn"];
				$query = $_REQUEST["query"];
				$entry = $_REQUEST["entry"];

				if(!IsNullOrEmptyString($entry)){

				}

				/*switch ($query) {
					case 'value':
						# code...
						break;
					
					default:
						# code...
						break;
				}*/

			break;
			case "getDeviceIdFromIp":
			//echo "string";//('xxxx');
				$ip = $_REQUEST["dip"];
				$log = $_REQUEST["log"];

				$deviceid = "0";
				

				if (strpos($log, '|') !== false) {
					$pieces = explode("|", $log);
					$dlog2 = $pieces[1] . "|" . $pieces[2];
					$query = "SELECT * FROM wp_sam_logs WHERE dentry like '%" . $dlog2 . "%';";
					//die($query);
				    $qobj = $wpdb->get_results($query);
					
						foreach( $qobj as $key => $row) {
							//try{
								//if(!IsNullOrEmptyString($row->deviceid)){
									$deviceid = $row->deviceid;
									if((int)$deviceid>0){
										break;
									}
							//	}
							//}catch(Exception $ex){
							//	$deviceid = "0";
							//}
						}
					
				}

				//if((IsNullOrEmptyString($deviceid) == true)){$deviceid = "0";}

				//die('xx = ' . sieOf(trim(($deviceid))));

				if(((int)$deviceid <= 0)){
					$qobj = $wpdb->get_results("SELECT * FROM wp_cu_device WHERE lastip='" . $ip . "';");
					foreach( $qobj as $key => $row) {
						$deviceid = $row->id;
						break;
					}
				}
				$rtv = $deviceid;

			break;
			case "DetectDevice":
				$skid = $_REQUEST["skid"];
				$project = $_REQUEST["project"];
				$ip = $_REQUEST["ip"];
				$sys = $_REQUEST["sys"];
				$tkn = $_REQUEST["tkn"];

				$deviceid = "0";
				$qobj = $wpdb->get_results("SELECT * FROM wp_cu_device WHERE lastip='" . $ip . "' OR dtkn='" . $tkn . "';");
				foreach( $qobj as $key => $row) {
					$deviceid = $row->id;
				}
				if($deviceid == 0){
					$query="insert into wp_cu_device (sid,lastip,dsys,prj,dtkn,ownerTbl,ownerRec,isSingle,isExist,isNew) values(6,#sq#" . $ip . "#sq#,#sq#" . $sys . "#sq#,#sq#" . $project . "#sq#,#sq#" . $tkn . "#sq#,0,0,1,0,0)";

					$query=str_replace("#sq#","'",$query);
					$results = $wpdb->query( 
							        	$query
									);
					$qobj = $wpdb->get_results("SELECT * FROM wp_cu_device WHERE lastip='" . $ip . "' OR dtkn='" . $tkn . "';");
					foreach( $qobj as $key => $row) {
						$deviceid = $row->id;
					}
				}

				$rtv = $deviceid;
				//console.log()

			break;
			case "insertorupdate":
				//tablename f1,f2 | v1,v2 | where
				$tablename = $_REQUEST["tablename"];
				$where = $_REQUEST["where"];
				$fls = $_REQUEST["flst"];
				$vls = $_REQUEST["vls"];
				$tablename = $_REQUEST["tablename"];
				$device = $_REQUEST["device"];

				//$skid = $_REQUEST["skid"];
				$project = $_REQUEST["project"];
				//$ip = $_REQUEST["ip"];
				//$sys = $_REQUEST["sys"];
				//$tkn = $_REQUEST["tkn"];


				$retid = "0";
				$qobj = $wpdb->get_results("SELECT * FROM wp_" . $tablename . " WHERE " . $where . ";");
				foreach( $qobj as $key => $row) {
					$retid = $row->id;
				}
				if($retid == 0){
					$query="insert into wp_" . $tablename . " (sid," . $fls . ",ownerTbl,ownerRec,isSingle,isExist,isNew) values(6," .$vls . ",0,0,1,0,0)";

					$query=str_replace("#sq#","'",$query);
					//$query=str_replace("{skid}",$skid,$query);
					$query=str_replace("{project}",$project,$query);
					//$query=str_replace("{ip}",$ip,$query);
					//$query=str_replace("{sys}",$sys,$query);
					//$query=str_replace("{tkn}",$tkn,$query);
					//$device
					$query=str_replace("{device}",$device,$query);

//echo $query;

					$results = $wpdb->query( 
							        	$query
									);
					$qobj = $wpdb->get_results("SELECT * FROM wp_" . $tablename . " WHERE " . $where . ";");
					foreach( $qobj as $key => $row) {
						$retid = $row->id;
					}

				}else{

					$flar = explode(",", $fls);
					$vlar = explode(",", $vls);

					$query="update wp_" . $tablename . " set ";

					for($iv=0;$iv<=sizeof($flar)-1;$iv++){
						$query=$query . $flar[$iv] . "=" . $vlar[$iv];
						if($iv<sizeof($flar)-1){
							$query=$query . ",";
						}
					}

					$query=$query . " WHERE " . $where . ";";
					$query=str_replace("#sq#","'",$query);
					$query=str_replace("#sq#","'",$query);
					//$query=str_replace("{skid}",$skid,$query);
					$query=str_replace("{project}",$project,$query);
					//$query=str_replace("{ip}",$ip,$query);
					//$query=str_replace("{sys}",$sys,$query);
					//$query=str_replace("{tkn}",$tkn,$query);

					$results = $wpdb->query( 
							        	$query
									);
					$qobj = $wpdb->get_results("SELECT * FROM wp_" . $tablename . " WHERE " . $where . ";");
					foreach( $qobj as $key => $row) {
						$retid = $row->id;
					}

				}

				$rtv = $retid;


			break;
			case "loginme":
				$us = $_REQUEST["dus"];
				$ps = $_REQUEST["dps"];
				$project = $_REQUEST["project"];
				$device = $_REQUEST["device"];

				$usid = "0";
				$qobj = $wpdb->get_results("SELECT * FROM wp_cu_user WHERE project='" . $project . "' AND duser='" . $us . "' AND dpass='" . $ps . "';");//" AND devid='" . $device . "';");

					foreach( $qobj as $key => $row) {
						$usid = $row->id;
					}

				$rtv = $usid;


			break;
			
		}
		break;
	}
echo $rtv;
?>