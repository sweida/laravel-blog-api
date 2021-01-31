## v2 版本

### 启动项目
```
# 复制配置文件，并修改配置文件，修改数据名称和密码，启动redis，配置redis密码（文章点击量使用redis统计）
cp .env.example .env

# 生成key
php artisan key:generate

# 生成jwt-key
php artisan jwt:secret

# 生成数据库表
php artisan migrate

# 填充数据
php artisan db:seed

# 或者合成一步
# 清空数据库重新生成表并生成数据
php artisan migrate --seed

# 开通又拍云账号
.env 文件配置又拍云信息

# postman请求头设置herders (错误时才会返回json格式)
X-Requested-With => XMLHttpRequest

# 启动8080端口
php artisan serve --port=8080

# 查看接口版本号
http://localhost:8080/api/version
```

## 开发使用的插件

### 安装jwt-auth
```
composer require tymon/jwt-auth

# 修改 config/app.php
'providers' => [
    ...
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]

# 发布配置文件
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

# 生成key
php artisan jwt:secret
```


### 安装 telescope
```
composer require laravel/telescope
php artisan telescope:install
php artisan migrate

# 访问地址
http://localhost:8080/telescope
```

### 安装Laravel Horizon
```
composer require laravel/horizon

# 发布配置文件
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"

php artisan horizon 即可启动所有的队列

# 访问地址
http://localhost:8080/horizon/dashboard
```

### 安装邮件模版
```
composer require qoraiche/laravel-mail-editor

# 发布配置文件
php artisan vendor:publish --provider="qoraiche\mailEclipse\mailEclipseServiceProvider"

php artisan migrate

# 访问地址
http://localhost:8080/maileclipse
```

# 手动生成markdown邮件
```
php artisan make:mail NweUser --markdown=mails.newsuer

# router 文件
new App\Mails\NewUser()
```

### .env邮件配置
```
# 163邮件配置
MAIL_DRIVER=smtp
MAIL_HOST=smtp.163.com
MAIL_PORT=465
MAIL_USERNAME=账号名@163.com
MAIL_PASSWORD=密码
MAIL_FROM_ADDRESS=账号名@163.com
MAIL_FROM_NAME=天行九歌
MAIL_ENCRYPTION=ssl

# smtp测试配置
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxxx
MAIL_FROM_ADDRESS=weidaxyy@163.com
MAIL_FROM_NAME=天行九歌
```

### 图片上传又拍云
```php
composer require "jellybool/flysystem-upyun"

# config/app.php 添加
'providers' => [
    // Other service providers...
    JellyBool\Flysystem\Upyun\UpyunServiceProvider::class,
],

# config/filesystems.php 的 disks 中添加下面的配置：
return [
    //...
      'upyun' => [
            'driver'        => 'upyun', 
            'bucket'        => env('UPYUN_PROJECT_NAME'),   // 服务名字
            'operator'      => env('UPYUN_OPERATOR_NAME'),  // 操作员的名字
            'password'      => env('UPYUN_OPERATOR_PASSWORD'), // 操作员的密码
            'domain'        => env('UPYUN_CNAME'), // 服务分配的域名
            'protocol'     => 'http', // 服务使用的协议，如需使用 http，在此配置 http
        ],
    //...
];

// .env配置文件
UPYUN_PROJECT_NAME = 又拍云项目名称
UPYUN_OPERATOR_NAME = 账号
UPYUN_OPERATOR_PASSWORD = 密码
UPYUN_CNAME = 项目CNAME XXXXX.b0.aicdn.com
```


```
# 添加model
php artisan make:model Models/Article -m

# 添加控制器
php artisan make:controller Api/ArticleController

# 添加request
php artisan make:request ArticleRequest
```

### 安装浏览统计插件
```
composer require awssat/laravel-visits

# 添加配置文件
php artisan vendor:publish --provider="awssat\Visits\VisitsServiceProvider"

# 修改.env文件
CACHE_DRIVER=file 改成 CACHE_DRIVER=array

# 在Postman里要设置 headers->User-Agent
```

#### 关联模型要写在model里，不能写在controller里
```
public function user() {
    return $this->belongsTo('App\Models\User');
}
```

#### 数据填充
```
# 生成User模型的工厂
php artisan make:factory UserFactory --model=Models/User

# 生成User的数据填充
php artisan make:seeder UsersTableSeeder

# 数据填充
php artisan db:seed

# 填充指定模型
php artisan db:seed --class=UsersTableSeeder

# 重新生成数据库表并填充数据
php artisan migrate:refresh --seed

# 进入数据填充测试
php artisan tinker

# 生成20个用户模型
namespace App\Models;
factory(User::class, 20)->create();
```


### redis启动
```
cd /usr/local/etc
redis-server & ./redis.conf

redis-cli
```

跨域medz/cors
pdf功能
支付功能


### 生成随机头像
https://avatars.dicebear.com/v2/identicon/:seed.svg
![https://avatars.dicebear.com/v2/identicon/:seed.svg](https://avatars.dicebear.com/v2/identicon/1.svg)
