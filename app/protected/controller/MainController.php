<?php

class MainController extends DooController {

	public function index() {
		$data['action'] = "index";
		
		if ($this->isPost()) {
			$origblogname = $this->escape($_POST['blogname']);
			$blogname = $this->_stripBlogname($origblogname);
			
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
				$data['message'] = "We couldn't find any information for this blog: $origblogname . Please try again."; 
			}
		}
		return $this->view()->renderLayout('main', 'tumblr', $data);
	}

	private function _stripBlogname($blogname) {
		//@todo: allow custom urls (ex: thejoyofcode.com)
		$http = strpos($blogname, 'http');
		$https = strpos($blogname, 'https');
		
		if (is_numeric($https)) {
			$newblog = substr($blogname, 8);
			$blogname = $newblog;
		}
		elseif (is_numeric($http)) {
			$newblog = substr($blogname, 7);
			$blogname = $newblog;
		}
		
		$tumblr = ".tumblr.com";
		$domain = stripos($blogname, $tumblr);
		if (is_numeric($domain)) {
			$newblog = substr($blogname, 0, $domain);
			$blogname = $newblog;	
		}
		return $blogname;
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

				if (isset($_POST['postsByDate']) && is_array($_POST['postsByDate'])) {
					$oldPostsByDate = $_POST['postsByDate'];
					if (is_array($stats['postsByDate']) && !empty($stats['postsByDate'])) {
						// geen lege stats EN posts, we need to add the values
						foreach ($oldPostsByDate as $year => $oldPostsByYear) {
							foreach ($oldPostsByYear as $month => $value) {
								// kijken of het jaar in de stats gezet is
								if (isset($stats['postsByDate'][$year])) {
									if (isset($stats['postsByDate'][$year][$month])) {
										// maand gezet, we moeten het erbij tellen
										$stats['postsByDate'][$year][$month] += $value;
									}
									else {
										// maand niet gezet ?
										$stats['postsByDate'][$year][$month] = $value;	
									}
								}
								else {
									$stats['postsByDate'][$year] = array();
									$stats['postsByDate'][$year][$month] = $value;
								}
							}	
						}
					}
					else {
						// lege stats['postsByDate'] maar geen lege post
						$stats['postsByDate'] = $oldPostsByDate;
					}
				}

				if (isset($stats['postsByDate']) && !empty($stats['postsByDate'])) {
					$chartData = array();
					foreach ($stats['postsByDate'] as $year => $postsByYear) {
						$chartData[] = array(
							'name' => $year,
							'data' => array(
								isset($stats['postsByDate'][$year]["01"]) ? (int) $stats['postsByDate'][$year]["01"] : 0,
								isset($stats['postsByDate'][$year]["02"]) ? (int) $stats['postsByDate'][$year]["02"] : 0,
								isset($stats['postsByDate'][$year]["03"]) ? (int) $stats['postsByDate'][$year]["03"] : 0,
								isset($stats['postsByDate'][$year]["04"]) ? (int) $stats['postsByDate'][$year]["04"] : 0,
								isset($stats['postsByDate'][$year]["05"]) ? (int) $stats['postsByDate'][$year]["05"] : 0,
								isset($stats['postsByDate'][$year]["06"]) ? (int) $stats['postsByDate'][$year]["06"] : 0,
								isset($stats['postsByDate'][$year]["07"]) ? (int) $stats['postsByDate'][$year]["07"] : 0,
								isset($stats['postsByDate'][$year]["08"]) ? (int) $stats['postsByDate'][$year]["08"] : 0,
								isset($stats['postsByDate'][$year]["09"]) ? (int) $stats['postsByDate'][$year]["09"] : 0,
								isset($stats['postsByDate'][$year]["10"]) ? (int) $stats['postsByDate'][$year]["10"] : 0,
								isset($stats['postsByDate'][$year]["11"]) ? (int) $stats['postsByDate'][$year]["11"] : 0,
								isset($stats['postsByDate'][$year]["12"]) ? (int) $stats['postsByDate'][$year]["12"] : 0,
							)
						);
					}

				}

				if (isset($_POST['tags']) && is_array($_POST['tags'])) {					
					$oldTags = $_POST['tags'];
					if (is_array($stats['tags']) && !empty($stats['tags'])) {
						foreach ($oldTags as $key => $value) {
							if (isset($stats['tags'][$key])) {
								$stats['tags'][$key] += $value;
							}
							else {
								$stats['tags'][$key] = $value;
							}
						}
					}
					else {
						// lege stats['tags'] maar geen lege post, we mogen deze dus niet kwijtspelen
						$stats['tags'] = $oldTags;
					}
				}

				if (is_array($stats['tags']) && !empty($stats['tags'])) {
					foreach ($stats['tags'] as $key => $value) {
						$stats['tags'][$key] = (int)$value;
					}
					arsort($stats['tags']);
					$topTags = array_slice($stats['tags'], 0, 6);
					$topTagsNew = array();
					foreach ($topTags as $key => $value) {
						$topTag = array('name' => $key, "y" => $value);
						array_push($topTagsNew, $topTag);
					}
					arsort($topTags);
				}

				$result = array(
					'posts' => $posts,
					'params' => $_POST,
					'types' => $stats['types'],
					'tags' => $stats['tags'],
					'postsByDate' => $stats['postsByDate'],
					'yearKeys' => array_keys($stats['postsByDate']),
					'topTags' => isset($topTagsNew) ? $topTagsNew : false,
					'chartData' => isset($chartData) ? $chartData : false
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