<?php

function classLoader($className) {
	$base = $_SERVER['DOCUMENT_ROOT'];

	$path = $className;

	$file = $base . "/prr/classes/" . $path . '.php';
	// echo $file."<br>";

	if (file_exists($file)) 
	{
		include_once $file;
	}
	else 
	{
		error_log('Class "' . $className . '" could not be autoloaded');
	}
}

spl_autoload_register('classLoader');

