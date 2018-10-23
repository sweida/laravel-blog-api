<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function timeline()
    {
        // 每页多少条
        $limit = rq('limit') ?: 10;
        // 页码，从第limit条开始
        $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

        $questions = question_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        $answers = answer_ins()
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($question->toArray(), $answer->toArray());
        $data = $questions->merge($answers);
        $data = $data->sortByDesc(function ($item) {
            return $item->created_at;
        });
        $data = $data -> values()->all();
        return suc(['data' => $data]);
    }

    // public function img_upload(Request $request)
    // {
    //     // $inputData = Request()->all();
    //     $file=$request->file('file');
    //     // return 1;
    //     // $request->file( key: 'upload')->store(path: 'upload');
    // }

    public function img_upload(Request $request){
    	//接收表单提交的文件，file为表单的name
        $file=$request->all();
        // $file=$request->file('file');
        // $path = $file->store(date('Ymd'));
        // dd($file);
        return suc(['file' => $file]);
        //判断是否为合法文件
        // $path=$file->store(date ('ymd'),'upload');
        // return ['file' => asset('uploadImages/'.$path), 'code' => 0];
		// if($file->isValid ()){
        //     //通过laravel自带的store方法进行文件的上传，其中第一个参数表示上传到哪个文件夹下，第二个参数为用哪个磁盘，也就是框架下面的filesystem.php里面的配置
		// 	$path=$file->store (date ('ymd'),'upload');
		// 	return ['file' => asset('uploadImages/'.$path), 'code' => 0];
		// }else{
		// 	return ['message' => '上传失败', 'code' => 403];
		// }
    }
    
    public function img_upload3(Request $request)
    {
        $avatar = $request->file('avatar')->store('/public/' . date('Y-m-d') . '/avatars');
        //上传的头像字段avatar是文件类型
        $avatar = Storage::url($avatar);//就是很简单的一个步骤
        $resource = Resource::create(['status' => 1, 'resource' => $avatar]);
        if ($resource) {
            return $this->responseForJson(ERR_OK, 'upload success');
        }

        return $this->responseForJson(ERR_EDIT, 'upload fails');
    }

    public function upload_img($file)
    {
        $url_path = 'uploads/cover';
        $rule = ['jpg', 'png', 'gif'];
        if ($file->isValid()) {
            $clientName = $file->getClientOriginalName();
            $tmpName = $file->getFileName();
            $realPath = $file->getRealPath();
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return '图片格式为jpg,png,gif';
            }
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            $path = $file->move($url_path, $newName);
            $namePath = $url_path . '/' . $newName;
            return $path;
        }
    }

    public function img_upload5(Request $request){
    	//接收表单提交的文件，file为表单的name
        // $file=$request->all();
        // $file= $request->file('file');
        $file = $request->file( 'file')->store('uploads');
        // $file = $request->file(key: 'file');
        // $path = $file->store(date('Ymd'));
        return suc(['data' => $file]);
    }
}
