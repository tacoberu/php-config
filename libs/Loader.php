<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette\Utils\Validators;


class FileLoader
{

	/**
	 * @param string
	 * @return Resource
	 */
	function fetch($file)
	{
		Validators::assert($file, 'string:1..');
		if ( ! file_exists($file)) {
			return NULL;
		}
		return new Resource($file, file_get_contents($file));
	}

}
