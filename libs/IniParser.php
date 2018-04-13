<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;


class IniParser implements Parser
{

	/**
	 * @return array
	 */
	function getExtensions()
	{
		return ['ini', 'conf'];
	}



	/**
	 * @return array
	 */
	function parse(Resource $res)
	{
		return parse_ini_string($res->getData(), TRUE, INI_SCANNER_RAW);
	}

}
