<?php
declare(strict_types=1);
namespace alvin0319\FileSystem;

use function realpath;

abstract class FileBase{

	/** @var string */
	protected $name;

	/** @var string */
	protected $path;

	public function __construct(string $name, string $path){
		$this->name = $name;
		$path = realpath($path);
		$this->path = $path;

		$this->check();
	}

	abstract public function check() : void;

	public function getPath() : string{
		return $this->path;
	}

	public function getName() : string{
		return $this->name;
	}
}