<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette,
	Nette\Utils\Validators;


/**
 * Načtený soubor. Krom vlastních dat, které mohou být lazy, hlavně obsahuje
 * referenci na zdrojový soubor jako součást informace. Je třeba pro
 * relativní odkazování.
 *
 * @property-read string $uri
 * @property-read string $data
 */
class Resource
{

	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $uri;

	/**
	 * @var string
	 */
	private $data;


	/**
	 * @param string
	 * @param string
	 */
	function __construct($uri, $data)
	{
		Validators::assert($uri, 'string:1..', 'uri');
		Validators::assert($data, 'string:1..', 'data');
		$this->uri = $uri;
		$this->data = $data;
	}



	/**
	 * @return string
	 */
	function getUri()
	{
		return $this->uri;
	}



	/**
	 * @return string
	 */
	function getData()
	{
		return $this->data;
	}

}
