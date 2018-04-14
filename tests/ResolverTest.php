<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;
use Mockista;


class ResolverTest extends PHPUnit_Framework_TestCase
{

	/** @var Mockista\Registry */
	private $mockista;

	protected function setUp()
	{
		$this->mockista = new Mockista\Registry();
	}



	protected function tearDown()
	{
		$this->mockista->assertExpectations();
	}



	function testConstruct()
	{
		$style = $this->mockista->create(PlatformStyle::class);
		$style->expects('getSystemConfig')->andReturn([__dir__ . '/data/system.json']);
		$style->expects('getUserConfig')->andReturn([__dir__ . '/data/user.json']);
		$style->expects('getLocalConfig')->andReturn([__dir__ . '/data/local.json']);
		$resolver = new Resolver($style, 'doe', null, '.');
		$this->assertEquals([ 'name' => 'Andreaw Fean' ], $resolver->fetch());
	}



	function testDefaultValues()
	{
		$style = $this->mockista->create(PlatformStyle::class);
		$style->expects('getSystemConfig')->andReturn([__dir__ . '/data/system.json']);
		$style->expects('getUserConfig')->andReturn([__dir__ . '/data/user.json']);
		$style->expects('getLocalConfig')->andReturn([__dir__ . '/data/local.json']);
		$resolver = new Resolver($style, 'doe', __dir__ . '/data/default.json', '.');
		$this->assertEquals([
			'name' => 'Andreaw Fean',
			'version' => 4,
			"app" => "Doe",
		], $resolver->fetch());
	}



	function testExtends()
	{
		$style = $this->mockista->create(PlatformStyle::class);
		$style->expects('getSystemConfig')->andReturn([__dir__ . '/data2/system.json']);
		$style->expects('getUserConfig')->andReturn([__dir__ . '/data2/user.json']);
		$style->expects('getLocalConfig')->andReturn([__dir__ . '/data2/local.json']);
		$resolver = new Resolver($style, 'doe', __dir__ . '/data2/default.json', '.');
		$this->assertEquals([
			'name' => 'Andreaw Fean',
			'version' => 3,
			"app" => "Doe",
			"items" => [2],
			"items-with-extends" => [1,2,3,4,3],
		], $resolver->fetch());
	}



	function testExtends2()
	{
		$style = $this->mockista->create(PlatformStyle::class);
		$style->expects('getSystemConfig')->andReturn([__dir__ . '/data2/system.json']);
		$style->expects('getUserConfig')->andReturn([__dir__ . '/data2/user.json']);
		$style->expects('getLocalConfig')->andReturn([__dir__ . '/data2/local.json']);
		$resolver = new Resolver($style, 'doe', __dir__ . '/data2/default-x.json', '.');
		$this->assertEquals([
			'name' => 'Andreaw Fean',
			'version' => 3,
			"items" => [2],
			"items-with-extends" => [4,3],
		], $resolver->fetch());
	}

}
