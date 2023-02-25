<script>
    @php
        $flashKeys = ['warning','success','info','error',]
    @endphp

    @foreach ($flashKeys as $keyName)
        @if (Session::has($keyName))
            toastr.{{$keyName}}("{{ Session::get($keyName) }}");
        @endif
    @endforeach

    @if (isset($errors) && $errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif
</script>
