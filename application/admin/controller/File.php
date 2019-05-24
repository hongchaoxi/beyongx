<?php
namespace app\admin\controller;

use app\common\model\FileModel;
use think\facade\Env;

/**
 * 文件上传 控制器
 */
class File extends Base
{
    use \app\common\controller\File; //使用trait

//    /**
//     * 通用文件上传
//     */
//    public function upload()
//    {
//        $file = request()->file('file');
//        if (empty($file)) {
//            $this->error('请选择上传文件');
//        }
//        $rule = [
//            //'ext' => 'apk',
//            'size' => 1024*1024*200, //200M
//        ];
//
//        $filePath = Env::get('root_path') . 'public';
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'file';
//        $path = $filePath . $fileUrl;
//        $check = $file->validate($rule);
//
//        if (!$check) {
//            $this->error($file->getError());
//        }
//
//        $info = $file->move($path);
//        $saveName = $info->getSaveName(); //实际包含日期+名字：如20180724/erwrwiej...dfd.ext
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . $saveName;
//
//        $fileSize = $info->getSize();
//
//        //原始上传文件名
//        $fileName = $_FILES['file']['name'];
//
//        //存入数据库
//        $data = [
//            'file_url' => $fileUrl,
//            'file_path' => $filePath,
//            'file_name' => $fileName,
//            'file_size' => $fileSize,
//            'create_time' => date_time()
//        ];
//        $FileModel = new FileModel();
//        $fileId = $FileModel->insertGetId($data);
//
//        $data['file_id'] = $fileId;
//
//        $this->success('文件上传成功', false, $data);
//    }
//
//    //上传软件，桌面端软件，如果.exe.zip
//    // 文件过大时，需要在php.ini配置post_max_size, upload_max_filesize
//    public function uploadSoftware()
//    {
//        ini_set('memory_limit', '256M');
//        //ini_set('post_max_size', '128M');
//        //ini_set('upload_max_filesize', '128M');
//        $file = request()->file('file');
//        if (empty($file)) {
//            # 可能php.ini配置错误，或者查看一下file传参
//            $this->error('请选择上传文件');
//        }
//        $rule = [
//            'ext' => 'zip,rar,exe',
//            'size' => 1024*1024*200, //200M
//        ];
//
//        $filePath = Env::get('root_path') . 'public';
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'software';
//        $path = $filePath . $fileUrl;
//        $check = $file->validate($rule);
//
//        if (!$check) {
//            $this->error($file->getError());
//        }
//
//        $version = input('param.version');
//        $fileName = $file->getInfo('name');
//
//        //不传值时，系统生成文件名，格式为YYYYmmdd/xxx.....xxxx.ext
//        $saveName = $version . DIRECTORY_SEPARATOR . $fileName; //文件命名
//        $info = $file->move($path, $saveName);
//        //$saveName = $info->getSaveName();
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'software' . DIRECTORY_SEPARATOR . $saveName;
//
//        $fileSize = $info->getSize();
//
//        //原始上传文件名
//        $fileName = $_FILES['file']['name'];
//
//        //存入数据库
//        $data = [
//            'file_url' => $fileUrl,
//            'file_path' => $filePath,
//            'file_name' => $fileName,
//            'file_size' => $fileSize,
//            'create_time' => date_time()
//        ];
//        $FileModel = new FileModel();
//        $fileId = $FileModel->insertGetId($data);
//
//        $data['file_id'] = $fileId;
//        $data['ext'] = $info->getExtension(); //文件后缀
//
//        $this->success('文件上传成功', false, $data);
//    }
//
//    //上传应用,移动类应用，如apk, ipa
//    public function uploadApp()
//    {
//        $file = request()->file('file');
//        if (empty($file)) {
//            $this->error('请选择上传文件');
//        }
//        $rule = [
//            'ext' => 'apk,ipa',
//            'size' => 1024*1024*200, //200M
//        ];
//
//        $filePath = Env::get('root_path') . 'public';
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'app';
//        $path = $filePath . $fileUrl;
//        $check = $file->validate($rule);
//
//        if (!$check) {
//            $this->error($file->getError());
//        }
//
//        $appId = input('param.app_id');
//        $version = input('param.version');
//        $fileName = $file->getInfo('name');
//
//        //不传值时，系统生成文件名，格式为YYYYmmdd/xxx.....xxxx.ext
//        $saveName = $appId . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $fileName; //文件命名
//        $info = $file->move($path, $saveName);
//        //$saveName = $info->getSaveName();
//        $fileUrl = DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $saveName;
//
//        $fileSize = $info->getSize();
//
//        //原始上传文件名
//        $fileName = $_FILES['file']['name'];
//
//        //存入数据库
//        $data = [
//            'file_url' => $fileUrl,
//            'file_path' => $filePath,
//            'file_name' => $fileName,
//            'file_size' => $fileSize,
//            'create_time' => date_time()
//        ];
//        $FileModel = new FileModel();
//        $fileId = $FileModel->insertGetId($data);
//
//        $data['file_id'] = $fileId;
//        $data['ext'] = $info->getExtension(); //文件后缀
//
//        $this->success('文件上传成功', false, $data);
//    }
}
