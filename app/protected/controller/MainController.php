<?php

class MainController extends DooController {

	public function index() {
		$data['action'] = "index";
		if ($this->isPost()) {
			$blogname = $this->escape($_POST['blogname']);
			Doo::loadClass('TumblrManager');
			$tumblr = new TumblrManager($blogname);
			$blogInfo = $tumblr->getBlogInfo();
			if ($blogInfo) {
				if ($blogInfo['posts'] == 0) {
					$data['message'] = "This Tumblr doesn't have posts yet, so we can't calculate anything."; 	
				}
				$data['bloginfo'] = $blogInfo;
				$data['avatar'] = $tumblr->getBlogAvatar();
				$data['blogname'] = $blogname;
				return $this->view()->renderLayout('main', 'results', $data);
			}
			else {
				// no bloginfo found (prob this blog doesn't exist, spelling mistake?)
				// also when private blog..
				$data['message'] = "We couldn't find any information for this blog: $blogname . Please try again."; 
			}
		}
		return $this->view()->renderLayout('main', 'tumblr', $data);
	}

	public function posts() {
		if ($this->isAjax()) {
			if (isset($this->params['blogname'])) {
				$blogname = $this->params['blogname'];
				Doo::loadClass('TumblrManager');
				$tumblr = new TumblrManager($blogname);
				$offset = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
				$posts = $tumblr->getAllPosts($offset);

				Doo::loadClass('TumblrCalculator');
				$stats = TumblrCalculator::createStats($posts);

				$result = array(
					'posts' => $posts,
					'params' => $_POST['offset'],
					'types' => $stats['types'],
					'tags' => $stats['tags']
				);
			}
			else {
				// foutje met url
			}
			
			$this->returnAjax($result);
		} 

	}

	public function about() {
		$data['action'] = "about";
		return $this->view()->renderLayout('main', 'about', $data);
	}

	public function contact() {
		$data['action'] = "contact";
		return $this->view()->renderLayout('main', 'contact', $data);
	}
}