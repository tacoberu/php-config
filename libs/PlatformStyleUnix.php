<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette,
	Nette\Utils\Validators;


/**
 * system: /etc/doe.ini
 * user: /home/<current user>/.config/doe/config.ini
 */
class PlatformStyleUnix extends Nette\Object implements PlatformStyle
{

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
			$xs[] = "/etc/{$appname}/config.{$ext}";
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
			$xs[] = "/home/{$this->user}/.config/{$appname}/config.{$ext}";
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
