<?php

class TumblrCalculator {

	public static function createStats($posts) {
		if (!is_array($posts) || empty($posts)) {
			return false;
		}

		$types = array();
		foreach ($posts as $post) {
			if (!isset($types[$post['type']])) {
				$types[$post['type']] = 1;
			}
			else {
				$types[$post['type']]++;
			}
		}
		return $types;
	}
}