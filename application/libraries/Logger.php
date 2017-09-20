<?php

Class Logger
{
    public static function Log($logText,$log_file = null){
        $path = self::get_path($log_file);
        $data = "[".date('Y-m-d H:i:s')."] ".$logText.PHP_EOL;
        $fp = fopen($path, "a");
        fwrite($fp,$data);
        fclose($fp);
    }


    public static function LogDebug($logText,$log_file = null){
        $path = self::get_path($log_file);
        $data = "[".date('Y-m-d H:i:s')."][DEBUG] ".$logText.PHP_EOL;
        $fp = fopen($path, "a");
        fwrite($fp,$data);
        fclose($fp);
    }

    public static function LogInfo($logText,$log_file = null){
        $path = self::get_path($log_file);
        $data = "[".date('Y-m-d H:i:s')."][INFO] ".$logText.PHP_EOL;
        $fp = fopen($path, "a");
        fwrite($fp,$data);
        fclose($fp);
    }


    public static function LogWarn($logText,$log_file = null){
        $path = self::get_path($log_file);
        $data = "[".date('Y-m-d H:i:s')."][WARN] ".$logText.PHP_EOL;
        include_once(APPPATH . 'libraries/VarDumper.php');
        $fp = fopen($path, "a");
        fwrite($fp,$data);
        fclose($fp);
    }

    public static function LogError($logText,$log_file = null){
        $path = self::get_path($log_file);
        $data = "[".date('Y-m-d H:i:s')."][ERROR] ".$logText.PHP_EOL;
        $fp = fopen($path, "a");
        fwrite($fp,$data);
        fclose($fp);
    }

    public static function showLog($log_file = null){
        return file(self::get_path($log_file));
    }

    public static function clearLog($log_file = null){
        file_put_contents(self::get_path($log_file), "");
    }


    protected static function get_path($log_file = null)
    {
        if (!self::contains($log_file, '.txt', false)) {
            $log_file .= '.txt';
        }
        return file_exists(LOG_PATH_DIRECTORY . $log_file) && $log_file ? (LOG_PATH_DIRECTORY . $log_file) : (LOG_PATH);

    }

    protected static function contains($haystack, $needle, $caseSensitive = true, $encoding = 'UTF-8')
    {
        if ($caseSensitive === false) {
            return mb_stristr($haystack, $needle, null, $encoding) !== false;
        }
        return mb_strstr($haystack, $needle, null, $encoding) !== false;
    }
}