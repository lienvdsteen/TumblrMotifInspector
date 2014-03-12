<?php

/**
 * TumblrApi class will make the api calls for the tumblr service.
 */
class TumblrApi {
	const API_URL = 'http://api.tumblr.com/v2/';
	const API_KEY = 'NGuxaoUqfF0L0UGX5guKxEvFhU4jPZk5hRgErdIPY69UrT6jwa';
	const API_SECRET = 'IRYdDnFPSJYl3Dxi5yoic35QU9SkelYWhGxKbg3VWEOw5Fp80k';

	/**
	 * Make the api call
	 * @param  string  $method extra url (for example '/info or /blog ...')
	 * @param  array   $params extra params that will be added to the url
	 * @param  boolean $apiKey call with apikey needed?
	 * @param  boolean $curl   let curl handle the request
	 * @return false if call failed or $response, array with the needed info
	 */
	public function makeApiCall($method = '', $params = array(), $apiKey = true, $curl = true, $check = true) {
		$url = self::API_URL . $method;
		$reqPar = array();

		if (is_array($params) && !empty($params)) {
			foreach ($params as $key => $value) {
				$reqPar[] = $key . '=' . $value;
			}
		}

		if ($apiKey) {
			$reqPar[] = 'api_key=' . self::API_KEY;
		}

		if (!empty($reqPar)) {
			$addUrl = implode('&', $reqPar);
			$url = $url . '?' . $addUrl;
		}

		if ($curl) {
			return $this->_http($url, null, $check);
		}

		return $url;
	}

	/**
	 * Will check the tumblr repsonse
	 * @param  json_object $response the json object we received from the tumblr api
	 * @return array json_decoded response
	 */
	private function _checkResponse($response) {
		$response = json_decode($response, true);
		if (!is_array($response)) {
			return false;
		}
		if ($response['meta']['status'] == 200) {
			return $response['response'];
		}
		elseif ($response['meta']['status'] == 301) {
			return $response['response']['avatar_url'];
		}
		else {
			// something went wrong, log this?
			return false;
		}

	}

	/**
	 * This private function will handle the curl stuff
	 */
    private function _http($url, $post_data = null, $check = true) {
        $start = microtime(true);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        if (isset($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        $response = curl_exec($ch);
        $this->http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->last_api_call = $url;
        curl_close($ch);

        if ($check) {
        	return $this->_checkResponse($response);
        }
        else {
        	return $response;
        }
    }
}
