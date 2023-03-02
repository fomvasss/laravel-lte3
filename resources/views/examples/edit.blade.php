@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Edit',
        'url_back' => '#',
    ])

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card" hidden>
            <div class="card-header">
                <h3 class="card-title">Title</h3>
            </div>
            <div class="card-body">

            </div>

            <div class="card-footer">

            </div>
        </div>

        <div class="card card-primary card-outline card-outline-tabs">
            
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-data-tab" data-toggle="pill" href="#tabs-data" role="tab" aria-controls="tabs-data" aria-selected="true">Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-seo-tab" data-toggle="pill" href="#tabs-seo" role="tab" aria-controls="tabs-seo" aria-selected="false">Seo</a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="tabs-data" role="tabpanel" aria-labelledby="tabs-data-tab">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Lte3::text('name', null, ['label' => 'Title']) !!}
                                {!! Lte3::textarea('body') !!}
                                {!! Lte3::datetimepicker('datetime', now(), [
                                    'label' => 'Published',
                                    'format' => 'Y-m-d H:i:s',
                                ]) !!}
                                {!! Lte3::select2('status', 'published', ['published' => 'Published', 'draff' => 'Draff'], [
                                   'label' => 'Status',
                               ]) !!}
                                {!! Lte3::slug('slug', null, ['label' => 'Slug']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Lte3::mediaFile('image', null, [
                                  'label' => 'Main Image',
                                  'is_image' => true,
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-seo" role="tabpanel" aria-labelledby="tabs-seo-tab">
                        {!! Lte3::slug('slug', null, ['label' => '']) !!}
                        {!! Lte3::text('title') !!}
                        {!! Lte3::textarea('description') !!}
                        {!! Lte3::text('keywords') !!}
                        {!! Lte3::text('robots', null, ['placeholder' => 'index,follow']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                {!! Lte3::btnSubmit('Submit') !!}
            </div>
        </div>

    </section>
@endsection
