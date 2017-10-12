<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;


/**
 * Information about platform.
 */
class PlatformInfo
{

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $subtype;

	/**
	 * @var string
	 */
	private $version;

	/**
	 * @var string
	 */
	private $machine;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $home;


	/**
	 * @return self
	 */
	static function create()
	{
		$user = $_SERVER['USER'];
		$home = $_SERVER['HOME'];
		$info = self::platform();
		return new self($info->type, $info->subtype, $info->version, $info->machine, $user, $home);
	}



	/**
	 * @param string Like linux|darwin|freebsd|windowsnt|sunos
	 * @param string Like 'fedora'
	 * @param string
	 * @param string Like x86_64
	 * @param string Like 'fero'
	 * @param string Like '/home/fero'
	 */
	function __construct($type, $subtype, $version, $machine, $user, $home)
	{
		$this->type = $type;
		$this->subtype = $subtype;
		$this->version = $version;
		$this->machine = $machine;
		$this->user = $user;
		$this->home = $home;
	}



	/**
	 * Like linux|darwin|freebsd|windowsnt|sunos
	 * @return string
	 */
	function getType()
	{
		return $this->type;
	}



	/**
	 * Like 'fedora'
	 * @return string
	 */
	function getSubType()
	{
		return $this->subtype;
	}



	/**
	 * @return string
	 */
	function getVersion()
	{
		return $this->version;
	}



	/**
	 * @return x86_64
	 */
	function getMachine()
	{
		return $this->machine;
	}



	/**
	 * @return string
	 */
	function getUser()
	{
		return $this->user;
	}



	/**
	 * @return /home/fero
	 */
	function getHome()
	{
		return $this->home;
	}



	private static function platform()
	{
		$res = (object) [
			'type' => strtolower(self::uname('s')),
			'subtype' => NULL,
			'version' => NULL,
			'machine' => strtolower(self::uname('m')),
		];
		switch ($res->type) {
			case 'linux':
				if ($release = self::parseRelease(self::exec('cat /etc/*release'))) {
					$res->subtype = strtolower($release['ID']);
					$res->version = strtolower($release['VERSION_ID']);
				}
				break;
			case 'darwin':
				$res->version = self::uname('r');
				break;
		}

		return $res;
	}



	/**
	 * @param string
	 * @param string
	 */
	private static function uname($mode)
	{
		return php_uname($mode);
		//~ return trim(self::exec('uname -s'));
	}



	/**
	 * @param string
	 * @param string
	 */
	private static function exec($cmd)
	{
		exec($cmd, $output);
		return implode(PHP_EOL, $output);
	}



	/**
	 * @param string
	 * @param list
	 */
	private static function parseRelease($content)
	{
		if (empty($content)) {
			return [];
		}
		$xs = [];
		foreach (explode(PHP_EOL, $content) as $row) {
			$pair = explode('=', $row, 2);
			if (count($pair) === 2) {
				$xs[$pair[0]] = $pair[1];
			}
		}

		return $xs;
	}

}
