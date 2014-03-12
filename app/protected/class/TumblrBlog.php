<?php

class TumblrBlog {

    public static function stripBlogname($blogname) {
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

}
