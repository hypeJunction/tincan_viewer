<?php

namespace TinCan\Viewer;

/**
 * Handles TinCan pages
 *
 * @param array  $segments   URL segments
 * @param string $identifier Page identifier
 * @return bool
 */
function page_handler($segments, $identifier) {

	$pagename = elgg_extract(0, $segments);
	array_shift($segments);

	switch ($pagename) {
		default :
		case 'all' :
			$page = elgg_view('resources/tincan/all');
			break;

		case 'owner' :
			set_input('username', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/owner');
			break;

		case 'friends' :
			set_input('username', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/friends');
			break;

		case 'group' :
			set_input('container_guid', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/group');
			break;

		case 'view' :
			set_input('guid', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/view');
			break;

		case 'add' :
			set_input('container_guid', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/add');
			break;

		case 'edit' :
			set_input('guid', elgg_extract(0, $segments));
			$page = elgg_view('resources/tincan/edit');
			break;

		case 'lrs' :
			set_input('guid', elgg_extract(0, $segments));
			if (elgg_extract(1, $segments) == 'statements') {
				$page = elgg_view('resources/tincan/statements');
			} else if (elgg_extract(1, $segments) == 'activities') {
				$page = elgg_view('resources/tincan/state');
			} else {
				$page = '';
			}
			break;
	}

	if (isset($page)) {
		echo $page;
		return true;
	}

	return false;
}
