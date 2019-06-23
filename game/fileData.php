<?php

function load_data($filename, &$file_data)
{
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	$file_path = $root . "/private/" . $filename;
	if (file_exists($file_path))
	{
		$contents = file_get_contents($file_path);
		if ($contents === FALSE)
		{
			$file_data = array();
			return FALSE;
		}
		$file_data = unserialize($contents);
	}
	else
		$file_data = array();
	return TRUE;
}

function save_data($filename, $file_data)
{
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	$file_path = $root . "/private/" . $filename;
	if (!file_exists($root . "/private/"))
		if (mkdir($root . "/private/") === FALSE)
			return FALSE;
//	if (file_put_contents($file_path, serialize($file_data), LOCK_EX) === FALSE)
	if (file_put_contents($file_path, serialize($file_data)) === FALSE)
		return FALSE;
	return TRUE;
}

?>
