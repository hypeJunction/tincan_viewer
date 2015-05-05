<?php

namespace TinCan\Viewer;

use Elgg\TinCan\Agent;
use Elgg\TinCan\LRS;
use ElggObject;
use ElggUser;
use TinCan\Statement;
use WideImage\Exception\Exception;
use ZipArchive;

class Package extends ElggObject {

	const SUBTYPE = 'tincan_package';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

	/**
	 * Returns original zip archive as an ElggFil
	 * @return File
	 */
	public function getSourceFile() {
		$filehandler = new File();
		$filehandler->owner_guid = $this->guid;
		$filehandler->setFilename('source.zip');
		return $filehandler;
	}

	/**
	 * Extracts contents of an archive
	 * @return void
	 * @throws Exception
	 */
	public function unzip() {

		$filestorename = $this->getSourceFile()->getFilenameOnFilestore();
		$dirname = dirname($filestorename);

		$zip = new ZipArchive;
		$result = $zip->open($filestorename);

		if ($result !== true) {
			throw new Exception(elgg_echo('tincan:error:not_valid_zip'));
		}

		$is_tincan = $zip->locateName('tincan.xml') !== false;

		if (!$is_tincan) {
			$zip->close();
			throw new Exception(elgg_echo('tincan:error:not_valid_tincan'));
		}

		$zip->extractTo($dirname);

		$details = $this->getManifest()->getPackageDetails();
		$launch = elgg_extract('launch', $details, 'index.html');

		if ($zip->locateName('tincan.xml') === false) {
			$zip->close();
			throw new Exception(elgg_echo('tincan:error:no_launch'));
		}

		$zip->close();
	}

	/**
	 * Returns tincan.xml as an object
	 * @return Manifest
	 */
	public function getManifest() {
		$xml = new File();
		$xml->owner_guid = $this->guid;
		$xml->setFilename('tincan.xml');
		if (!$xml->exists()) {
			throw new Exception(elgg_echo('tincan:edit:error:not_valid_tincan'));
		}
		return simplexml_load_file($xml->getFilenameOnFilestore(), __NAMESPACE__ . '\\Manifest');
	}

	/**
	 * Returns a display name
	 * @return string
	 */
	public function getDisplayName() {
		if ($this->package_name) {
			return $this->package_name;
		} else {
			return parent::getDisplayName();
		}
	}

	/**
	 * Returns a URL suitable for iframe display
	 * @return string
	 */
	public function getEmbedURL() {

		$path = ($this->package_launch) ? : 'index.html';

		$key = get_site_secret();
		$guid = $this->guid;
		$ts = $this->package_time;

		$hmac = hash_hmac('sha256', $guid . $ts . $_COOKIE['Elgg'], $key);

		// Reports are sent using these params
		$params = array(
			'endpoint' => urlencode(elgg_normalize_url("tincan/lrs/$this->guid/")),
			'activity_id' => $this->package_id,
			'hmac' => hash_hmac('sha256', $this->package_id . $this->guid . $_COOKIE['Elgg'], $key),
		);

		$params = elgg_trigger_plugin_hook('params:endpoint', 'tincan', array(
			'entity' => $this,
				), $params);

		return elgg_http_add_url_query_elements("/mod/tincan_viewer/servers/$guid/$ts/$hmac/$path", $params);
	}

	/**
	 * Handles TinCan Statement
	 *
	 * @param ElggUser $user Actor
	 * @param array    $data Payload
	 * @return \TinCan\LRSResponse
	 */
	public function handleStatement($user, $data) {
		return elgg_trigger_plugin_hook('statement:save', 'tincan', array(
			'user' => $user,
			'data' => $data,
		));
	}

	/**
	 * Updates TinCan state
	 *
	 * @param ElggUser $user
	 * @param array    $data
	 * @return \TinCan\LRSResponse
	 */
	public function saveState($user, $data) {
		return elgg_trigger_plugin_hook('state:save', 'tincan', array(
			'user' => $user,
			'data' => $data,
		));
	}

	/**
	 * Retrieves state
	 *
	 * @param ElggUser $user User
	 * @param array    $data Data
	 * @return \TinCan\LRSResponse
	 */
	public function retrieveState($user, $data) {
		return elgg_trigger_plugin_hook('state:retrieve', 'tincan', array(
			'user' => $user,
			'data' => $data,
		));
	}

}
