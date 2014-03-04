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
            $step = Input::query('step');
            
            //define the valid steps
            $valid_steps = array('start', 'install_db', 'install_admin', 'run', 'success');
            
            //see if we have a valid step defined
            if (!in_array($step, $valid_steps))
            {
                $step = 'start';
            }
            
            $this->step = $step;
            
            $method = strtolower(Request::method());
            $action = $method.'_'.$step;
            
            return $this->$action();
            
	}
        
        public function get_start()
        {
            return View::make('whoisdoma_installer::start');
        }
        
        public function get_install_db()
        {
            return View::make('whoisdoma_installer::install_db');
        }
        
        
        public function post_install_db()
        {
            $rules = array(
                'db_host' => 'required',
                'db_name' => 'required',
                'db_user' => 'required'
            );
            
            if (!$this->validate($rules))
            {
                return $this->redirectBack();
            }
            
            $db_conf = array(
                'driver' => 'mysql',
                'host' => Input::get('db_host'),
                'database' => Input::get('db_name'),
                'username' => Input::get('db_user'),
                'password' => Input::get('db_pass'),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => Input::get('db_prefix'),
            );
            
            $this->remember('db_conf', $db_conf);
            
            //redirect to next step
            $this->redirectTo('install_admin');
        }
        
        public function get_install_admin()
        {
            return View::make('whoisdoma_installer::install_admin');
        }
        
        public function post_install_admin()
        {
            $rules = array(
                'admin_username' => 'required',
                'admin_email' => 'required|email',
                'admin_pass' => 'required'
            );
            
            if (!$this->validate($rules))
            {
                return $this->redirectBack();
            }
            
            $user_info = array(
                'username' => Input::get('admin_username'),
                'email' => Input::get('admin_email'),
                'password' => Input::get('admin_pass'),
            );
            
            $this->remember('admin', $user_info);
            
            $this->redirectTo('run');
            
        }
        
        public function get_run()
        {
            return View::make('whoisdoma_installer::run');
        }
        
        public function post_run()
        {
            $installer = new Installer(app());
            
            $db = $this->retrieve('db_conf');
            
            // Tell the database to use this connection
            Config::set('database.connections.whoisdoma', $db);
            DB::setDefaultConnection('whoisdoma');
            
            $installer->writeDatabaseConfig($db);
            
            //create the database tables
            $installer->createDatabaseTables();
            
            $admin = $this->retrieve('admin');
            
            //add the admin user
            $installer->createAdminUser($admin);
            
            return $this->redirectTo('success');
        }
        
        public function get_success()
        {
            return View::make('whoisdoma_installer::success');
        }

        protected function validate(array $rules)
	{
		$this->validation = Validator::make(Input::all(), $rules);
		return $this->validation->passes();
	}

	protected function redirectTo($step)
	{
		return Redirect::to(Request::url().'?step='.$step);
	}

	protected function redirectBack()
	{
		return $this->redirectTo($this->step)
			->withInput(Input::all())
			->withErrors($this->validation->getMessageBag());
	}

	protected function remember($key, $value)
	{
		Session::put('whoisdoma.install.'.$key, $value);
	}

	protected function has($key)
	{
		return Session::has('whoisdoma.install.'.$key);
	}

	protected function retrieve($key)
	{
		return Session::get('whoisdoma.install.'.$key);
	}
}