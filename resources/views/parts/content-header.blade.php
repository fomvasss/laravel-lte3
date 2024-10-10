<!-- Content Header (Page header) -->
<div class="content-header pb-0">
    <div class="container-fluid pl-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    @isset($url_back)
                        <a href="{{ $url_back }}" class="btn btn-flat btn-secondary"><i
                                    class="fa fa-chevron-left"></i> </a>
                    @endif
                    @isset($page_title) {!! $page_title !!} @endisset
                    @isset($small_page_title)<small>{{ $small_page_title }}</small>@endisset
                    @if(!empty($url_create))
                        <a href="{{ $url_create }}" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Create</a>
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    @yield('btn-content-header')
                </div>
            </div>
        </div>
    </div>
</div>

@include('lte3::parts.callouts')
@include('lte3::parts.alerts')
