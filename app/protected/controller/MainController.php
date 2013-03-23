<?php

class MainController extends DooController {

	public function index() {
		$data['action'] = "index";
		if ($this->isPost()) {
			$blogname = $_POST['blogname'];
			Doo::loadClass('TumblrManager');
			$tumblr = new TumblrManager($blogname);
		}
		return $this->view()->renderLayout('main', 'tumblr', $data);
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