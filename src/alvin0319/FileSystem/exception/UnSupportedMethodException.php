<?php
declare(strict_types=1);
namespace alvin0319\FileSystem\exception;

class UnSupportedMethodException extends FileException{

	public static function wrap(string $funcName) : UnSupportedMethodException{
		return new UnSupportedMethodException("Function \"" . $funcName . "\" isn't implemented yet");
	}
}