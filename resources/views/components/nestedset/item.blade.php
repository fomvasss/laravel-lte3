@foreach($items as $item)
    <li data-id="{{ $item->id }}">
        <span class="handle">
            @isset($attrs['handle_icon'])
                {!! $attrs['handle_icon'] !!}
            @else
                <i class="fa fa-arrows-alt"></i>
            @endisset
        </span>

        <span>
            @isset($attrs['routes']['edit'])
                <a href="{{ route($attrs['routes']['edit'], array_merge([$item], $attrs['routes']['params'])) }}"
                   class="text hover-edit">
                    {{ $item->name }}
                </a>
            @else
                <span class="text hover-edit">{{ $item->name }}</span>
            @endisset
        </span>

        <div class="tools" style="display: block;">
            @isset($attrs['routes']['show'])
                <a href="{{ route($attrs['routes']['show'], array_merge([$item], $attrs['routes']['params'])) }}"
                   target="_blank" class="text-info"><i class="fas fa-eye"></i></a>
            @endif
            @isset($attrs['routes']['create'])
                <a href="{{ route($attrs['routes']['create'], array_merge($attrs['routes']['params'], ['parent_id' => $item->id])) }}"
                   class="text-success"><i class="fas fa-plus-circle"></i></a>
            @endif
            @isset($attrs['routes']['edit'])
                <a href="{{ route($attrs['routes']['edit'], array_merge([$item], $attrs['routes']['params'])) }}"
                   class="text-warning"><i class="fas fa-edit"></i></a>
            @endisset
            @isset($attrs['routes']['delete'])
                <a href="#" class="text-danger js-click-submit" data-method="DELETE"
                   data-url="{{ route($attrs['routes']['delete'], array_merge([$item], $attrs['routes']['params'])) }}"
                   data-confirm="Delete?"><i class="fas fa-trash"></i></a>
            @endisset
        </div>
        @if(!empty($item->children) && $item->children->count())
            <ul>
                @include($attrs['item'], ['items' => $item->children, 'attrs' => $attrs])
            </ul>
        @elseif($attrs['has_nested'])
            <ul></ul>
        @endif
    </li>
@endforeach
