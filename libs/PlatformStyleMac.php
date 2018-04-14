<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette,
	Nette\Utils\Validators;


/**
 * system: /private/etc/doe.ini
 * user: /Users/<current user>/.config/doe/config.ini
 */
class PlatformStyleMac implements PlatformStyle
{

	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $user;


	/**
	 * @param string
	 */
	function __construct($user)
	{
		Validators::assert($user, 'string:1..');
		$this->user = $user;
	}



	/**
	 * @param string
	 * @param list of string
	 * @return list of string
	 */
	function getSystemConfig($appname, array $exts)
	{
		Validators::assert($appname, 'string:1..');
		$xs = [];
		foreach ($exts as $ext) {
			$xs[] = "/private/etc/{$appname}/config.{$ext}";
		}
		return $xs;
	}



	/**
	 * @param string
	 * @param list of string
	 * @return list of string
	 */
	function getUserConfig($appname, array $exts)
	{
		Validators::assert($appname, 'string:1..');
		$xs = [];
		foreach ($exts as $ext) {
			$xs[] = "/Users/{$this->user}/.config/{$appname}/config.{$ext}";
		}
		return $xs;
	}



	/**
	 * @param string
	 * @param string
	 * @param list of string
	 * @return list of string
	 */
	function getLocalConfig($appname, $workDirectory, array $exts)
	{
		Validators::assert($appname, 'string:1..');
		Validators::assert($workDirectory, 'string:1..');
		$workDirectory = rtrim($workDirectory, '/');
		$xs = [];
		foreach ($exts as $ext) {
			$xs[] = "{$workDirectory}/{$appname}.{$ext}";
		}
		return $xs;
	}

}
