<?php
namespace wl_socialstream;

class PathHelper {

    /**
     * Replaces / and \ with DIRECTORY_SEPARATOR for compatibility reasons
     * @param string $path path to be corrected.
     * @return string corrected path.
     */    
	public static function GetFilePathCorrected( $path ) {

        $path = str_replace('/',DIRECTORY_SEPARATOR,$path);
        $path = str_replace('\\',DIRECTORY_SEPARATOR,$path);
        return  $path;

	}

    /**
     * Combines 2 paths. Checks for trailing slashes
     * @param string $path1 First part of the path
     * @param string $path2 Second part of the path
     * @return string combined path
     */    
    public static function PathCombine( $path1,$path2 ) {
        if(StringHelper::StringStartsWith($path2,DIRECTORY_SEPARATOR) && StringHelper::StringEndsWith($path1,DIRECTORY_SEPARATOR))
        {
            return rtrim($path1, DIRECTORY_SEPARATOR).$path2;
        }else if(StringHelper::StringStartsWith($path2,DIRECTORY_SEPARATOR) || StringHelper::StringEndsWith($path1,DIRECTORY_SEPARATOR)){
            return $path1.$path2;
        }else{
            return $path1.DIRECTORY_SEPARATOR.$path2;
        }
    }
  
}
?>