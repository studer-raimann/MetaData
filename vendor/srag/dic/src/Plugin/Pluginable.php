<?php

namespace srag\DIC\MetaData\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\MetaData\Plugin
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Pluginable {

	/**
	 * @return PluginInterface
	 */
	public function getPlugin();


	/**
	 * @param PluginInterface $plugin
	 *
	 * @return static
	 */
	public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
