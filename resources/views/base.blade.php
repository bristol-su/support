@if(isset($globalScripts))
    @foreach($globalScripts as $script)
        @push('scripts')
            <script src="{{$script}}"></script>
        @endpush
    @endforeach
@endif

@includeFirst(['partials.injections', 'bristolsu::partials.injections'])

@includeFirst(['partials.doctype', 'bristolsu::partials.doctype'])
@componentFirst(['partials.components.html', 'bristolsu::partials.components.html'])

    @componentFirst(['partials.components.head', 'bristolsu::partials.components.head'])
        @stack('meta-tags')
        @includeFirst(['partials.javascript', 'bristolsu::partials.javascript'])
        <title>@yield('title', 'Portal')</title>
        @stack('fonts')
        @stack('styles')
        @includeFirst(['partials.noscript', 'bristolsu::partials.noscript'])
    @endcomponentfirst

    @componentFirst(['partials.components.body', 'bristolsu::partials.components.body'])
        @componentFirst(['partials.components.wrapper', 'bristolsu::partials.components.wrapper'])
            @includeFirst(['partials.header', 'bristolsu::partials.header'])
            @componentFirst(['partials.components.contentwrap', 'bristolsu::partials.components.contentwrap'])
                @yield('content')
            @endcomponentfirst
            @includeFirst(['partials.footer', 'bristolsu::partials.footer'])
        @endcomponentfirst
        @stack('scripts')
    @endcomponentfirst

@endcomponentfirst
