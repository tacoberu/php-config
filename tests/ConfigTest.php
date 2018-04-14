<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use PHPUnit_Framework_TestCase;
use Mockista;
use LogicException;


class ConfigTest extends PHPUnit_Framework_TestCase
{

	/** @var Mockista\Registry */
	private $mockista;
	private $config;

	protected function setUp()
	{
		$this->mockista = new Mockista\Registry();
		$resolver = $this->mockista->create(Resolver::class);
		$resolver->expects('fetch')->andReturn([
			'name' => 'Andreaw Fean',
			'core' => [
				'version' => 4,
				'name' => 'App'
			],
			'langs' => [
				'cs',
				'de',
				'jp',
			],
		]);
		$this->config = new Config($resolver);
	}



	protected function tearDown()
	{
		$this->mockista->assertExpectations();
	}



	function testCorrect()
	{
		$this->assertTrue($this->config->has('name'));
		$this->assertFalse($this->config->has('nope'));
		$this->assertEquals('Andreaw Fean', $this->config->required('name'));
		$this->assertEquals('Andreaw Fean', $this->config->get('name'));
		$this->assertNull($this->config->get('nope'));
		$this->assertEquals(42, $this->config->get('nope', 42));
		$this->assertEquals(4, $this->config->required('core.version'));
		$this->assertEquals([
			'version' => 4,
			'name' => 'App'
		], $this->config->required('core'));
		$this->assertEquals([
			'cs',
			'de',
			'jp',
		], $this->config->required('langs'));
		$this->assertEquals('de', $this->config->required('langs[1]'));
	}



	function testFail()
	{
		$this->setExpectedException(LogicException::class, 'Path `nope\' is not found.');
		$this->config->required('nope');
	}



	function _testCreateByEnv()
	{
		$config = Config::createByEnvironment('demn', __dir__);
		dump($config);
	}

}
