<?php

// herokuモード
class Log extends \Fuel\Core\Log{
	public static function _init(){
		parent::_init();
		static::herokuHandler();
	}

	public static function herokuHandler(){
		$stream = new \Monolog\Handler\StreamHandler("php://stderr", \Monolog\Logger::DEBUG);
		$formatter = new \Monolog\Formatter\LineFormatter("%level_name% - %datetime% --> %message%".PHP_EOL, "Y-m-d H:i:s");
		$stream->setFormatter($formatter);
		static::$monolog->pushHandler($stream);
	}
}
