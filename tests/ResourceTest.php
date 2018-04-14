<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;


class ResourceTest extends PHPUnit_Framework_TestCase
{


	function testConstruct()
	{
		$m = new Resource(__dir__ . '/data/a.json', '{}');
		$this->assertEquals($m->uri, __dir__ . '/data/a.json');
		$this->assertEquals($m->data, '{}');
	}


}
