<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Exception;
use Nette\Utils\Json,
	Nette\Utils\Validators;
use RuntimeException;


class JsonParser implements Parser
{

	/**
	 * @return list of string
	 */
	function getExtensions()
	{
		return ['json'];
	}



	/**
	 * @return array
	 */
	function parse(Resource $res)
	{
		try {
			return Json::decode($res->getData(), Json::FORCE_ARRAY);
		}
		catch (Exception $e) {
			throw new RuntimeException("ERROR parsing of {$res->uri}: {$e->getMessage()}.");
		}
	}

}
