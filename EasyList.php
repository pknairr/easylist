<?php
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
	die('PHP ActiveRecord requires PHP 5.3 or higher');

define('PHP_EASYLIST_VERSION_ID','1.0');

if (!defined('PHP_EASYLIST_AUTOLOAD_PREPEND'))
	define('PHP_EASYLIST_AUTOLOAD_PREPEND',true);

require __DIR__.'/lib/Adaptor.php';
require __DIR__.'/lib/List.php';

