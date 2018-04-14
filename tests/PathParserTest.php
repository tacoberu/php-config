<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;
use Mockista;
use Nette\Utils\AssertionException;


class PathParserTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider dataFail
	 */
	function testFail($path, $expected)
	{
		$this->setExpectedException(AssertionException::class, $expected);
		PathParser::parse($path);
	}



	/**
	 * @dataProvider dataParser
	 */
	function testParser($path, $expected)
	{
		$this->assertEquals($expected, PathParser::parse($path));
	}



	function dataParser()
	{
		return [
			['abc',
				['abc']],
			['abc.foo',
				['abc', 'foo']],
			['abc[1].foo',
				['abc', '1', 'foo']],
			['abc[1]',
				['abc', '1']],
		];
	}



	function dataFail()
	{
		return [
			['',
				'The path expects to be string in range 1.., string \'\' given.'],
			['abc[1]foo',
				'Format of path is not correct: `abc[1]foo\'.'],
			['abc/foo',
				'Format of path is not correct: `abc/foo\'.'],
		];
	}

}
