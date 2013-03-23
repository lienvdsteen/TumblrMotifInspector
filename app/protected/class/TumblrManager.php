<?php

class TumblrManager {
	private $_blogname;

	public function __construct($blogname) {
		if (!$blogname) {
			return false;
		}
		$this->_blogname = $blogname;
	}
}