<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;


/**
 * Platformově závislé informace kde hledat která data.
 */
interface PlatformStyle
{

	/**
	 * @param string
	 * @param list of string
	 * @return list of filepath
	 */
	function getSystemConfig($appname, array $exts);



	/**
	 * @param string
	 * @param list of string
	 * @return list of filepath
	 */
	function getUserConfig($appname, array $exts);



	/**
	 * @param string
	 * @param string
	 * @param list of string
	 * @return list of filepath
	 */
	function getLocalConfig($appname, $workDirectory, array $exts);

}



interface Parser
{

	/**
	 * @return array
	 */
	function parse(Resource $res);



	/**
	 * @return array of string
	 */
	function getExtensions();

}
