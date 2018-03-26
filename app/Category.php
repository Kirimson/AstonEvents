<?php
/**
 * Created by PhpStorm.
 * User: kiera
 * Date: 26/03/2018
 * Time: 17:31
 */

namespace App;


class Category extends Page
{
	public $pages;

	function __construct($name, $route, $pages)
	{
		parent::__construct($name, $route);
		$this->pages=$pages;
	}
}