<?php
/**
 * User: Kieran Gates
 * Date: 26/03/2018
 * Time: 17:31
 */

namespace App;

/**
 * Class Category
 *
 * A collection of Page classes, which also extends page itself, to be store in a navigation bar in a View
 *
 * @package App
 */
class Category extends Page
{
	public $pages;

	function __construct($name, $route, $pages)
	{
		parent::__construct($name, $route);
		$this->pages=$pages;
	}
}