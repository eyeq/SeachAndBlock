<?php

require_once("twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey       = "xxx";
$consumerSecret    = "xxx";
$accessToken       = "xxx";
$accessTokenSecret = "xxx";

$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

define('KEYS_FILE', './keys.txt');

$keys = array();
$max_read = 10000;

$file = fopen(KEYS_FILE, "r");
while(!feof($file)) {
	$keys[] = rtrim(fgets($file));
}
fclose($file);

$i = 0;
foreach($keys as $key) {
	print("key is " . $key . ".\n");
	print("Started.\n");
	
	$options = array("q" => $key, "count" => 100, "result_type" => "recent");

	for($j = 0; $j < $max_read; ++$j) {
		$statuses = $connection->get("search/tweets", $options);

		if(!$statuses->statuses) {
			print("No defined at " . $j+1 . " times.\n");
			break;
		}
		print(count($statuses->statuses) . " tweets are fined\n");
		foreach ($statuses->statuses as $result) {
//				print($result->user->screen_name . ": " . $result->text . "\n");
		
			$id = $result->user->id;
			$connection->post("blocks/create", array("user_id" => $id));
		}

		$next_results = $statuses->search_metadata->next_results;
		if(!$next_results) {
			print("No defined at " . $j+1 . " times.\n");
			break;
		}
		parse_str($next_results, $options);
	}
}

print("Finished.\n");

?>
