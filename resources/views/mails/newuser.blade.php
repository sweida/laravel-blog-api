@component('mail::message')

### 亲爱的用户：{{ $data->name }}  

重置密码的验证码是 {{ $data->captcha }}，请在5分钟内验证。

@component('mail::button', ['url' => ''])
Button Text
@endcomponent


@component('mail::panel')
如果不是本人操作发送的邮件，请提防账号被盗！
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
