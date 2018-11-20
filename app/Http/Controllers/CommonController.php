<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class CommonController extends Controller
{
    // public function timeline()
    // {
    //     // 每页多少条
    //     $limit = rq('limit') ?: 10;
    //     // 页码，从第limit条开始
    //     $skip = (rq('page') ? rq('page')-1 : 0) * $limit;

    //     $questions = question_ins()
    //         ->limit($limit)
    //         ->skip($skip)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $answers = answer_ins()
    //         ->limit($limit)
    //         ->skip($skip)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     // dd($question->toArray(), $answer->toArray());
    //     $data = $questions->merge($answers);
    //     $data = $data->sortByDesc(function ($item) {
    //         return $item->created_at;
    //     });
    //     $data = $data -> values()->all();
    //     return suc(['data' => $data]);
    // }


    // public function img_upload(Request $request){
    // 	//接收表单提交的文件，file为表单的name
    //     $file=$request->all();
    //     // $file=$request->file('file');
    //     // $path = $file->store(date('Ymd'));
    //     // dd($file);
    //     return suc(['file' => $file]);
    //     //判断是否为合法文件
    //     // $path=$file->store(date ('ymd'),'upload');
    //     // return ['file' => asset('uploadImages/'.$path), 'code' => 0];
	// 	// if($file->isValid ()){
    //     //     //通过laravel自带的store方法进行文件的上传，其中第一个参数表示上传到哪个文件夹下，第二个参数为用哪个磁盘，也就是框架下面的filesystem.php里面的配置
	// 	// 	$path=$file->store (date ('ymd'),'upload');
	// 	// 	return ['file' => asset('uploadImages/'.$path), 'code' => 0];
	// 	// }else{
	// 	// 	return ['message' => '上传失败', 'code' => 403];
	// 	// }
    // }
    
    // public function img_upload3(Request $request)
    // {
    //     $avatar = $request->file('avatar')->store('/public/' . date('Y-m-d') . '/avatars');
    //     //上传的头像字段avatar是文件类型
    //     $avatar = Storage::url($avatar);//就是很简单的一个步骤
    //     $resource = Resource::create(['status' => 1, 'resource' => $avatar]);
    //     if ($resource) {
    //         return $this->responseForJson(ERR_OK, 'upload success');
    //     }

    //     return $this->responseForJson(ERR_EDIT, 'upload fails');
    // }

    // public function upload_img($file)
    // {
    //     $url_path = 'uploads/cover';
    //     $rule = ['jpg', 'png', 'gif'];
    //     if ($file->isValid()) {
    //         $clientName = $file->getClientOriginalName();
    //         $tmpName = $file->getFileName();
    //         $realPath = $file->getRealPath();
    //         $entension = $file->getClientOriginalExtension();
    //         if (!in_array($entension, $rule)) {
    //             return '图片格式为jpg,png,gif';
    //         }
    //         $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
    //         $path = $file->move($url_path, $newName);
    //         $namePath = $url_path . '/' . $newName;
    //         return $path;
    //     }
    // }

    // 上传文件
    public function img_upload(Request $request){
        $file = $request->file('file')->store('/public/ad/' . date('Y-m-d'));
        return suc(['data' => $file]);
    }

    // 上传文章封面图
    public function blog_banner(Request $request){
        $file = $request->file('file')->store('/public/blog/banner');
        return suc(['data' => $file]);
    }

    // 上传文章详情图
    public function fileUpload(Request $request)
    {
        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            if (!$picture->isValid()) {
                abort(400, '无效的上传文件');
            }
            // 文件扩展名
            $extension = $picture->getClientOriginalExtension();
            // 文件名
            $fileName = $picture->getClientOriginalName();
            // 生成新的统一格式的文件名
            $newFileName = md5($fileName . time() . mt_rand(1, 10000)) . '.' . $extension;
            // 图片保存路径
            $savePath = 'blog/article/' . $newFileName;
            // Web 访问路径
            $webPath = 'storage/' . $savePath;
            // 将文件保存到本地 storage/app/public/images 目录下，先判断同名文件是否已经存在，如果存在直接返回
            if (Storage::disk('public')->has($savePath)) {
                return response()->json(['path' => $webPath]);
            }
            // 否则执行保存操作，保存成功将访问路径返回给调用方
            if ($picture->storePubliclyAs('blog/article/', $newFileName, ['disk' => 'public'])) {
                return response()->json(['path' => $webPath]);
            }
            abort(500, '文件上传失败');
        } else {
            abort(400, '请选择要上传的文件');
        }
    }

    // 删除上传的文件
    public function delete_upload() {
        if (!rq('url'))
            return err('url is requesed');
        $file = Storage::delete(rq('url'));
        return $file ?
            suc(['msg' => '删除成功']):
            err('删除失败');
    }

    // 发送邮件
    public function mail() {
        if (!rq('email'))
            return err('email is requesed');
        
        $user = user_ins()->where('email', rq('email'))->first();

        if (!$user)
            return err('该邮箱没有注册账号');
        else
            return suc(['data' => $user]);
        // $send = Mail::raw('找回密码', function($message) {
        //     $message->from('weidaxyy@163.com', '放羊的猩猩的博客');
        //     $message->subject('验证码是' + $user->phone_captcha + '，三分钟内有效');
        //     $message->to(rq('email'));
        // });

        // if ($send)
        //     return suc(['msg' => '邮件已经发送，请注意查看']);
        // else 
        //     return err('邮件发送失败，请稍后在操作');
    }

}
