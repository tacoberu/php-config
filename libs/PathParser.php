<?php
/**
 * Copyright (c) since 2004 Martin Takáč
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\Config;

use Nette\Utils\AssertionException,
	Nette\Utils\Validators;


/**
 * Jednoduchý způsob na dotazování se klíčů.
 */
class PathParser
{

	/**
	 * @param string 'name', 'foo.boo', 'list[5].item'
	 * @return array of string
	 */
	static function parse($path)
	{
		Validators::assert($path, 'string:1..', 'path');
		$xs = [];
		foreach (explode('].', $path) as $frg1) {
			foreach (explode('[', $frg1) as $frg2) {
				foreach (explode('.', $frg2) as $x) {
					$x = rtrim($x, ']');
					self::assertFormat($x, $path);
					$xs[] = $x;
				}
			}

		}
		return $xs;
	}



	/**
	 * @param string
	 * @param string
	 */
	private static function assertFormat($m, $path)
	{
		if (strpos($m, ']') !== FALSE) {
			throw new AssertionException("Format of path is not correct: `$path'.");
		}
		if (strpos($m, '/') !== FALSE) {
			throw new AssertionException("Format of path is not correct: `$path'.");
		}
	}

}
