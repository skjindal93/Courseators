<?php
include('header.php');
$url = "http://internal.iitd.ernet.in/search/ldap1.php?uid=cs5110295";
$content = file_get_contents($url);
//echo $content;
$GLOBALS['a']=array();
$name = "";
$studentid = "";
$year = "";
$head = "";
$img = "";
$arr = preg_split("/<br>/", $content);
$first = strstr($arr[0],"<p>",true);

if (strlen($first)==0){
	$img = strstr($arr[0],"<img");
	if(preg_match_all('/\>(.*?)\</',$arr[0],$match)){
		$first = $match[1][0];	
	}
	
}

if(preg_match_all('/\>(.*?)\</',$first,$match)) {
	$info = $match[1][0];
	$name = strstr($info,"(",true);
	$name = trim($name);
	if(preg_match_all('/\((.*?)\)/',$info,$match)) { 
		$studentid = $match[1][0];
		$studentid = trim($studentid);
		$year = substr($studentid,0,4);
	}
}
else {
	$name = strstr($first,"(",true);
	$name = trim($name);
	if(preg_match_all('/\((.*?)\)/',$first,$match)) { 
		$studentid = $match[1][0];
		$studentid = trim($studentid);
		$year = substr($studentid,0,4);
	}	
}

//echo $img."<br> ".$name." ".$studentid." ".$year."<br>";


for ($x = 0;$x<10;$x++){
	$GLOBALS['a'][$x] = '';
}

for ($i=0;$i<sizeof($arr);$i++){
	parse($arr[$i]);
}
$GLOBALS['a'][10] = $studentid;
$GLOBALS['a'][11] = $name;
$GLOBALS['a'][12] = $year;
$GLOBALS['a'][13] = $img;
var_dump($GLOBALS['a']);
//echo "insert into student values ('".$GLOBALS['a'][10]."','".$GLOBALS['a'][10]."','".$GLOBALS['a'][11]."','".$GLOBALS['a'][13]."',".$GLOBALS['a'][12].",'".$GLOBALS['a'][8]."','".$GLOBALS['a'][0]."','".$GLOBALS['a'][1]."','".$GLOBALS['a'][2]."','".$GLOBALS['a'][9]."','".$GLOBALS['a'][3]."','".$GLOBALS['a'][4]."','".$GLOBALS['a'][5]."','".$GLOBALS['a'][6]."');";
pg_query("insert into student values ('".$GLOBALS['a'][10]."','".$GLOBALS['a'][10]."','".$GLOBALS['a'][11]."','".$GLOBALS['a'][13]."',".$GLOBALS['a'][12].",'".pg_escape_string($GLOBALS['a'][8])."','".$GLOBALS['a'][0]."','".$GLOBALS['a'][1]."','".$GLOBALS['a'][2]."','".$GLOBALS['a'][9]."','".$GLOBALS['a'][3]."','".$GLOBALS['a'][4]."','".$GLOBALS['a'][5]."','".$GLOBALS['a'][6]."','".pg_escape_string($GLOBALS['a'][7])."');");

function parse($string){
	if (preg_match("/Advisor/",$string)){
		$advisor = strstr($string,"Advisor");
		$advisor = preg_split("/ : /",$advisor)[1];
		if(preg_match_all('/uid=(.*?)target/',$advisor,$match)) {     
	        $advisor = $match[1][0];
	        $GLOBALS['a'][0]=$advisor;
		}
	}

	if (preg_match("/Telephone Number/",$string)){
		$telno = strstr($string,"Telephone Number");
		$telno = preg_split("/ : /",$telno)[1];
		$GLOBALS['a'][1] = $telno;
	}
	if (preg_match("/Mobile/",$string)){
		$mobileno = strstr($string,"Mobile");
		$mobileno = preg_split("/ : /",$mobileno)[1];
		$GLOBALS['a'][2] = $mobileno;
	}
	if (preg_match("/Postal Address/",$string)){
		$postaddr = strstr($string,"Postal Address");
		$postaddr = preg_split("/ : /",$postaddr)[1];
		$GLOBALS['a'][6] = $postaddr;
	}
	else if (preg_match("/Address/",$string)){
		$addr = strstr($string,"Address");
		$addr = preg_split("/ : /",$addr)[1];
		$GLOBALS['a'][3] = $addr;
	}

	if (preg_match("/Home Phone/",$string)){
		$homephone = strstr($string,"Home Phone");
		$homephone = preg_split("/ : /",$homephone)[1];
		$GLOBALS['a'][4] = $homephone;
	}
	if (preg_match("/Room Number/",$string)){
		$roomaddr = strstr($string,"Room Number");
		$roomaddr = preg_split("/ : /",$roomaddr)[1];
		$GLOBALS['a'][5] = $roomaddr;
	}
	
	if (preg_match("/URL/",$string)){
		$url = strstr($string,"URL");
		$url = preg_split("/ : /",$url)[1];
		$GLOBALS['a'][7] =  $url;
	}
	if (preg_match("/E-Mail/",$string)){
		$email = strstr($string,"E-Mail");
		$email = preg_split("/ : /",$email)[1];
		$GLOBALS['a'][8] = $email;
	}
	if (preg_match("/Fax/",$string)){
		$fax = strstr($string,"Fax");
		$fax = preg_split("/ : /",$fax)[1];
		$GLOBALS['a'][9] = $fax;
	}
	
}
?>