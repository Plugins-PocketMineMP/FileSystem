<?php
declare(strict_types=1);
namespace alvin0319\FileSystem;

use alvin0319\FileSystem\exception\ContentEditException;
use function fclose;
use function file_get_contents;
use function fopen;
use function fwrite;
use function json_decode;
use function parse_ini_string;
use function pathinfo;
use function unlink;
use function yaml_parse;
use const PATHINFO_EXTENSION;

class File extends FileBase{

	public function check() : void{
	}

	/**
	 * @param bool $stdClass
	 * @return array|string return array if can parse, return string (raw data) if can't parse
	 * @throws ContentEditException
	 */
	public function getData(bool $stdClass = false){
		$ext = pathinfo($this->path, PATHINFO_EXTENSION);
		if($stdClass and $ext !== "json"){
			throw new ContentEditException("Cannot import non-JSON file in stdClass format.");
		}
		switch($ext){
			case "json":
				return json_decode(file_get_contents($this->path), !$stdClass);
			case "yaml":
			case "yml":
				return yaml_parse(file_get_contents($this->path));
			case "ini":
				return parse_ini_string(file_get_contents($this->path));
			default:
				return file_get_contents($this->path);
		}
	}

	public function addString(array $content = []) : File{
		$res = fopen($this->path, "w+");
		foreach($content as $str){
			fwrite($res, $str);
		}
		fclose($res);
		return $this;
	}

	public function unlink() : void{
		@unlink($this->path);
	}
}