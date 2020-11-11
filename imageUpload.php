<?php
	require 'vendor/autoload.php';
	use \Gumlet\ImageResize;
	
	// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       // Build an array of paths segment names to be joins using OS specific slashes.
       $path_segments = [$current_folder, $upload_subfolder_name, mt_rand(0,1000) . basename($original_filename)];
       
       // The DIRECTORY_SEPARATOR constant is OS specific.
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = strtolower(pathinfo($new_path, PATHINFO_EXTENSION));
        $actual_mime_type        = mime_content_type($temporary_path);
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }

    // resize the image to specified size, and save as another 
    // appropriate name based on size in the current folder. 
    function resize($newTempName, $size){
        $imageMedium = new ImageResize($newTempName);
        $imageMedium->resizeToWidth($size);
        $imageMedium->save(imageVersionName($newTempName, $size));
    }

    // Get the appropriate name based on size.
    function imageVersionName($filePath, $size = 'original'){
        $fileName = basename($filePath);
        $directoryName = dirname($filePath);    

        if ($size == 400) {
            $fileName = "Medium_" . $fileName;
        }elseif ($size == 50) {
            $fileName = "Thumbnail_" . $fileName;
        }

        $path_segments = [$directoryName, $fileName];
        return join(DIRECTORY_SEPARATOR, $path_segments);
    }
?>