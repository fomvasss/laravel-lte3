<div class="form-group position-relative {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $name }}">{!! $label !!}</label>
    @endif

    <textarea class="form-control @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
              name="{{ $name }}"
              data-toggle="tooltip"
    @if(Arr::get($attrs, 'disabled')) disabled @endif
    @if(Arr::get($attrs, 'readonly')) readonly @endif
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @foreach(Arr::get($attrs, 'attrs') ?? [] as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @if(isset($attrs['data']) && is_array($attrs['data']))
        @foreach($attrs['data'] as $dataKey => $dataVal)
          data-{{$dataKey}}="{{$dataVal}}"
        @endforeach
    @endif
    >{!! $value !!}</textarea>

    @if(Arr::get($attrs, 'tokens'))
        <div class="btn-group btn-group-tokens dropleft">
            <button type="button" class="btn btn-default p-0 border-0 bg-transparent" data-toggle="dropdown" aria-expanded="false">
                <i class="far fa-caret-square-down"></i>
            </button>
            <div class="dropdown-menu" role="menu">
                @foreach(Arr::get($attrs, 'tokens', []) as $key => $name)
                    <a class="dropdown-item js-clipboard" href="#" data-text="{{ $key }}">{{ $name }} - {{ $key }}</a>
                @endforeach
            </div>
        </div>
    @endif

    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
