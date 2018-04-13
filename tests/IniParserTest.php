<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;
use RuntimeException;


class IniParserTest extends PHPUnit_Framework_TestCase
{

	private $parser;

	function setUp()
	{
		$this->parser = new IniParser();
	}



	function testObjectToArray()
	{
		$res = new Resource(__dir__ . '/data/a.conf', '
version = 4

; comment
# comment

[simple]
text = "some value"
num = 567
yesno = off

[array]
arr[] = "arr_elem_one"
arr[] = "arr_elem_two"
arr[] = "arr_elem_three"

[array_keys]
tup[6] = "key_6"
tup[some_key] = "some_key_value"
		');
		$this->assertEquals([
			'version' => '4',
			'simple' => [
				'text' => 'some value',
				'num' => '567',
				'yesno' => 'off',
			],
			'array' => [
				'arr' => [
					'arr_elem_one',
					'arr_elem_two',
					'arr_elem_three',
				],
			],
			'array_keys' => [
				'tup' => [
					6 => 'key_6',
					'some_key' => 'some_key_value',
				],
			],
		], $this->parser->parse($res));
	}



	function testGetExtensions()
	{
		$this->assertEquals(['ini', 'conf'], $this->parser->getExtensions());
	}


}
