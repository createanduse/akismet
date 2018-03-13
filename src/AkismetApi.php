<?php

/**
 * Akismet PHP plugin
 *
 * @author Łukasz 'gogol' Gogołkiewicz <lukasz@gogolkiewicz.pl>
 * @copyright 2016 Łukasz Gogołkiewicz
 * @license MIT
 * @link http://github.com/createanduser/akismet
 * @link http://gogolkiewicz.pl/p/akismet-php-plugin
 * @version 1.0
 */

namespace CreateAndUse\Akismet;

/**
 * Class AkismetApi
 * @package CreateAndUse\Akismet
 */
class AkismetApi {

	/**
	 * Akismet name
	 */
	const AKISMET_NAME = 'Akismet';

	/**
	 * Akismet version
	 */
	const AKISMET_VERSION = '3.1.7';

	/**
	 * @var array
	 */
	private $_apiConfig;

	/**
	 * @var string
	 */
	private $_apiKey;

	/**
	 * @var string
	 */
	private $_appUrl;

	/**
	 * @var string
	 */
	private $_apiResponse;

	/**
	 * @var array
	 */
	private $_apiResponseHeaders;

	/**
	 * Construct
	 * @param string $apiKey
	 * @param string $appUrl
	 * @param array $apiConfig
	 */
	public function __construct($apiKey, $appUrl, $apiConfig) {
		$this->_apiKey = $apiKey;
		$this->_appUrl = $appUrl;
		$this->_apiConfig = $apiConfig;
	}

	/**
	 * send request to Akismet API
	 * @param string $path
	 * @param bool $addApiKeyToUrl
	 * @param array $data
	 * @throws \Exception
	 */
	public function sendRequest($path, $addApiKeyToUrl, array $data = []) {
		$fullUrl = $this->_buildFullUrl($path, $addApiKeyToUrl);
		$context = $this->_generateContext($data);

		$this->_apiResponse = file_get_contents($fullUrl, false, $context);
		$this->_apiResponseHeaders = $http_response_header;
	}

	/**
	 * Get raw response from Akismet API
	 * @return string
	 */
	public function getRawResponse() {
		return $this->_apiResponse;
	}

	/**
	 * Get raw response headers from Akismet API
	 * @return array
	 */
	public function getRawResponseHeaders() {
		return $this->_apiResponseHeaders;
	}

	/**
	 * Build user agent header for Akismet API request
	 * @return string
	 */
	private function _buildUserAgent() {
		$plugin = Akismet::PLUGIN_NAME . '/' . Akismet::PLUGIN_VERSION;
		$akismet = self::AKISMET_NAME . '/' . self::AKISMET_VERSION;

		return $plugin . '|' . $akismet;
	}

	/**
	 * Generate full url to Akismet API
	 * @param string $path
	 * @param bool $addApiKeyToUrl
	 * @return string
	 * @throws \Exception
	 */
	private function _buildFullUrl($path, $addApiKeyToUrl = true) {
		if(!array_key_exists($path, $this->_apiConfig)) {
			throw new \Exception('Incorrect Akismet API config');
		}

		$url = 'http' . ($this->_apiConfig['ssl'] ? 's' : '') . '://';

		if($addApiKeyToUrl) {
			$url .= $this->_apiKey . '.';
		}

		$url .= rtrim($this->_apiConfig['url'], '/') . '/' . $this->_apiConfig['version'];
		return $url . $this->_apiConfig[$path];
	}

	/**
	 * Generate context for Akismet API request
	 * @param array $data
	 * @return resource
	 */
	private function _generateContext(array $data) {
		$headers = [
			'Content-Type: application/x-www-form-urlencoded',
			'User-Agent: ' . $this->_buildUserAgent(),
		];

		$options = [
			'http' => [
				'method' => 'POST',
				'header' => implode("\r\n", $headers) . "\r\n",
				'content' => http_build_query($data),
			],
		];

		return stream_context_create($options);
	}

}
