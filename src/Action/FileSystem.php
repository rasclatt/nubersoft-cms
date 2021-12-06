<?php
namespace NubersoftCms\Action;

use \NubersoftCms\Model\FileSystem as FileHelper;
use \Nubersoft\{
    nFileHandler,
    nObserver,
    nApp
};

class FileSystem implements nObserver
{
    private $POST, $nApp, $FileHelper;
    /**
     * @description 
     */
    public function __construct(
        nApp $nApp,
        FileHelper $FileHelper
    )
    {
        $this->nApp = $nApp;
        $this->FileHelper = $FileHelper;
        $this->POST = $this->nApp->getPost();
    }
    /**
     * @description 
     */
    public function listen()
    {
        switch ($this->getSubAction()) {
            case ('update'):
                if ($this->getDelete() == 'on') {
                    @unlink($this->getPath());
                    if (!is_file($this->getPath())) {
                         $this->nApp->ajaxResponse(["msg" => "File deleted"]);
                    }
                } else {
                    if (!empty($this->getPath()) && !empty($this->getData())) {
                        # Save contents of file to disk
                        file_put_contents( $this->nApp->dec($this->getPath()),  $this->nApp->dec($this->getData()));
                        # Set current name
                        $oldname = pathinfo($this->getPath(), PATHINFO_BASENAME);
                        # Filter name
                        $fname = preg_replace('/[^\dA-Z\.-_]/i', '', $this->getFname());
                        # rename the file and save to path if new name
                        if (!empty($fname)) {
                            if ($fname != $oldname) {
                                # Set the path
                                $filepath = pathinfo($this->getPath(), PATHINFO_DIRNAME);
                                # Rename file
                                rename($filepath . DS . $oldname, $filepath . DS . $fname);
                                # If the file just duplicates but doesn't delete
                                if (is_file($filepath . DS . $oldname) && is_file($filepath . DS . $fname))
                                    unlink($filepath . DS . $oldname);
                                # Save back to POST array
                                $newpath = $this->setPath(pathinfo($this->getPath(), PATHINFO_DIRNAME) . DS . $fname);
                            }
                        }
                    }
                }
            default:
                $item = $this->getPath();
                $isfile = is_file($item);
                $contents = $this->FileHelper->fetchContents(($isfile) ? $item : realpath($item));

                if (empty($contents)) {
                    $contents = $this->FileHelper->setDataAttributes($item);
                } else {
                    if ($isfile) {
                        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                        $cont = file_get_contents($item);
                        $isImg = in_array($ext, ['jpg', 'jpeg', 'gif', 'png', 'tif', 'tiff', 'svg', 'psd']);
                        $rows = (!$isImg) ? count(file($item)) : false;
                        $fileData = ($isImg) ? '<img src="' . (new \Nubersoft\nImage())->toBase64($cont, $ext) . '" style="width: auto; height: auto;" />' : '<textarea class="textarea" name="file_edit" style="background-color: #111; font-family: Courier !important; padding: 1em; color: #FFF; width: calc(100% - 2em); height: 100%; font-size: 1em !important;" rows="' . $rows . '" data-path="' . $item . '">' .  $this->nApp->enc($cont) . '</textarea>';
                        $contents = array_merge($contents, [
                            'contents' => $fileData
                        ]);
                    }
                }

                 $this->nApp->ajaxResponse($contents);
        }
    }
    /**
     * @description 
     */
    public function __call($method, $args = false)
    {
        $action = (stripos($method, 'set') !== false) ? 'set' : 'get';
        $method = preg_replace('/^' . $action . '/', '', strtolower($method));
        if ($action == 'set')
            $this->POST[$method] = ($args[0]) ?? false;
        return ($this->POST[$method]) ?? false;
    }
}
