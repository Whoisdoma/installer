<?php

/**
*
* @category	Whoisdoma
* @package	Installer
* @copyright (c) 2013 XAOS Interactive
*
*/

namespace Whoisdoma\Installer;

use Illuminate\Support\Facades\DB;
use Eloquent;
use Whoisdoma\Core;
use Whoisdoma\Models\User;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;


class Installer 
{
    protected $container;
    
    public function __construct(Container $app) 
    {
        $this->container = $app;
        
        //makesure we can create the data
        Eloquent::unguard();
    }
    
    public function writeDatabaseConfig(array $configuration)
    {
        $config = array('database' => $configuration, 'route_prefix' => '');
        
        $confDump = '<?php'."\n\n".'return '.var_export($config, true).';'."\n";
	$confFile = $this->container['path'].'/config/whoisdoma.php';

	$success = $this->container['files']->put($confFile, $confDump);

	if (!$success)
	{
            throw new RuntimeException('Unable to write config file. Please create the file "'.$confFile.'" with the following contents:'."\n\n".$config);
	}
    }
    
    public function createDatabaseTables()
    {
        $migrationClasses = array(
            'Whoisdoma\Migrations\Install\ApiKeys',
            'Whoisdoma\Migrations\Install\Users',
            'Whoisdoma\Migrations\Install\WhoisServers',
        );
        
        foreach($migrationClasses as $class)
        {
            $instance = new $class;
            $instance->up();
        }
        
    }
    
    public function createAdminUser(array $user)
    {
        $adminUser = new User(array(
            'username' => $user['username'],
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
        ));
        
        $adminUser->save();
    }
    

}

