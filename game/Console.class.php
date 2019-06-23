<?php

class Console
{
	private $_logs;

	private function __construct()
	{
		$this->_logs = array();
		$this->_logs[] = "Console started.";
	}

	private static function getCurrentInstance()
	{
		if (!array_key_exists("log", $_SESSION))
			$_SESSION["log"] = new Console();
		return $_SESSION["log"];
	}

	public static function clear()
	{
		$instance = self::getCurrentInstance();
		$instance->_logs = array();
	}

	public static function log_message($message)
	{
		$instance = self::getCurrentInstance();
		$instance->_logs[] = $message;
	}

	public static function log_debug($message)
	{
		self::log_message("DEBUG: " . $message);
	}

	public static function log_error($message)
	{
		self::log_message("ERROR: " . $message);
	}

	public static function log_to_HTML()
	{
		$instance = self::getCurrentInstance();
		$str = "";
		foreach ($instance->_logs as $message)
		{
			$type = (substr($message, 0, 5) == "ERROR") ? "log-err" : "log-msg";
//			if (substr($message, 0, 5) == "DEBUG")
//				continue;
			$str = "<p class=\"" . $type . "\">" . $message . "</p>" . $str . PHP_EOL;
		}
		return $str;
	}
}

?>
