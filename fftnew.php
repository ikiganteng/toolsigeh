<?php
//ikiganteng
include 'class_ig.php';
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

clear();
copycat();
$u = getUsername();
$p = getPassword();
echo PHP_EOL;
$text_comment = getComment('Enter your file comment here: ');
$getfile = file_get_contents($text_comment);
$x = explode("|", $getfile);
$c = count($x) -1;
echo 'Follow User? (enter Y OR y for YES, Enter any key for NO) : ';
$f = trim(fgets(STDIN));
$username_target = getUsername('Enter Target Username: ');
$jumlah = getUsername('Enter Number to Follow: ');
// $u = 'indiramayasari7'; $p = 'Anonymous1704!'; $text = 'Hi!';
//ikiganteng
echo '############################################################' . PHP_EOL . PHP_EOL;
$login = login($u, $p);
if ($login['status'] == 'success') {
	
	echo color()["LG"].'[ * ] Login as '.$login['username'].' Success!' . PHP_EOL;
	$data_login = array(
		'username' => $login['username'],
		'csrftoken'	=> $login['csrftoken'],
		'sessionid'	=> $login['sessionid']
	);
	
	$data_target = findProfile($username_target, $data_login);
	
	if ($data_target['status'] == 'success') {
		
		echo color()['LC'].'[ * ] Target: '.$data_target['username'].' | Name: '.$data_target['fullname'].'  | Followers: '.$data_target['followers'].' | Following: '.$data_target['following'].' | Post: '.$data_target['post'].' [ * ] '.PHP_EOL.PHP_EOL;

		$cmt = 0;

			$profile = getFollowers($data_target['id'] , $data_login, $jumlah, 1);
			foreach ($profile as $rs) {

				$id_user = $rs->id;
				$username = $rs->username;
				$is_private = $rs->is_private;
				if (!($is_private)) {
				echo color()["LG"].'[+] '.date('H:i:s').' Username: '.$username.' | ';

				
				$post = getPost($username, $data_login);
				if ($post['status'] == 'error') {
					
					echo color()["LR"].'Error: '.ucfirst($post['details']).' | ' .PHP_EOL;
						$sleep = 10;
				}else{

					$data_follow = ($f == 'y' OR 'y') ? follow($username, $data_login) : '' ;
					if ($data_follow['status'] == 'success') {

						echo color()["LG"]."Follow Success | ";
						$sleep = 300;
						sleep(30);
						$id_post = $post['id'];

						$like = like($id_post, $data_login);
						if ($like['status'] == 'error') {
							
							echo color()["LR"]."Error Like :( | Throttled! Resting during 10 minutes before try again." . PHP_EOL;
						sleep(10*60);		
						}else{
							echo color()["LG"]."Like Success | ";
							sleep(30);
							shuffle($x);
							$text = $x[0];
							$comment = comment($id_post, $data_login, $text);

							if ($comment['status'] == 'success') {
								
								$cmt = $cmt+1;
								echo color()["LG"]."[ $cmt ] Comment Success: " . color()['MG'].$comment['text'] . color()['CY']." ".PHP_EOL;
								$sleep = $sleep + 300;
								sleep(30);
							}else{
								echo color()["LR"]."Error comment :( Throttled! Resting during 10 minutes before try again.| ";
								sleep(10*60);		
								$unfollow = unfollow($username,$data_login);
								if ($unfollow['status'] == 'success') {
									
									echo color()['LG']."Unfollow " . $username." Success | " . PHP_EOL;
								}else{

									echo color()['LR']."Unfollow " . $username." Failed | " . PHP_EOL;
								}
							}
						}
					}else{

						echo color()["LR"]."Follow Failed: ".ucfirst($data_follow['details'])."" . PHP_EOL;
                        $sleep = $data_follow['sleep'];		
                        }
				}

				sleep($sleep);
			}
			}
		
	}else{

		echo color()['LR'].'Error: '.ucfirst($data_target['details'])."" . PHP_EOL;
	}
}else{

		echo color()['LR'].'Error: '.ucfirst($login['details'])."" . PHP_EOL;
	}
