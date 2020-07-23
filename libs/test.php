<?php
declare(strict_types=1);

$start = microtime(true);

require "autoloader.php";

$folder = \alvin0319\FileSystem\FileSystem::fromDir("../../bin");

foreach($folder->getContents() as $content){
	if($content instanceof \alvin0319\FileSystem\Folder){
		echo "Folder detected: " . $content->getPath() . "\n";
	}elseif($content instanceof \alvin0319\FileSystem\File){
		echo "File detected: " . $content->getPath() . "\n";
	}
}

//var_dump($folder);

$anotherFolder = $folder->mkdir("testFolder");

$anotherFile = $anotherFolder->makeFile("test.json");

$anotherFile->addString([json_encode(["test" => "ha"])]);

echo "Succeed to create testFolder.\n";

var_dump($anotherFolder);

try{
	$anotherFolder->clearFiles();
}catch(\alvin0319\FileSystem\exception\UnSupportedMethodException $e){
	echo "Failed to clear files: " . $e->getMessage() . "\n";
}

echo "Succeed to rmdir testFolder\n";

$file = \alvin0319\FileSystem\FileSystem::fromFile(__FILE__);

var_dump($file);

echo "Succeed to load " . __FILE__ . "\n";

$end = microtime(true);

$result = ($end - $start);

echo "Script end in " . number_format($result) . " seconds.\n";