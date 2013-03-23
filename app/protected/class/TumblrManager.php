<?php

class TumblrManager {
	private $_blogname;
	private $_posts = array();
	private $_api;
	private $_totalPostCount;
	private $_collectedPostCount = 0;

	public function __construct($blogname) {
		$this->_blogname = $blogname;
		Doo::loadClass('TumblrApi');
		$this->_api = new TumblrApi();
	}

	public function getBlogInfo() {
		$method = 'blog/' . $this->_blogname . '.tumblr.com/info';
		$blogInfo = $this->_api->makeApiCall($method);
		if (!$blogInfo || !is_array($blogInfo) || !isset($blogInfo['blog'])) {
			return false;
		}
		$this->_totalPostCount = $blogInfo['blog']['posts'];
		if ($this->_totalPostCount > 250) {
			$this->_totalPostCount = 250;
		}
		return $blogInfo;
	}

	public function getPosts() {
		$method = 'blog/' . $this->_blogname . '.tumblr.com/posts';

		while ($this->_collectedPostCount < $this->_totalPostCount) {
			$currentPosts = $this->_getAllPosts($method, $this->_collectedPostCount);
			if (is_array($currentPosts)) {
				$this->_posts = array_merge($this->_posts, $currentPosts);
				$this->_collectedPostCount = $this->_collectedPostCount + count($currentPosts);	
			}
		}
		return $this->_posts;
	}

	private function _getAllPosts($method, $offset = 0, $limit = 20) {
		$params = array(
			'offset' => $offset,
			'limit' => $limit
		);
		$posts = $this->_api->makeApiCall($method, $params);
		if ($posts && is_array($posts['posts'])) {
			return $posts['posts'];
		}
		return false;
	}
}