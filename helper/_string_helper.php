<?php
namespace wl_socialstream;

class StringHelper {
    /**
     * String is starting with passed value
     * @param string $haystack String to search for
     * @param string $needle String to compare with  
     * @return bool String starts with searched value
     */    
    public static function StringStartsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
    /**
     * String is ending with passed value
     * @param string $haystack String to search for
     * @param string $needle String to compare with  
     * @return bool String ends with searched value
     */    
    public static function StringEndsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
    
    /**
     * Removes all empty p tags
     * @param string $text Content to search for
     * @return string Content without empty p tags
     */  
    public static function StringCleantext($text)
    {
        $text = str_replace('<p></p>', '', $text);
        if (self::startsWith($text, '</p>')) {
            $text = substr($text, 4);
        }
        if (self::endsWith($text, '<p>')) {
            $text = substr($text, 0, strlen($text)-3);
        }
        
        $text = trim($text);
        
        return $text;
    }
    /**
     * Replaces all German Umlaute with html encoding
     * @param string $text Content to be searched
     * @return string Content with replaced Umlaute
     */  
    public static function ReplaceGermanUmlaute($text)
    {
        $text = iconv('UTF-8', 'ISO-8859-1', $text);
        $umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/");
        $replace = Array("&amp;auml;","&amp;ouml;","&amp;uuml;","&amp;Auml;","&amp;Ouml;","&amp;Uuml;","ss");
        $text = preg_replace($umlaute, $replace, $text);

        return $text;
    }
  
}
?>