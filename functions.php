<?php
	function changeToThreeRelativePath($path){
		$fileName = basename($path);
		$output = [ 'thumbnail' => 'uploads' . DIRECTORY_SEPARATOR . 'Thumbnail_' . $fileName,
					'medium' => 'uploads' . DIRECTORY_SEPARATOR .  'Medium_' . $fileName,
					'origin' => 'uploads' . DIRECTORY_SEPARATOR . $fileName
					];
		return $output;
	}
?>