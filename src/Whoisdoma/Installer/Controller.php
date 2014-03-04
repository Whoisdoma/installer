<?php

/**
*
* @category	Whoisdoma
* @package	Installer
* @copyright (c) 2013 XAOS Interactive
*
*/

namespace Whoisdoma\Installer;

use Whoisdoma\Controllers\BaseController;
use Config;
use DB;
use Input;
use Redirect;
use Request;
use Session;
use Validator;
use View;

class Controller extends BaseController
{
	protected $step;

	protected $validation;
	
	public function run()
	{
	
	}
}