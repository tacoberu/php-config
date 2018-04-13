<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;
use RuntimeException;


class JsonParserTest extends PHPUnit_Framework_TestCase
{

	private $parser;

	function setUp()
	{
		$this->parser = new JsonParser();
	}



	function testObjectToArray()
	{
		$res = new Resource(__dir__ . '/data/a.json', '{ "name": "John Dee" }');
		$this->assertEquals(['name' => 'John Dee'], $this->parser->parse($res));
	}



	function testArray()
	{
		$res = new Resource(__dir__ . '/data/a.json', '[ "name", "surname" ]');
		$this->assertEquals(['name', 'surname'], $this->parser->parse($res));
	}



	function testFailContent()
	{
		$this->setExpectedException(RuntimeException::class, 'ERROR parsing of ' . __dir__ . '/data/a.json: unexpected character.');
		$res = new Resource(__dir__ . '/data/a.json', '[ "name", "surname", ]');
		$this->parser->parse($res);
	}



	function testGetExtensions()
	{
		$this->assertEquals(['json'], $this->parser->getExtensions());
	}


}
