<?php
date_default_timezone_set('Asia/Jakarta');
include 'class_ig.php';
error_reporting(0);

clear();
copycat();
$u = getUsername();
$p = getPassword();
echo PHP_EOL;
//$sleep = getComment('Sleep in seconds: ');
echo '############################################################' . PHP_EOL . PHP_EOL;
$login = login($u, $p);
if ($login['status'] == 'success') {
	
	echo color()["LG"].'[ * ] Login as '.$login['username'].' Success!' . PHP_EOL;
	$data_login = array(
		'username' => $login['username'],
		'csrftoken'	=> $login['csrftoken'],
		'sessionid'	=> $login['sessionid']
	);
	$data_target = findProfile($u, $data_login);
	echo color()["LG"].'[ # ] Name: '.$data_target['fullname'].' | Followers: '.$data_target['followers'] .' | Following: '.$data_target['following'] . PHP_EOL;
	if ($data_target['status'] == 'success') {
			$profile = getFollowing($u , $data_login, $data_target['following'], 1);
			foreach ($profile as $rs) {
				$username = $rs->username;
				$id_user = $rs->id;
				$unfollow = unfollows($id_user, $data_login);
				if ($unfollow['status'] == 'success') {
					echo color()["CY"].'[ > ] '.date('H:i:s').' Unfollow @'.$username.' Success!';
					sleep(rand(70,100));
					echo PHP_EOL;
				}else{
					echo color()["LR"].'[ ! ] '.date('H:i:s').' Unfollow @'.$username.' Failed! Throttled! Resting during 10 minutes before try again.';
					sleep(10*60);		
					echo PHP_EOL;
				}

			}
	}else{

		echo 'Error!';
		echo PHP_EOL;
	}
}else{

		echo color()['LR'].'Error: '.ucfirst($login['details'])."" . PHP_EOL;
}
