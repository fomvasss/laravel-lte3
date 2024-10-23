<div class="form-group {{ $attrs['class_wrap'] ?? null }}" @if(!empty($attrs['hidden_wrap'])) hidden @endif>
    @if(($label = Arr::get($attrs, 'label', Str::studly($name))) !== '')
        <label for="{{ $name }}">{!! $label !!}</label>
    @endif

    <textarea class="form-control @error($name) is-invalid @enderror {{ $attrs['class'] ?? '' }}"
              name="{{ $name }}"
              data-toggle="tooltip"
    @if(Arr::get($attrs, 'disabled')) disabled @endif
    @foreach(Arr::only($attrs, $field_attrs) as $key => $val)
        {{$key}}="{{$val}}"
    @endforeach
    @if(isset($attrs['data']) && is_array($attrs['data']))
        @foreach($attrs['data'] as $dataKey => $dataVal)
          data-{{$dataKey}}="{{$dataVal}}"
        @endforeach
    @endif
    >{!! $value !!}</textarea>

    @error($name)
    <div class="error invalid-feedback"> {{ $message }} </div>@enderror
    @isset($attrs['help'])<span style="width: 100%;"><small>{!! $attrs['help'] !!}</small></span>@endisset
</div>
