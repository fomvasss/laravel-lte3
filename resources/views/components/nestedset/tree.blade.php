@php
    $attrs['routes']['params'] = Arr::wrap($attrs['routes']['params'] ?? []);
    $attrs['has_nested'] = $attrs['has_nested'] ?? $attrs['is_nested'] ?? true;
@endphp

<div class="card card-default f-sortable-nested-wrap"
     @if(\Arr::get($attrs, 'routes.order')) data-url="{{ route(\Arr::get($attrs, 'routes.order'), $attrs['routes']['params']) }}" @endif
>

    @isset($attrs['label'])
        <div class="card-header">
            @isset($attrs['label'])
                <h3 class="card-title">{!! $attrs['label'] !!}: {{ $terms->count() }}</h3>
            @endisset

            <div class="card-tools">
                @if(($attrs['routes']['create'] ?? '') && ($attrs['root_btn_create'] ?? ''))
                    <a href="{{ route($attrs['routes']['create'], $attrs['routes']['params']) }}"
                       class="btn btn-success btn-xs" data-toggle="tooltip"><i class="fas fa-plus"></i> {{ $attrs['root_btn_create'] ?? '' }}</a>
                @endif
            </div>
        </div>
    @endisset

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="todo-list @if(\Arr::get($attrs, 'routes.order')) js-sortable-nested @endif" style="position: relative">
                    @include($attrs['item'], [
                        'attrs' => $attrs,
                        'items' => $terms->toTree(),
                        'attributes' => $attrs ?? [],
                    ])
                </ul>
            </div>
        </div>
    </div>
</div>
