<?php
/**
 * User: Kieran Gates
 * Date: 25/03/2018
 * Time: 21:01
 */

namespace App;

/**
 * Class Page
 * Page in the system, contains a route to go to and a name, used in navigation bar
 * @package App
 */
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