<?php

namespace App\Http\Controllers\Module\Bi\Folder;

use App\Eoffice\Helper;
use App\Http\Controllers\Controller;
use App\Module\Bi\Folder;
use Illuminate\Http\Request;

class  RenameController extends Controller
{

    public function index()
    {
        if (Helper::isAUserInSession()) {
            $viewHtml = view('system/module/bi/folderRename')->render();
            return response()->json(array('success' => true, 'viewHtml' => $viewHtml));
        } else {
            return view('user/login');
        }
    }

    public function execute(Request $request)
    {
        try {
            $dataPost = $request->input();
            $folderId = $dataPost['FolderID'];
            $newFolderName = $dataPost['NewFolderName'];
            $folderFactory = Folder::find($folderId);
            $folderFactory->FolderName = $newFolderName;
            $folderFactory->save();
            return response()->json(array('success' => true));
        } catch (\Exception $exception) {
            return response()->json(array('success' => false, 'errorMessage' => $exception->getMessage()));
        }
    }
}