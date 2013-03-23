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

	public function getBlogAvatar() {
		$method = 'blog/' . $this->_blogname . '.tumblr.com/avatar/128';
		$avatar = $this->_api->makeApiCall($method, array(), false);
		return $avatar;
	}

	public function getBlogInfo() {
		$method = 'blog/' . $this->_blogname . '.tumblr.com/info';
		$blogInfo = $this->_api->makeApiCall($method);
		if (!$blogInfo || !is_array($blogInfo) || !isset($blogInfo['blog'])) {
			return false;
		}
		$this->_totalPostCount = $blogInfo['blog']['posts'];
		return $blogInfo['blog'];
	}

	public function getAllPosts($offset = 0, $limit = 20) {
		$method = 'blog/' . $this->_blogname . '.tumblr.com/posts';
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