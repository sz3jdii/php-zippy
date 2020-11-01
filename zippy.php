<?php
/* Zippy
 * PHP Recursive Backup-Script to ZIP-File
 * (c) 2020: Fixes and improvements: sz3jdii. (https://iodi.pl) 
 * (c) 2012: Base: Marvin Menzerath. (http://menzerath.eu)
 * 
*/
echo "Start";
echo date('d/m/Y h:i:s a', time());
ini_set('max_execution_time', 600);
ini_set('memory_limit','2048M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$path =  realpath(dirname(__FILE__));
// Start the backup!
echo $path.PHP_EOL;
// Carefully with basedir, watchout destinations
zipData($path, $path.'/backup.zip');
echo date('d/m/Y h:i:s a', time()).PHP_EOL;
echo 'Finished.'.PHP_EOL;

// Here the magic happens :)
function zipData($source, $zipTo) {
    if (extension_loaded('zip') === true) {
        if (file_exists($source) === true) {
            $zip = new ZipArchive();

            if ($zip->open($zipTo, ZIPARCHIVE::CREATE) === true) {
                $source = realpath($source);

                if (is_dir($source) === true) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

                    foreach ($files as $file) {
                        $file = realpath($file);

                        if (is_dir($file) === true) {
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        } else if (is_file($file) === true) {
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                    }
                } else if (is_file($source) === true) {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }
            return $zip->close();
        }else{
            echo "Source does not exist ".$source.PHP_EOL;
        }
    }else{
        echo "Extension zip disabled".PHP_EOL;
    }
    return false;
}
?>
