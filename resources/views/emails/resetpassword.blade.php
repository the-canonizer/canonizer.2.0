@component('mail::message')
 Hi {{ $user->first_name}},<br/>
 <p>You recently requested to reset you password, In order to complete your request click the button below</p>
     

@component('mail::button', ['url' => url('/') . '/' . $link])
Click Here To Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
