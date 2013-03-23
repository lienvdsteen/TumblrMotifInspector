<?php

class TumblrCalculator {

	public static function createStats($posts) {
		if (!is_array($posts) || empty($posts)) {
			return false;
		}

		$types = array();
		$tags = array();
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
		}
		return array(
			'types' => $types,
			'tags' => $tags
		);
	}
}