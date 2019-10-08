<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use Hash;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Overtrue\Socialite\SocialiteManager;
// use App\Http\Resources\UserResource;
use Socialite;

class UserController extends Controller
{
    //用户注册
    public function signup(UserRequest $request){
        User::create($request->all());
        return $this->message('用户注册成功');
    }

    //用户登录
    public function login(UserRequest $request){
        $token=Auth::guard('api')->attempt(
            ['name'=>$request->name,'password'=>$request->password]
        );
        if($token) {
            $user = Auth::guard('api')->user();
            $user->updated_at = time();
            $user->update();
            return $this->success(['token' => 'Bearer ' . $token]);
        }
        return $this->failed('密码有误！', 200);
    }
    
    //用户退出
    public function logout(){
        Auth::guard('api')->logout();
        return $this->message('退出登录成功!');
    }

    //返回当前登录用户信息
    public function info(){
        $user = Auth::guard('api')->user();
        if ($user->is_admin==1)
            $user->admin = true;
        return $this->success($user);
    }

    //返回指定用户信息
    public function show(UserRequest $request){
        $user = User::find($request->id);
        return $this->success($user);
    }

    //返回用户列表 10个用户为一页
    public function list(){
        $users = User::paginate(10);
        foreach($users as $item) {
            if ($item->is_admin) {
                $item->admin = true;
            }
        }
        // return UserResource::collection($users);
        return $this->success($users);
    }

    // 修改密码
    public function resetpassword(UserRequest $request){
        $user = Auth::guard('api')->user();
        $oldpassword = $request->get('old_password');

        if (!Hash::check($oldpassword, $user->password))
            return $this->failed('旧密码错误', 200);

        $user->update(['password' => $request->new_password]);
        return $this->message('密码修改成功');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubLogin()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = [
            'email' => $githubUser->email,
            'name' => $githubUser->nickname,
            'avatar_url' => $githubUser->avatar,
            'password' => bcrypt(str_random(16))
        ];
        User::updateOrCreate(['email' => $user['email']], $user);

        $token=Auth::guard('api')->attempt(
            ['name'=>$user['name'],'password'=>$user['password']]
        );
        $url = 'http://localhost:9001/login';

        return view('githubLogin')->with(['token' => $token, 'url' => $url]);
    }





}
