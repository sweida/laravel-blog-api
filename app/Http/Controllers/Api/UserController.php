<?php

namespace App\Http\Controllers\Api;


use App\Models\User;
use App\Models\UserAuth;
use Hash;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserAuthRequest;
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
        $user = User::create($request->all());
        $emailIdentifier = [
            'user_id' => $user->id,
            'identity_type' => 'email',
            'identifier' => $request->email,
            'password' => $request->password
        ];
        $nameIdentifier = [
            'user_id' => $user->id,
            'identity_type' => 'name',
            'identifier' => $request->name,
            'password' => $request->password
        ];
        UserAuth::create($emailIdentifier);
        UserAuth::create($nameIdentifier);

        return $this->message('用户注册成功');
    }


    // 将以前旧账号数据添加到有账号表
    public function createPassword() {
        $users = User::get();

        foreach($users as $item) {
            $emailIdentifier = [
                'user_id' => $item->id,
                'identity_type' => 'email',
                'identifier' => $item->email,
                'password' => $item->password
            ];
            $nameIdentifier = [
                'user_id' => $item->id,
                'identity_type' => 'name',
                'identifier' => $item->name,
                'password' => $item->password
            ];
            UserAuth::create($emailIdentifier);
            UserAuth::create($nameIdentifier);
        }
        // return ['s' => $users];
        return $this->message('批量生成成功');
    }

    //用户登录
    public function login(UserAuthRequest $request){
        $token=Auth::guard('api')->attempt(
            [
                'identity_type' => $request->type, 
                'identifier'=>$request->name,
                'password'=>$request->password
            ]
        );
        if($token) {
            $userAuth = Auth::guard('api')->user();
            $user = User::find($userAuth->user_id);
            $user->update([$user->updated_at = time()]);

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
        $userAuth = Auth::guard('api')->user();
        $user = User::find($userAuth->user_id);

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

        // 修改所有关联账号密码
        $userAuths = UserAuth::where('user_id', $user->user_id)->get();
        foreach($userAuths as $item){
            $item->update(['password' => $request->new_password]);
        }

        return $this->message('密码修改成功');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubLogin()
    {
        $githubUser = Socialite::driver('github')->user();

        // 邮件存在则不创建，共享一个账号数据
        $user = [
            'email' => $githubUser->email,
            'name' => $githubUser->nickname,
            'avatar_url' => $githubUser->avatar,
            'password' => bcrypt(str_random(16))
        ];
        $newUser = User::firstOrCreate(['email' => $user['email']], $user);

        // 创建一条github账号
        $githubIdentifier = [
            'user_id' => $newUser->id,
            'identity_type' => 'github',
            'identifier' => $githubUser->email,
            'password' => bcrypt(str_random(16))
        ];
        UserAuth::updateOrCreate([
            'identifier' => $githubUser->email, 
            'identity_type' => 'github'
        ], $githubIdentifier);

        // $token = Auth::guard('api')->tokenById($newUser->id);
        $token=Auth::guard('api')->attempt(
            [
                'identity_type' => 'github', 
                'identifier' => $githubUser->email, 
                'password' => $githubIdentifier['password']
            ]
        );

        return view('githubLogin')->with(['token' => 'Bearer ' . $token, 'url' => env('LOGIN_REDIRECT').'#/login']);
    }




}
