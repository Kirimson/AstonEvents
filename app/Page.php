<?php
/**
 * Created by PhpStorm.
 * User: kiera
 * Date: 25/03/2018
 * Time: 21:01
 */

namespace App;


class Page
{
	public $name;
	public $route;

	function __construct($name, $route)
	{
		$this->name=$name;
		$this->route=$route;
	}
}