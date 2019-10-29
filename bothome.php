<?php
include 'class_ig.php';

			$data_login = array(
		'username' => 'username lu',
		'csrftoken'	=> 'csrftoken lu',
		'sessionid'	=> 'sessionid lu'
	);
				while(true){
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
				echo '[+] Tidur selama 120 detik [+]'. PHP_EOL;;
				sleep(120);
                }
