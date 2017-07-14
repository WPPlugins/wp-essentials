<?php
	$link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	mysql_select_db(DB_NAME,$link);
	$tables = array();
	$result = mysql_query('SHOW TABLES');
	$return = '';
	
	while ($row = mysql_fetch_row($result)) {
		$tables[] = $row[0];
	}
	
	foreach ($tables as $table) {
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);

		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
	
		for ($i = 0; $i < $num_fields; $i++)  {
			while ($row = mysql_fetch_row($result)) {
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for ($j=0; $j<$num_fields; $j++) {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = preg_replace("#\n#", "\\n", $row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	function zip($source, $destination) {
		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}
	
		$source = str_replace('\\', '/', realpath($source));
	
		if (is_dir($source) === true) {
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	
			foreach ($files as $file) {
				$file = str_replace('\\', '/', $file);
	
				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;
	
				$file = realpath($file);
	
				if (is_dir($file) === true) {
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true) {
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true) {
			$zip->addFromString(basename($source), file_get_contents($source));
		}
	
		return $zip->close();
	}
	
	$date = date("Ymd_His");
	
	if (!file_exists('zips')) {
		mkdir('zips', 0755);
	}
	$handle = fopen('zips/'.$date.'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
	
	zip('zips/'.$date.'.sql','zips/'.$date.'.zip');
		
	$file = "zips/".$date.".zip";
	$header = "";
?>