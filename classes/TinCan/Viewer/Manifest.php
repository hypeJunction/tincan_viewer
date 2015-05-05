<?php

namespace TinCan\Viewer;

class Manifest extends \SimpleXMLElement {

	/**
	 * Returns package details
	 * Assumes that the first <activity> node describes the package
	 * @return array
	 */
	public function getPackageDetails() {
		$package = $this->activities[0]->activity[0];

		return array(
			'id' => $package->attributes()['id'],
			'type' => $package->attributes()['type'],
			'name' => $package->name[0],
			'description' => $package->description[0],
			'launch' => $package->launch[0] ?: 'index.html',
		);
	}

}