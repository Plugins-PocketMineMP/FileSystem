<?php
declare(strict_types=1);
namespace alvin0319\FileSystem;

use alvin0319\FileSystem\exception\FileNotFoundException;
use function is_dir;
use function is_file;
use function pathinfo;
use function realpath;
use const PATHINFO_DIRNAME;
use const PATHINFO_FILENAME;

class FileSystem{

	/**
	 * @param string $dir
	 * @return Folder
	 * @throws FileNotFoundException
	 */
	public static function fromDir(string $dir) : Folder{
		$dir = realpath($dir);
		if(!is_dir($dir)){
			throw new FileNotFoundException("Couldn't find dir $dir");
		}
		return new Folder(pathinfo($dir, PATHINFO_DIRNAME), $dir);
	}

	/**
	 * @param string $file
	 * @return File
	 * @throws FileNotFoundException
	 */
	public static function fromFile(string $file) : File{
		$file = realpath($file);
		if(!is_file($file)){
			throw new FileNotFoundException("Couldn't find file $file");
		}
		return new File(pathinfo($file, PATHINFO_FILENAME), $file);
	}
}