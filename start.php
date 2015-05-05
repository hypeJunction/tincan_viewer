<?php

namespace TinCan\Viewer;

require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/page_handlers.php';
require_once __DIR__ . '/lib/hooks.php';

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrade');

/**
 * Initialize the plugin
 * @return void
 */
function init() {

	elgg_register_page_handler('tincan', __NAMESPACE__ . '\\page_handler');

	elgg_register_action('tincan/edit', __DIR__ . '/actions/tincan/edit.php');

	elgg_register_plugin_hook_handler('entity:url', 'object', __NAMESPACE__ . '\\url_handler');

	elgg_register_entity_type('object', Package::SUBTYPE);

	elgg_register_plugin_hook_handler('statement:save', 'tincan', __NAMESPACE__ . '\\save_statement');
	elgg_register_plugin_hook_handler('state:save', 'tincan', __NAMESPACE__ . '\\save_state');
	elgg_register_plugin_hook_handler('state:retrieve', 'tincan', __NAMESPACE__ . '\\retrieve_state');
	elgg_register_plugin_hook_handler('params:endpoint', 'tincan', __NAMESPACE__ . '\\filter_endpoint_params');
}

/**
 * Run upgrade scripts
 * @return void
 */
function upgrade() {
	if (elgg_is_admin_logged_in()) {
		include_once __DIR__ . '/lib/upgrades.php';
	}
}
