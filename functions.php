<?php
	function changeToThreeRelativePath($path){
		$fileName = basename($path);
		$output = [ 'thumbnail' => 'uploads' . DIRECTORY_SEPARATOR . 'Thumbnail_' . $fileName,
					'medium' => 'uploads' . DIRECTORY_SEPARATOR .  'Medium_' . $fileName,
					'origin' => 'uploads' . DIRECTORY_SEPARATOR . $fileName
					];
		return $output;
	}

	function isActive($currentPage){
		$path = explode('?', $_SERVER['REQUEST_URI']);
		$currentUrl = basename($path[0]);
		if (gettype($currentPage) === 'string') {
			if (strtolower($currentUrl) === strtolower($currentPage)) {
				echo 'active';
			}
		}else{
			foreach ($currentPage as $value) {
				if (strtolower($currentUrl) === strtolower($value)) {
					echo 'active';
					break;
				}
			}
		}
   }

   function ifShowUserInfo($user, $fieldName, $showUserInfo){
		try {
			if ($showUserInfo && !empty($user[$fieldName])) {
				if ($fieldName === 'address') {
					return $user[$fieldName];
				}else{
					return 'value="' . $user[$fieldName] . '"';
				}
			}
		} catch (Exception $e) {
			return '';
		} 		
   }
?>