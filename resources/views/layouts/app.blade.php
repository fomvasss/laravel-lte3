@include('lte3::layouts.inc.begin')
<div class="wrapper">
    @includeWhen(config('lte3.view.preloader'), 'lte3::layouts.inc.preloader')
    @include('lte3::layouts.inc.navbar')
    @include('lte3::layouts.inc.sidebar')
    <div class="content-wrapper">
        @yield('content')
    </div>
    @include('lte3::layouts.inc.footer')
</div>
@include('lte3::layouts.inc.end')


