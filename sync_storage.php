<?php

function copiarArchivos($source, $destination)
{
    $dir = opendir($source);
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    while (false !== ($file = readdir($dir))) {
        if ($file === '.' || $file === '..') continue;

        $srcFile = $source . '/' . $file;
        $dstFile = $destination . '/' . $file;

        if (is_dir($srcFile)) {
            copiarArchivos($srcFile, $dstFile);
        } else {
            if (!file_exists($dstFile) || filemtime($srcFile) > filemtime($dstFile)) {
                copy($srcFile, $dstFile);
            }
        }
    }
    closedir($dir);
}

$from = __DIR__ . '/storage/app/public';
$to   = __DIR__ . '/public/storage';

copiarArchivos($from, $to);
