<?php
declare(strict_types=1);
namespace alvin0319\FileSystem;

use alvin0319\FileSystem\exception\UnSupportedMethodException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function fclose;
use function is_dir;
use function mkdir;
use function rmdir;
use function substr;
use function unlink;
use const DIRECTORY_SEPARATOR;

class Folder extends FileBase{

	protected $files = [];

	public function __construct(string $name, string $path){
		parent::__construct($name, $path);
		if(substr($path, -1) !== DIRECTORY_SEPARATOR)
			$path .= DIRECTORY_SEPARATOR;
		$this->path = $path;
	}

	public function check() : void{
		$directoryIterator = new RecursiveDirectoryIterator($this->path);
		$directoryIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator(
			$directoryIterator,
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		/** @var SplFileInfo $file */
		foreach($files as $file){
			if($file->isDir()){
				$this->files[] = new Folder($file->getBasename(), $file->getRealPath());
			}else{
				$this->files[] = new FIle($file->getBasename(), $file->getRealPath());
			}
		}
	}

	public function clearFiles() : void{
		/*
		$directoryIterator = new RecursiveDirectoryIterator($this->path);
		$directoryIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);

		$files = new RecursiveIteratorIterator(
			$directoryIterator,
			RecursiveIteratorIterator::LEAVES_ONLY
		);
		$this->recursiveRmdir($files);

		$this->check();
		*/
		throw UnSupportedMethodException::wrap(__FUNCTION__);
	}

	private function recursiveRmdir(RecursiveIteratorIterator $iterator) : void{
		/** @var SplFileInfo $file */
		foreach($iterator as $file){
			if($file->isDir()){
				unlink($file->getRealPath());
			}elseif(is_dir($file->getRealPath())){
				$directoryIterator = new RecursiveDirectoryIterator($file->getRealPath());
				$directoryIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
				$this->recursiveRmdir(
					new RecursiveIteratorIterator(
						$directoryIterator,
						RecursiveIteratorIterator::LEAVES_ONLY
					)
				);
				rmdir($file->getRealPath());
			}
		}
	}

	public function mkdir(string $dirName) : Folder{
		@mkdir($this->path . $dirName);
		return $this->files[] = new Folder($dirName, $this->path . $dirName);
	}

	public function makeFile(string $fileName) : File{
		$res = fopen($this->path . $fileName, "w+");
		fclose($res);
		return $this->files[] = new File($fileName, $this->path . $fileName);
	}

	/**
	 * @return array<File|Folder>
	 */
	public function getContents() : array{
		return $this->files;
	}
}