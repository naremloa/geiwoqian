<?php

namespace App\Http\Controllers;

use App\Libraries\Response;
use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;

class TestController extends Controller
{
    //
    public function upload(Request $request){
        $check = [];
        if(!$check){
            $check = TestController::getBinary($request, 'file');
        }
        list($binary, $extension, $filesize) = $check;
        $filename = md5(uniqid(time())) . '.' . $extension;
        $path_name = ltrim($filename, '\/');
        $res = QiniuStorage::disk('qiniu')->put($path_name,$binary);
        if($res){
            $domain = trim(config('filesystems.disks.qiniu.domains.default'));
            $result = 'http://' . $domain . '/' . $path_name;
        }else{
            return 'no';
        }
        return Response::formatJson(200, '上传成功', $result);
    }

    public static function getBinary($request, $name = 'file'){
        if (isset($_FILES['file'])) {
            $file = $request->file('file');
        } else if (isset($_FILES[$name])) {
            $file = $request->file($name);
        }
        if (!$file) {
            return [];
        }
        $binary = file_get_contents($file->getPathname());
        $extension = strtolower($file->guessClientExtension());
        $filesize = $file->getClientSize();

        return [$binary, $extension, $filesize];
    }
}
