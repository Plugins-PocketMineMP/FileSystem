<?php
declare(strict_types=1);
namespace alvin0319\FileSystem;

use alvin0319\FileSystem\exception\ContentEditException;
use function fclose;
use function file_get_contents;
use function fopen;
use function fwrite;
use function in_array;
use function json_decode;
use function parse_ini_string;
use function pathinfo;
use function unlink;
use function yaml_parse;
use const PATHINFO_EXTENSION;

class File extends FileBase{

	public function check() : void{
	}

	public function canEdit() : bool{
		$ext = pathinfo($this->path, PATHINFO_EXTENSION);
		return in_array($ext, [
			"json",
			"yml",
			"yaml",
			"ini"
		]);
	}

	/**
	 * @param bool $stdClass
	 * @return array
	 * @throws ContentEditException
	 */
	public function getData(bool $stdClass = false) : array{
		if(!$this->canEdit()){
			throw new ContentEditException("Cannot edit this file");
		}
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
				throw new ContentEditException("Cannot edit this file");
		}
	}

	public function setData(string $content) : File{
		$res = fopen($this->path, "w+");
		fwrite($res, $content);
		fclose($res);
		return $this;
	}

	public function unlink() : void{
		@unlink($this->path);
	}
}