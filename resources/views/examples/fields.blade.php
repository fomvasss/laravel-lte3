@extends('lte3::layouts.app')

@section('content')
    @include('lte3::parts.content-header', [
        'page_title' => 'Fields from a description',
        'small_page_title' => 'Lte3::fields',
    ])

    <section class="content">
        <div class="card">
            <div class="card-body">
                {!! Lte3::formOpen(['action' => route('lte3.fields'), 'method' => 'POST']) !!}
                    {!! Lte3::fields($fields, $values, ['prefix' => 'settings']) !!}
                    {!! Lte3::btnSubmit('Submit') !!}
                {!! Lte3::formClose() !!}
            </div>
        </div>
    </section>
@endsection
