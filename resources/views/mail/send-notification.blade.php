@component('mail::layout')
{{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

{{-- Body --}}
    {{ $data['subject'] }}

    {{ $data['message'] }}
    
    Regards,
    ASME

@endcomponent

