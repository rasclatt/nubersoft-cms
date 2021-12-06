<?php
namespace NubersoftCms\Model;

use \Nubersoft\Conversion\Data;
use \RecursiveIteratorIterator as RII;
use \RecursiveDirectoryIterator as RDI;
class FileSystem
{
    /**
     * @description 
     */
    public function fetchContents($path)
    {
        if (is_file($path))
            return $this->setDataAttributes($path);

        $new = [];
        foreach (scandir($path) as $item) {
            if (in_array($item, ['.', '..']))
                continue;

            $new[] = $this->setDataAttributes($path . DS . $item);
        }

        usort($new, function ($a, $b) {
            return strcmp(strtolower($a['full_name']), strtolower($b['full_name']));
        });

        return $new;
    }
    /**
     * @description 
     */
    public function fetchRecursiveContents($path)
    {
        $recurse = new RII(new RDI($path, RDI::KEY_AS_PATHNAME | RDI::SKIP_DOTS));
        $new = [];
        foreach ($recurse as $filepath => $it) {
            $new[] = $this->setDataAttributes($filepath);
        }

        usort($new, function ($a, $b) {
            return strcmp(strtolower($a['full_name']), strtolower($b['full_name']));
        });

        return $new;
    }
    /**
     * @description 
     */
    public function setDataAttributes($filepath)
    {
        $isFile = is_file($filepath);

        return [
            'path' => (!$isFile) ? realpath($filepath) : $filepath,
            'type' => ($isFile) ? 'file' : 'folder',
            'file_info' => pathinfo($filepath),
            'full_name' => pathinfo($filepath, PATHINFO_BASENAME),
            'ext' => ($isFile) ? pathinfo($filepath, PATHINFO_EXTENSION) : false,
            'modified' => date('M j, Y g:i a', filemtime($filepath)),
            'created' => date('M j, Y g:i a', filectime($filepath)),
            'size' => ($isFile) ? Data::getByteSize(filesize(realpath($filepath)), ['from' => 'B', 'to' => 'KB', 'ext' => true, 'round' => 2]) : (count(scandir($filepath)) - 2)
        ];
    }
}
