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
 * Class Akismet
 * @package CreateAndUse\Akismet
 */
class Akismet {

	/**
	 * Akismet PHP plugin name
	 */
	const PLUGIN_NAME = 'AkismetPhpPlugin';

	/**
	 * Akismet PHP plugin version
	 */
	const PLUGIN_VERSION = '1.0';

	/**
	 * Default API config
	 * @var array
	 */
	private $_apiConfig = [
		'url' => 'rest.akismet.com',
		'ssl' => true,
		'version' => '1.1',
		'pathCommentCheck' => '/comment-check',
		'pathVerifyKey' => '/verify-key',
		'pathSubmitSpam' => '/submit-spam',
		'pathSubmitHam' => '/submit-ham',
	];

	/**
	 * @var string
	 */
	private $_apiKey;

	/**
	 * @var string
	 */
	private $_appUrl;

	/**
	 * Construct
	 * @param string $apiKey
	 * @param string $appUrl
	 * @param array $apiConfig
	 */
	public function __construct($apiKey, $appUrl, array $apiConfig = []) {
		$this->_apiKey = $apiKey;
		$this->_appUrl = urlencode($appUrl);

		if($apiConfig) {
			$this->setApiConfig($apiConfig);
		}
	}

	/**
	 * Set non default Akismet API config
	 * @param array $apiConfig
	 */
	public function setApiConfig(array $apiConfig) {
		$this->_apiConfig = $apiConfig;
	}

	/**
	 * Verify Akismet API key
	 * @throws \Exception
	 * @return bool
	 */
	public function verifyKey() {
		$api = new AkismetApi($this->_apiKey, $this->_appUrl, $this->_apiConfig);
		$api->sendRequest('pathVerifyKey', false, [
			'key' => $this->_apiKey,
			'blog' => $this->_appUrl,
		]);

		$response = $api->getRawResponse();
		return (bool) ($response == 'valid');
	}

	/**
	 * Check comment by Akismet API
	 * If returns true then it is spam
	 *
	 * required and optional params list
	 * @see https://akismet.com/development/api/#comment-check
	 *
	 * @param array $params
	 * @throws \Exception
	 * @return bool
	 */
	public function commentCheck(array $params) {
		$params['blog'] = $this->_appUrl;

		$api = new AkismetApi($this->_apiKey, $this->_appUrl, $this->_apiConfig);
		$api->sendRequest('pathCommentCheck', true, $params);

		$response = $api->getRawResponse();
		return (bool) ($response == 'true');
	}

	/**
	 * Submit spam to Akismet API
	 * If returns true then it is approved by Akismet API
	 *
	 * required and optional params list
	 * @see https://akismet.com/development/api/#submit-spam
	 *
	 * @param array $params
	 * @throws \Exception
	 * @return bool
	 */
	public function submitSpam(array $params) {
		$params['blog'] = $this->_appUrl;

		$api = new AkismetApi($this->_apiKey, $this->_appUrl, $this->_apiConfig);
		$api->sendRequest('pathSubmitSpam', true, $params);

		$response = $api->getRawResponse();
		return (bool) ($response == 'Thanks for making the web a better place.');
	}

	/**
	 * Submit ham to Akismet API
	 * If returns true then it is approved by Akismet API
	 *
	 * required and optional params list
	 * @see https://akismet.com/development/api/#submit-ham
	 *
	 * @param array $params
	 * @throws \Exception
	 * @return bool
	 */
	public function submitHam(array $params) {
		$params['blog'] = $this->_appUrl;

		$api = new AkismetApi($this->_apiKey, $this->_appUrl, $this->_apiConfig);
		$api->sendRequest('pathSubmitHam', true, $params);

		$response = $api->getRawResponse();
		return (bool) ($response == 'Thanks for making the web a better place.');
	}

}
