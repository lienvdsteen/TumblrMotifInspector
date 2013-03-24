<?php

class TumblrCalculator {

	public static function createStats($posts) {
		if (!is_array($posts) || empty($posts)) {
			return false;
		}

		$types = array();
		$tags = array();
		$postsByDate = array(); //sorted per year, per month

		foreach ($posts as $post) {
			if (!isset($types[$post['type']])) {
				$types[$post['type']] = 1;
			}
			else {
				$types[$post['type']]++;
			}
			if (isset($post['tags']) && !empty($post['tags'])) {
				foreach ($post['tags'] as $tag) {
					if (!isset($tags[$tag])) {
						$tags[$tag] = 1;
					}
					else {
						$tags[$tag]++;
					}
				}
			}
			if (isset($post['timestamp']) && !empty($post['timestamp'])) {
				// get year
				// get month
				$year = date("Y", $post['timestamp']);
				$month = date("m", $post['timestamp']);

				if (!isset($postsByDate[$year])) {
					$postsByDate[$year] = array();
					if (!isset($postsByDate[$year][$month])) {
						$postsByDate[$year][$month] = array();
					}
				}
				$postsByDate[$year][$month][] = $post;
			}
		}
		return array(
			'types' => $types,
			'tags' => $tags,
			'postsByDate' => $postsByDate
		);
	}
}