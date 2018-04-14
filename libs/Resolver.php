<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette,
	Nette\Utils\Validators;
use RuntimeException;


/**
 * Načte config podle lokalizace. Zparsuje jej za použití parseru a zohlední
 * případné includy, které opět načte. Výsledkem je mapa.
 */
class Resolver
{

	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $coreDefaultConfig;

	/**
	 * @var PlatformStyle
	 */
	private $style;

	/**
	 * @var string
	 */
	private $appname;

	/**
	 * @var string
	 */
	private $workdirectory;

	/**
	 * @var array of Parser
	 */
	private $parsers;


	/**
	 * @param string $appname
	 * @param string|null $coreDefaultConfig
	 * @param string $workdirectory
	 */
	function __construct(PlatformStyle $style, $appname, $coreDefaultConfig, $workdirectory)
	{
		Validators::assert($appname, 'string:1..', 'appname');
		Validators::assert($coreDefaultConfig, 'string:1..|null', 'coreDefaultConfig');
		Validators::assert($workdirectory, 'string:1..', 'workdirectory');
		$this->style = $style;
		$this->appname = $appname;
		$this->coreDefaultConfig = $coreDefaultConfig;
		$this->workdirectory = $workdirectory;
		$this->parsers = [
			'ini' => new IniParser,
			'json' => new JsonParser,
		];
	}



	function addParser(Parser $inst)
	{
		$ext = reset($inst->getExtensions());
		$this->parsers[$ext] = $inst;
	}



	/**
	 * @return array
	 */
	function fetch()
	{
		$chains = [
			[$this->coreDefaultConfig],
			$this->style->getSystemConfig($this->appname, $this->getSupportedExtensions()), // @var array of string
			$this->style->getUserConfig($this->appname, $this->getSupportedExtensions()),
			$this->style->getLocalConfig($this->appname, $this->workdirectory, $this->getSupportedExtensions()),
		];
		$data = [];
		foreach ($chains as $uris) {
			$map = NULL;
			foreach ($uris as $uri) {
				if (empty($uri)) {
					continue;
				}
				$map = $this->resolve($uri);
				if ($map !== FALSE) {
					break;
				}
			}
			if ($map) {
				$data = $this->merge((array) $data, (array) $map);
			}
		}
		return $data;
	}



	/**
	 * @param string with file name.
	 * @return array
	 */
	private function resolve($uri)
	{
		Validators::assert($uri, 'string:1..', 'uri');

		// Zvolíme správný loader a parser. Loader slouží k získání zdroje.
		// Pravděpodobně to bude soubor, ale může to být i http.
		// Parser zase slouží podle typu souboru k parsování obsahu.
		$loader = $this->resolveLoaderFor($uri);

		// @var Resource
		if ( ! $resource = $loader->fetch($uri)) {
			return FALSE;
		}

		// Můžeme to určit podle koncovku, ale také podle obsahu.
		return $this->resolveParserFor($resource)->parse($resource);
	}



	/**
	 * @param array
	 * @param array
	 * @return array
	 */
	private function merge(array $orig, array $nuevo)
	{
		return array_merge($orig, $nuevo);
	}



	/**
	 * Like ['ini', 'conf', 'json'] parsing from Parsers.
	 * @return list of string
	 */
	private function getSupportedExtensions()
	{
		$exts = [];
		foreach ($this->parsers as $parser) {
			$exts = array_merge($exts, $parser->getExtensions());
		}
		return $exts;
	}



	/**
	 * @param string
	 * @return Loader
	 */
	private function resolveLoaderFor($uri)
	{
		return new FileLoader;
	}



	/**
	 * @return Parser
	 */
	private function resolveParserFor(Resource $resource)
	{
		$ext = pathinfo($resource->uri, PATHINFO_EXTENSION);
		foreach ($this->parsers as $parser) {
			if (in_array($ext, $parser->getExtensions(), TRUE)) {
				return $parser;
			}
		}
		throw new RuntimeException("Unsupported type of content for: `$ext'.");
	}

}
