@component('mail::message')
# Sofra Reset Password

Your reset password code is : {{$code}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
