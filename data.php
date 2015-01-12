<?php

include "config.php";
include "bootstrap.php";

if ( getLastUpdatedTime() < time() - 60 * UPDATE_FREQUENCY ) {
	updateTweets();
	updateInstagramPhotos();

	$currentTime = time();
	$query       = $db->prepare( "UPDATE metadata SET value=:current_time WHERE parameter='social_last_updated'" );
	$query->bindParam( ":current_time", $currentTime );
	$query->execute();
}

echo $twig->render(
	'data.twig',
	[
		'tweets'          => getTweets(),
		'slides'          => getSlides(),
		'instagramPhotos' => getInstagramPhotos()
	]
);

function updateTweets() {

	global $db;

	$client = new Freebird\Services\freebird\Client();
	$client->init_bearer_token( TWITTER_API_KEY, TWITTER_API_SECRET );
	$response = $client->api_request( 'search/tweets.json',
		[
			'q'           => urlencode( TWITTER_SEARCH_TERM ),
			'count'       => 20,
			'result_type' => 'recent'
		]
	);
	$tweets   = json_decode( $response );

	$db->exec( "TRUNCATE TABLE twitter" );

	foreach ( $tweets->statuses as $tweet ) {

		//Is the tweet a retweet?
		if ( property_exists( $tweet, "retweeted_status" ) ) {

			$is_retweet        = 1;
			$text              = $tweet->retweeted_status->text;
			$original_poster   = $tweet->retweeted_status->user->screen_name;
			$profile_image_url = $tweet->retweeted_status->user->profile_image_url;

		} else {

			$is_retweet        = 0;
			$text              = $tweet->text;
			$original_poster   = null;
			$profile_image_url = $tweet->user->profile_image_url;
		}

		//Expand URLs
		if ( ! empty( $tweet->entities->urls ) ) {

			foreach ( $tweet->entities->urls as $url ) {

				$text = str_replace( $url->url, $url->display_url, $text );

			}
		}

		$id          = $tweet->id_str;
		$created_at  = strtotime( $tweet->created_at );
		$name        = $tweet->user->name;
		$screen_name = $tweet->user->screen_name;

		$media    = null;
		$filePath = null;

		//Convert Unicode emoji to short names
		$text = Emojione\Emojione::toShort( $text );

		//If tweet has a photo, put it in the database too
		if ( property_exists( $tweet->entities, "media" ) ) {
			if ( $tweet->entities->media[0]->type === "photo" ) {
				$media = $tweet->entities->media[0]->media_url;

				$fileName = basename( $media );

				$filePath = "media/twitter/" . $fileName;

				if ( ! file_exists( $filePath ) ) {

					$image = new Imagick( $media );

					$image->setImageBackgroundColor( "black" );

					$image->ThumbnailImage( 992, 444, true, true );

					$image->writeImage( $filePath );

				}

				//Remove photo URL from the text
				$text = str_replace( $tweet->entities->media[0]->url, null, $text );

			}
		}

		//Make the avatar bigger
		$profile_image_url = str_replace( "_normal", "_bigger", $profile_image_url );

		$query = $db->prepare( "REPLACE INTO twitter(id, text, created_at, name, screen_name, profile_image_url, media, is_retweet, original_poster) VALUES (:id, :text, :created_at, :name, :screen_name, :profile_image_url, :media, :is_retweet, :original_poster)" );

		$query->bindParam( ':id', $id );
		$query->bindParam( ':text', $text );
		$query->bindParam( ':created_at', $created_at );
		$query->bindParam( ':name', $name );
		$query->bindParam( ':screen_name', $screen_name );
		$query->bindParam( ':profile_image_url', $profile_image_url );
		$query->bindParam( ':media', $filePath );
		$query->bindParam( ':is_retweet', $is_retweet );
		$query->bindParam( ':original_poster', $original_poster );

		$query->execute();

	}

}

function insertInstagramPhotosToDatabase( $json ) {

	global $db;

	$db->exec( "TRUNCATE TABLE instagram" );

	foreach ( $json->data as $photo ) {
		$id        = $photo->id;
		$username  = $photo->user->username;
		$image     = $photo->images->standard_resolution->url;
		$likes     = $photo->likes->count;
		$timestamp = $photo->created_time;
		$fileName  = basename( $image );
		$filePath  = "media/instagram/" . $fileName;

		if ( ! file_exists( $filePath ) ) {
			$image = new Imagick( $image );
			$image->ThumbnailImage( 528, 528 );
			$image->writeImage( $filePath );
		}

		$query = $db->prepare( "REPLACE INTO instagram(id, username, image_url, likes, timestamp) VALUES (:id, :username, :image_url, :likes, :timestamp)" );

		$query->bindParam( ':id', $id );
		$query->bindParam( ':username', $username );
		$query->bindParam( ':image_url', $filePath );
		$query->bindParam( ':likes', $likes );
		$query->bindParam( ':timestamp', $timestamp );

		$query->execute();
	}
}

function updateInstagramPhotos() {
	$response = file_get_contents( "https://api.instagram.com/v1/tags/" . INSTAGRAM_SEARCH_TAG . "/media/recent?client_id=" . INSTAGRAM_CLIENT_ID );
	$photos   = json_decode( $response );
	insertInstagramPhotosToDatabase( $photos );
}

function getInstagramPhotos() {

	global $db;

	$query  = $db->query( "SELECT username, image_url FROM instagram ORDER BY timestamp DESC LIMIT 16" );
	$photos = $query->fetchAll( PDO::FETCH_ASSOC );

	foreach ( $photos as $key => &$photo ) {
		if ( ( $key + 1 ) % 2 == 0 ) {
			$photo['class'] = 'photo-last';
		} else {
			$photo['class'] = 'photo-first';
		}
	}

	return $photos;
}

function getSlides() {

	global $db;

	$query  = $db->query( "SELECT * FROM slide WHERE visibility = 1" );
	$slides = $query->fetchAll( PDO::FETCH_ASSOC );

	return $slides;
}

function emojifyTweets( $tweets ) {

	foreach ( $tweets as &$tweet ) {
		$tweet["text"] = Emojione\Emojione::shortNametoImage( $tweet["text"] );
	}

	return $tweets;
}

function getLastUpdatedTime() {

	global $db;

	$query       = $db->query( "SELECT value FROM metadata WHERE parameter = 'social_last_updated'" );
	$lastUpdated = $query->fetch( PDO::FETCH_ASSOC );

	return $lastUpdated['value'];
}

function getTweets() {

	global $db;

	$query  = $db->query( "SELECT * FROM twitter ORDER BY created_at DESC LIMIT 15" );
	$tweets = $query->fetchAll( PDO::FETCH_ASSOC );

	//Convert emoji short names to images
	$tweets = emojifyTweets( $tweets );

	return array_reverse( $tweets );
}