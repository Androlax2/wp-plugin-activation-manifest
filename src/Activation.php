<?php

namespace Androlax2\WordPress\PluginManifest;

use Androlax2\WordPress\PluginControl\DisablePlugins;
use Androlax2\WordPress\PluginControl\EnablePlugins;
use Androlax2\WordPress\PluginControl\NetworkDisablePlugins;
use Androlax2\WordPress\PluginControl\NetworkEnablePlugins;

class Activation
{

	protected $manifest;

	public function __construct(Manifest $manifest)
	{
		$this->manifest = $manifest;
	}

	public static function set($config_file, $environment = null)
	{
		$manifest = new Manifest($config_file, $environment);
		$activation = new static($manifest->load());

		return $activation->apply();
	}

	public function apply()
	{
		array_map([$this, 'enforce'], $this->manifest->get_data());

		return $this;
	}

	protected function enforce($manifest)
	{
		if (!empty($manifest['enable'])) {
			new EnablePlugins($manifest['enable']);
		}

		if (!empty($manifest['disable'])) {
			new DisablePlugins($manifest['disable']);
		}

		if (!empty($manifest['network-enable'])) {
			new NetworkEnablePlugins($manifest['network-enable']);
		}

		if (!empty($manifest['network-disable'])) {
			new NetworkDisablePlugins($manifest['network-disable']);
		}
	}

}