<?php

$foldersArray = [
    '../assets',
    '../commands',
    '../components',
    '../config',
    '../controllers',
    '../mail',
    '../migrations',
    '../models',
    '../views',
    '../www'
];

foreach ($foldersArray as $folder) {

    $folderSplitted = explode('/', $folder);

    compress($folder, $folderSplitted[count($folderSplitted) - 1] . '.zip');

}

zipSql();

function zipSql()
{
    $zip = new ZipArchive();
    $zip->open(getcwd() . '/../backup/' . date("Y-m-d") . '-backup/' . date("Y-m-d") . '-' . 'db.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFile('../backup/db.sql', 'db.sql');
    $zip->close();
    unlink('../backup/db.sql');
}

function compress($rootPath, $name)
{
    $zip = new ZipArchive();
    $zip->open(getcwd() . '/../backup/' . date("Y-m-d") . '-backup/' . date("Y-m-d") . '-' . $name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {

        if (!$file->isDir()) {

            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
}
