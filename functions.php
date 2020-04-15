<?php

function export_data($data) {
    try {
        $file_name = 'data.json';
        $folder_path = __DIR__ . '/' . 'export';
        $file_path = $folder_path . '/' . $file_name;
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0755, true);
        }
        $file = fopen($file_path, "w");
        fwrite($file, json_encode($data));
        fclose($file);

        copy('export.php', 'export/index.php');

        $zipname = 'export.zip';
        if (file_exists($zipname)) @unlink($zipname);

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folder_path),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folder_path) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();

        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename='export.zip'");
        header('Content-Length: ' . filesize($zipname));
        header("Location: export.zip");

    } catch (Exception $ex) {
    }
}