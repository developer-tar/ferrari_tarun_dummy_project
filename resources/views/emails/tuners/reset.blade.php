<x-mail::message>

{{-- Greeting --}}
# {{ __('emails.reset_password.greeting') }}, {{ $name ?? __('emails.reset_password.user') }}

{{-- Intro Text --}}
{{ __('emails.reset_password.line1') }}

{{-- Action Button --}}
<x-mail::button :url="$url">
    {{ __('emails.reset_password.button') }}
</x-mail::button>

{{-- Expiry --}}
{{ __('emails.reset_password.expiry_line', ['count' => $expiryMinutes]) }}

{{-- Outro Text --}}
{{ __('emails.reset_password.line2') }}

{{-- Signature --}}
{{ __('emails.reset_password.signature') }},<br>
{{ config('app.name') }}

{{-- Custom Subcopy --}}
<x-slot:subcopy>
@lang('emails.reset_password.subcopy', ['actionText' => __('emails.reset_password.button')])
<br>
<span class="break-all">[{{ $url }}]({{ $url }})</span>
</x-slot:subcopy>

</x-mail::message>
