<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use LogicException;
use Nette,
	Nette\Utils\Validators;


/**
 * To je třída, díky které získáváme data. Této se budeme dotazovat na klíče.
 *
 * $config = Config::createByEnvironment('doe', __dir__ . '/default-config.json', getcwd());
 * $config->has('name'); // TRUE
 * $config->required('name'); // John
 * $config->required('core.name'); // Andrey
 * $config->required('core'); // [ name => Andrey, age => 42 ]
 * $config->required('nope'); // exception
 * $config->get('nope'); // NULL
 * $config->get('nope', 'Mark'); // Mark
 */
class Config
{

	use Nette\SmartObject;

	/**
	 * @var array
	 */
	private $resolver;

	/**
	 * @var array
	 */
	private $map;


	/**
	 * @param string
	 * @param string
	 * @param string
	 * @return self
	 */
	static function createByEnvironment($appname, $coreDefaultConfig, $workdirectory)
	{
		Validators::assert($appname, 'string:1..');
		Validators::assert($coreDefaultConfig, 'string:1..|null', 'coreDefaultConfig');
		Validators::assert($workdirectory, 'string:1..');

		$info = PlatformInfo::create();
		switch (TRUE) {
			case $info->getType() === 'darwin':
				$style = new PlatformStyleMac($info->getUser());
				break;
			case in_array($info->getType(), ['win', 'nt', 'winnt'], TRUE):
				$style = new PlatformStyleWin($info->getUser());
				break;
			default:
				$style = new PlatformStyleUnix($info->getUser());
				break;
		}

		return new self(new Resolver($style, $appname, $coreDefaultConfig, $workdirectory));
	}



	function __construct(Resolver $resolver)
	{
		$this->resolver = $resolver;
	}



	function addParser(Parser $inst)
	{
		$this->resolver->addParser($inst);
		return $this;
	}



	/**
	 * @param string 'name', 'foo.boo', 'list[5].item'
	 * @return mixed
	 */
	function required($path)
	{
		Validators::assert($path, 'string:1..');

		$data = $this->getData();
		foreach (PathParser::parse($path) as $key) {
			if ( ! array_key_exists($key, $data)) {
				throw new LogicException("Path `$path' is not found.");
			}
			$data = $data[$key];
		}
		return $data;
	}



	/**
	 * @param string 'name', 'foo.boo', 'list[5].item'
	 * @param mixed
	 * @return mixed
	 */
	function get($path, $default = NULL)
	{
		Validators::assert($path, 'string:1..');

		$data = $this->getData();
		foreach (PathParser::parse($path) as $key) {
			if ( ! array_key_exists($key, $data)) {
				return $default;
			}
			$data = $data[$key];
		}
		return $data;
	}



	/**
	 * @param string
	 * @return bool
	 */
	function has($path)
	{
		Validators::assert($path, 'string:1..');

		$data = $this->getData();
		foreach (PathParser::parse($path) as $key) {
			if ( ! array_key_exists($key, $data)) {
				return FALSE;
			}
			$data = $data[$key];
		}
		return TRUE;
	}



	/**
	 * @return array
	 */
	private function getData()
	{
		if (empty($this->map)) {
			$this->map = $this->resolver->fetch();
		}
		return $this->map;
	}

}
