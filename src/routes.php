<?php

/**
*
* @category	Whoisdoma
* @package	Installer
* @copyright (c) 2013 XAOS Interactive
*
*/

View::addNamespace('whoisdoma_installer', __DIR__.'/views/');

Route::any('install', 'Whoisdoma\Installer\Controller@run');
