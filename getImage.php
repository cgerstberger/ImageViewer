<?php
    $curDir = getcwd() . "/images";
    $files = scandir($curDir);
    $nrFiles = count($files);
    $files = array_slice($files, 2, $nrFiles);
    $serverAdr = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/ImageViewer/trunk/images";
    $resultArr;
    $j = 0;
    for ($i=0; $i < count($files); $i++) {
        $file = $files[$i];
        if(!endsWith($file, ".txt"))
        {
            $urlFileName = $serverAdr . "/" . $file;
            $fileNameInServer = $curDir . "/" . $file;
            $pointIdx = strrpos($fileNameInServer, ".");
            $fileNameInServerWithoutEnding = substr($fileNameInServer, 0, $pointIdx);
            
            $correspondingTextFile = $fileNameInServerWithoutEnding . ".txt";
            $infoArr[0] = $urlFileName;
            $infoArr = readTextFileAndAddToArray($correspondingTextFile, $infoArr);
            $resultArr[$j] = $infoArr;
            $j++;
            //$fileDict[$urlFileName] = $infoArr;
        }
    }
    print(array_to_json($resultArr));
    //print_r(array_to_json($fileDict));


    function endsWith($str, $ending){
        $strEnding = substr($str, -strlen($ending));
        return $strEnding === $ending;
    }

    function readTextFileAndAddToArray($file, $infoArr){
        $fileHandle = @fopen($file, "r");
        if($fileHandle == false)
            return $infoArr;
        for ($i=1; !feof($fileHandle); $i++) { 
            $infoArr[$i] = fgets($fileHandle);
        }
        fclose($fileHandle);
        return $infoArr;
    }





    /** 
* Converts an associative array of arbitrary depth and dimension into JSON representation. 
* 
* NOTE: If you pass in a mixed associative and vector array, it will prefix each numerical 
* key with "key_". For example array("foo", "bar" => "baz") will be translated into 
* {"key_0": "foo", "bar": "baz"} but array("foo", "bar") would be translated into [ "foo", "bar" ]. 
* 
* @param $array The array to convert. 
* @return mixed The resulting JSON string, or false if the argument was not an array. 
* @author Andy Rusterholz 
*/ 
function array_to_json( $array ){ 
    
        if( !is_array( $array ) ){ 
            return false; 
        } 
    
        $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
        if( $associative ){ 
    
            $construct = array(); 
            foreach( $array as $key => $value ){ 
    
                // We first copy each key/value pair into a staging array, 
                // formatting each key and value properly as we go. 
    
                // Format the key: 
                if( is_numeric($key) ){ 
                    $key = "key_$key"; 
                } 
                $key = '"'.addslashes($key).'"'; 
    
                // Format the value: 
                if( is_array( $value )){ 
                    $value = array_to_json( $value ); 
                } else if( !is_numeric( $value ) || is_string( $value ) ){ 
                    $value = '"'.addslashes($value).'"'; 
                } 
    
                // Add to staging array: 
                $construct[] = "$key: $value"; 
            } 
    
            // Then we collapse the staging array into the JSON form: 
            $result = "{ " . implode( ", ", $construct ) . " }"; 
    
        } else { // If the array is a vector (not associative): 
    
            $construct = array(); 
            foreach( $array as $value ){ 
    
                // Format the value: 
                if( is_array( $value )){ 
                    $value = array_to_json( $value ); 
                } else if( !is_numeric( $value ) || is_string( $value ) ){ 
                    $value = '"'.addslashes($value).'"'; 
                } 
    
                // Add to staging array: 
                $construct[] = $value; 
            } 
    
            // Then we collapse the staging array into the JSON form: 
            $result = "[ " . implode( ", ", $construct ) . " ]"; 
        } 
    
        return $result; 
    } 
?>

