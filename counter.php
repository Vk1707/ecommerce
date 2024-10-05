<?php
$visitor_ip = $_SERVER['REMOTE_ADDR'];
if($visitor_ip!="")
{
	$dt=date("Y-m-d");
	$sql="select count(*) as cnt from visitors 
	   where date(update_time)='$dt' and ip_address='$visitor_ip'";
	$cntR=$db->select_single($sql);
	$cnt=$cntR['cnt'];
	if (is_numeric($cnt) && $cnt > 0)
	{
		//do nothing
	}
	else
	{
		//$location = geoip_record_by_name($visitor_ip);
		//$vcountry = $location['country_name'];
		$data=['ip_address' => $visitor_ip];
		$db->insert('visitors', $data);
		
	}
}


?>