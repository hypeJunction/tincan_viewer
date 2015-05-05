<?php

namespace TinCan\Viewer;

/**
 * Handles package URLs
 *
 * @param string $hook   "entity:url"
 * @param string $type   "object"
 * @param string $url    URL
 * @param array  $params Hook params
 * @return string
 */
function url_handler($hook, $type, $url, $params) {

	$entity = elgg_extract('entity', $params);

	if ($entity instanceof Package) {
		return elgg_normalize_url("tincan/view/$entity->guid");
	}

	return $url;
}

/**
 * Sends TinCan statement to LRS
 *
 * @param string $hook   "statement:save"
 * @param string $type   "tincan"
 * @param mixed  $return Data to return to the TinCan package
 * @param array  $params Data received from the TinCan package
 * @return mixed
 */
function save_statement($hook, $type, $return, $params) {

	if (!elgg_is_active_plugin('tincan_xapi')) {
		return $return;
	}

	$user = elgg_extract('user', $params);
	$data = elgg_extract('data', $params);

	$content = elgg_extract('content', $data);
	$content = json_decode($content, true);

	if (!$user || !$content) {
		return $return;
	}

	try {
		$actor = new \Elgg\TinCan\Agent($user);
		$content['actor'] = $actor->getProperties();

		$lrs = \Elgg\TinCan\LRS::getRemoteLRS();
		$statement = new \TinCan\Statement($content);
		$return = $lrs->saveStatement($statement);
	} catch (\Exception $ex) {
		elgg_log($ex->getMessage(), 'ERROR');
	}

	return $return;
}

/**
 * Saves TinCan state in the LRS
 * 
 * @param string $hook   "state:save"
 * @param string $type   "tincan"
 * @param mixed  $return Data to return to the TinCan package
 * @param array  $params Data received from the TinCan pakcage
 * @return mixed
 */
function save_state($hook, $type, $return, $params) {

	if (!elgg_is_active_plugin('tincan_xapi')) {
		return $return;
	}
	
	$user = elgg_extract('user', $params);
	$data = elgg_extract('data', $params);

	try {
		$activity = array(
			'id' => elgg_extract('activityId', $data),
		);

		$actor = new \Elgg\TinCan\Agent($user);
		$actor = $actor->getProperties();

		$state = elgg_extract('stateId', $data);
		$content = elgg_extract('content', $data);

		$lrs = \Elgg\TinCan\LRS::getRemoteLRS();
		$return = $lrs->saveState($activity, $actor, $state, $content);
	} catch (\Exception $ex) {
		elgg_log($ex->getMessage(), 'ERROR');
	}

	return $return;
}

/**
 * Retrieves TinCan state from the LRS
 *
 * @param string $hook   "state:retrieve"
 * @param string $type   "tincan"
 * @param mixed  $return Data to return to the TinCan package
 * @param array  $params Data received from the TinCan pakcage
 * @return mixed
 */
function retrieve_state($hook, $type, $return, $params) {

	if (!elgg_is_active_plugin('tincan_xapi')) {
		return $return;
	}

	$user = elgg_extract('user', $params);
	$data = elgg_extract('data', $params);

	try {
		$activity = array(
			'id' => elgg_extract('activityId', $data),
		);

		$actor = new \Elgg\TinCan\Agent($user);
		$actor = $actor->getProperties();

		$state = elgg_extract('stateId', $data);
		$content = elgg_extract('content', $data);

		$lrs = \Elgg\TinCan\LRS::getRemoteLRS();
		return $lrs->retrieveState($activity, $actor, $state);
	} catch (\Exception $ex) {
		elgg_log($ex->getMessage(), 'ERROR');
	}

	return $return;
}

/**
 * Adds registration parameter for LRS integration
 *
 * @param string $hook   "params:endpoint"
 * @param string $type   "tincan"
 * @param array  $return Endpoint params
 * @param array  $params Hook params
 * @return array
 */
function filter_endpoint_params($hook, $type, $return, $params) {

	if (!elgg_is_active_plugin('tincan_xapi')) {
		return $return;
	}

	$return['registration'] = \TinCan\Util::getUUID();
	return $return;
}