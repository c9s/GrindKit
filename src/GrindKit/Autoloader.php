<?php
/*
 * This file is part of the GrindKit package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace GrindKit;

class Autoloader 
{
	public $basedir;

	function loadclass($class)
	{
		$path = $this->basedir . DIRECTORY_SEPARATOR .  str_replace('\\',DIRECTORY_SEPARATOR,$class). '.php';
		require $path;
	}

	function load()
	{
		$this->basedir = dirname(dirname(__FILE__));
		spl_autoload_register( array($this,'loadclass') );
	}
}

$loader = new Autoloader;
$loader->load();
