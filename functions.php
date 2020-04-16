<?php

require_once 'vendor/autoload.php';
use RedisClient\RedisClient;

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
        recurse_copy(__DIR__ . '/' . 'vendor', __DIR__ . '/export/' . 'vendor');

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

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

class MyRedis
{
    private $client = null;

    public function __construct()
    {
        if (!$this->client) {
            $this->client = new RedisClient([
                'server' => '127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
//                'server' => 'unix:///home/streamap/.applicationmanager/redis.sock', // or 'unix:///tmp/redis.sock'
                'timeout' => 2
            ]);
        }
    }

    function __destruct()
    {
        if ($this->client) {
            $this->client->quit();
            $this->client = null;
        }
    }

    public function quit()
    {
        if ($this->client) {
            $this->client->quit();
            $this->client = null;
        }
    }

    //Test function
    public function ping()
    {
        echo $this->client->ping();
    }

    public function set($id, $value)
    {
        return $this->client->set($id, $value, 3600*6);
    }

    public function get($id)
    {
        $value = $this->client->get($id);
        return ($value ? $value : null);
    }

    public function delete($id) {
        return $this->client->del($id);
    }
}