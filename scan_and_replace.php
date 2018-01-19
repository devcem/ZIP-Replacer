<?php

	//Check if there is output and temp folders
	if(is_dir('./output')){
		mkdir('output');
	}

	if(is_dir('./temp')){
		mkdir('temp');
	}

	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,basename($file));
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}

	function replaceFile($find, $replace, $url){
		global $code;
		global $folder;

		$file_type     = mime_content_type($url); //Get file type

		//Test if it's project file
		if($file_type == 'application/zip'){
			$zip = new ZipArchive;
			$res = $zip->open($url);

			if ($res === TRUE) {
				$zip->extractTo('./temp');
				$zip->close();

				$htmlFileName = glob('./temp/*.html');
				$htmlFileName = $htmlFileName[0];
				$htmlFile = file_get_contents($htmlFileName);
				$htmlFile = str_replace($find, $replace, $htmlFile);

				file_put_contents($htmlFileName, $htmlFile);
				$zipFilename = str_replace(array('.html', './temp'), array('.zip',''), $htmlFileName);
				echo $zipFilename."\n";

				$files = array_map(function($value){
					return str_replace('./', '', $value);
				}, glob('./temp/*'));

				print_r($files);

				create_zip($files, './output/'.$folder.'/'.$zipFilename);

				$files = glob('./temp/*'); // get all file names
				foreach($files as $file){ // iterate files
				  if(is_file($file))
				    unlink($file); // delete file
				}
			} else {
				$error = true;
			}
		}
	}

	function zipReplacer($find, $replace, $folderToScan){
		$files = glob($folderToScan.'/*.zip');

		foreach ($files as $key => $file) {
			replaceFile($find, $replace, $file);
		}
	}

	//Use function here
	zipReplacer('<!--Write your code here-->', '<script...', 'my_zip_files');