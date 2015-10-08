<?php

require_once("twitteroauth/autoload.php");
use Abraham\TwitterOAuth\TwitterOAuth;

$consumerKey       = "xxx";
$consumerSecret    = "xxx";
$accessToken       = "xxx";
$accessTokenSecret = "xxx";

$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$key = "xxx";
$count = 10;
$max_read = 10000;

echo "Started.\n";

for($i = 0; $i < $count; ++$i) {
	$options = array("q" => $key, "count" => 100, "result_type" => "recent");

	for($j = 0; $j < $max_read; ++$j) {
		$statuses = $connection->get("search/tweets", $options);

		echo count($statuses->statuses) . " tweets are fined\n";
		foreach ($statuses->statuses as $result) {
//			echo $result->user->screen_name . ": " . $result->text . "\n";
		
			$id = $result->user->id;
			$connection->post("blocks/create", array("user_id" => $id));
		}

		$next_results = $statuses->search_metadata->next_results;
		if(!$next_results) {
			echo "No defined at " . $j+1 . " times.\n";
			break;
		}
		parse_str($next_results, $options);
	}
}

echo "Finished.\n";

?>
