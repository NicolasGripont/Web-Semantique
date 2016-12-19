<?php
// For more docs :
//		https://twitteroauth.com
//		https://dev.twitter.com/rest/public/search

require "../libraries/twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

class Social_networks {
	private $CONSUMER_KEY_TWITTER = "VoxamEtLVhTvTJFPPeLpfaTBl";
	private $CONSUMER_SECRET_TWITTER = "USKvit6FXHX8m44YhqjF6h3CNSYEkdqzQFTz2jZQrN793RYBsj";

	public function __construct() {
    
    }

	private function getAccessTokenTwitter() {
		$oauth = new TwitterOAuth($this->CONSUMER_KEY_TWITTER, $this->CONSUMER_SECRET_TWITTER);
		$accessToken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
		return $accessToken->access_token;
	}

	public function searchNewsOnSocialNetworks($query, $searchOnTwitter) {
		if($searchOnTwitter) {
			$twitter = new TwitterOAuth($this->CONSUMER_KEY_TWITTER, $this->CONSUMER_SECRET_TWITTER, null, $this->getAccessTokenTwitter());
			$tweets = $twitter->get('search/tweets', [
			    'q' => $query,
			    'lang' => 'en',
			    'result_type' => 'recent',
			    'count' => 20
			]);
			$res["twitter"]["query"] = $query;
			foreach($tweets->statuses as $st) {
				//print_r($st);
				$newSt["id"] = $st->id;
				$newSt["created_at"] = $st->created_at;
				$newSt["text"] = $st->text;
				$newSt["hashtags"] = $st->entities->hashtags;
				//$newSt["urls"] = $st->url["urls"];
				//$newSt["img"] = "";
				$newSt["username_name"] = $st->user->screen_name;
				$newSt["username_real_name"] = $st->user->name;
				$newSt["username_photo_profil"] = "";
				$results[] = $newSt;
			}
			$res["twitter"]["results"] = $results;

		}
		return $res;
	}

	public function setConsumerTwitter($CONSUMER_KEY_TWITTER, $CONSUMER_SECRET_TWITTER) {
		$this->CONSUMER_KEY_TWITTER = $CONSUMER_KEY_TWITTER;
		$this->CONSUMER_SECRET_TWITTER = $CONSUMER_SECRET_TWITTER;
	}
}

//$sn = new social_networks();
//print_r($sn->searchNewsOnSocialNetworks("cognac", true));

/*
Array ( [twitter] => Array ( [query] => cognac [results] => Array ( [0] => stdClass Object ( [created_at] => Mon Dec 19 14:23:26 +0000 2016 [id] => 810853050894131200 [id_str] => 810853050894131200 [text] => @31DOVER love Hennessy Cognac ✅ [truncated] => [entities] => stdClass Object ( [hashtags] => Array ( ) [symbols] => Array ( ) [user_mentions] => Array ( [0] => stdClass Object ( [screen_name] => 31DOVER [name] => 31DOVER.com [id] => 590064536 [id_str] => 590064536 [indices] => Array ( [0] => 0 [1] => 8 ) ) ) [urls] => Array ( ) ) [metadata] => stdClass Object ( [iso_language_code] => en [result_type] => recent ) [source] => Twitter for iPad [in_reply_to_status_id] => 810796961381097473 [in_reply_to_status_id_str] => 810796961381097473 [in_reply_to_user_id] => 590064536 [in_reply_to_user_id_str] => 590064536 [in_reply_to_screen_name] => 31DOVER [user] => stdClass Object ( [id] => 35180560 [id_str] => 35180560 [name] => Michael Doherty [screen_name] => Mike_IChing [location] => UK [description] => Scientific bridge maker. Bringing science & metaphysics together. Retired teacher of maths & physics. #Writer on the I CHING oracle #Author #editor [url] => https://t.co/9IKz5basGl [entities] => stdClass Object ( [url] => stdClass Object ( [urls] => Array ( [0] => stdClass Object ( [url] => https://t.co/9IKz5basGl [expanded_url] => http://www.themichaelfiles.com [display_url] => themichaelfiles.com [indices] => Array ( [0] => 0 [1] => 23 ) ) ) ) [description] => stdClass Object ( [urls] => Array ( ) ) ) [protected] => [followers_count] => 317 [friends_count] => 1094 [listed_count] => 27 [created_at] => Sat Apr 25 09:05:51 +0000 2009 [favourites_count] => 748 [utc_offset] => 0 [time_zone] => London [geo_enabled] => [verified] => [statuses_count] => 3229 [lang] => en [contributors_enabled] => [is_translator] => [is_translation_enabled] => [profile_background_color] => 0099B9 [profile_background_image_url] => http://abs.twimg.com/images/themes/theme4/bg.gif [profile_background_image_url_https] => https://abs.twimg.com/images/themes/theme4/bg.gif [profile_background_tile] => [profile_image_url] => http://pbs.twimg.com/profile_images/1984098618/mike_normal.jpg [profile_image_url_https] => https://pbs.twimg.com/profile_images/1984098618/mike_normal.jpg [profile_banner_url] => https://pbs.twimg.com/profile_banners/35180560/1469786708 [profile_link_color] => 0099B9 [profile_sidebar_border_color] => 5ED4DC [profile_sidebar_fill_color] => 95E8EC [profile_text_color] => 3C3940 [profile_use_background_image] => 1 [has_extended_profile] => 1 [default_profile] => [default_profile_image] => [following] => [follow_request_sent] => [notifications] => [translator_type] => none ) [geo] => [coordinates] => [place] => [contributors] => [is_quote_status] => [retweet_count] => 0 [favorite_count] => 0 [favorited] => [retweeted] => [lang] => en ) ) ) )

*/
?>