<?php

namespace App\Module\Bi;

class Helper extends \Illuminate\Database\Eloquent\Model
{
    protected $fullPath = [];
    var $folders = array();

    public static function test()
    {
        $folderFactory = new Folder();
        $folderCollection = $folderFactory->getCollection();
        $folderArray = [];
        foreach ($folderCollection as $folder) {
            $folderArray[] = $folder;
        }
    }
    public function getFolderCollection()
    {
        $this->folders = array();
        $folderFactory = new Folder();
        $folderCollection = $folderFactory->getCollection();
        return $folderCollection;
    }

    public function folderCollectionToArray($folderCollection)
    {
        foreach ($folderCollection as $key => $value) {
            $this->folders[$key] = $value;
        }
    }

    public function viewTree()
    {
        $folderCollection = $this->getFolderCollection();
        $this->folderCollectionToArray($folderCollection);

        $output = "<ul>";
        for ($i = 0; $i < count($this->folders); $i++) {
            /** For level 0 parent folders */
            if ($this->folders[$i]->FolderParentID == "")
            {
                $output .= "<li folder_id=\"" .$this->folders[$i]->ID ."\">".$this->folders[$i]->FolderName."<ul>";
                $output .= $this->getAllChildren($this->folders[$i]->ID);
                $output .= "</ul></li>";
            }
        }
        $output .= "</ul>";
        return $output;
    }

    public function getAllChildren($folderParentId)
    {
        //Get all the nodes for particular ID
        $output = "";
        for ($i = 0; $i < count($this->folders); $i++) {
            /** For others level 1+ folder */
            if ($this->folders[$i]->FolderParentID == $folderParentId)
            {
                $output .= "<li folder_id=\"" .$this->folders[$i]->ID ."\">".$this->folders[$i]->FolderName."<ul>";
                $output .= $this->getAllChildren($this->folders[$i]->ID);
                $output .= "</ul></li>";
            }
        }
        return $output;
    }


    public function getFullPath($folderId)
    {
        $folder = Folder::find($folderId);
        if (($folder->FolderParentId) || ($folder->FolderParentId == "")) {
            $this->fullPath[] = $folderId;
            return $this->fullPath;
        }
        $parentId = $folder->FolderParentId;
        $this->fullPath[] = $parentId;
        return $this->getFullPath($parentId);
    }

}