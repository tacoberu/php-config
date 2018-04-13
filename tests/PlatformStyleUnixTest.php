<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;


class PlatformStyleUnixTest extends PHPUnit_Framework_TestCase
{

	function testConstruct()
	{
		$style = new PlatformStyleUnix('mia');
		$this->assertEquals(['/etc/doe/config.ini'], $style->getSystemConfig('doe', ['ini']));
		$this->assertEquals(['/home/mia/.config/doe/config.ini'], $style->getUserConfig('doe', ['ini']));
		$this->assertEquals(['./doe.ini'], $style->getLocalConfig('doe', '.', ['ini']));
		$this->assertEquals(['./doe.ini'], $style->getLocalConfig('doe', './', ['ini']));
	}


}
