<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;


class PlatformStyleWinTest extends PHPUnit_Framework_TestCase
{

	function testConstruct()
	{
		$style = new PlatformStyleWin('mia');
		$this->assertEquals(['C:/Windows/system32/doe/config.ini'], $style->getSystemConfig('doe', ['ini']));
		$this->assertEquals(['C:/Users/mia/.config/doe/config.ini'], $style->getUserConfig('doe', ['ini']));
		$this->assertEquals(['./doe.ini'], $style->getLocalConfig('doe', '.', ['ini']));
		$this->assertEquals(['./doe.ini'], $style->getLocalConfig('doe', './', ['ini']));
	}


}
