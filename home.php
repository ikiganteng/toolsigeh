<?php
include 'class_ig.php';
error_reporting(0);
clear();
copycat();
$u = getUsername();
$p = getPassword();
$username_target = getUsername('Enter Target Username: ');
// $u = 'indiramayasari7'; $p = 'Anonymous1704!'; $text = 'Hi!';
//ikiganteng
echo '############################################################' . PHP_EOL . PHP_EOL;
$login = login($u, $p);
if ($login['status'] == 'success') {
	echo color()["LG"].'[ * ] Login as '.$login['username'].' Success!' . PHP_EOL;
	$data_login = array('username' => $login['username'],'csrftoken'	=> $login['csrftoken'],'sessionid'	=> $login['sessionid']);
	$iki = json_encode($data_login);
	$h=fopen("instagram.txt","a");
    fwrite($h,$iki."\n");
    fclose($h);
	$profile = getHome($data_login);
	$data_array = json_decode($profile);
	$result = $data_array->user->edge_web_feed_timeline;
	foreach ($result->edges as $items) {
	$id = $items->node->id;
	$username = $items->node->owner->username;
	$like = like($id, $data_login);
	if ($like['status'] == 'error') {
	echo '[+] Username: '.$username.' | Error Like' . PHP_EOL;
	}else{
	echo '[+] Username: '.$username.' |  Like Success'. PHP_EOL;;
	}
	}
} else echo json_encode($login);
