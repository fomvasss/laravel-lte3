@php($collapsed = isset($collapsed) ? $collapsed : !request()->has('_f'))

{!! Lte3::formOpen(['action' => Request::fullUrl(), 'method' => 'GET']) !!}
<div class="collapse @if(!$collapsed) show @endif" id="collapseFilter">
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="_f" value="1">
            @yield('body')
        </div>
        <div class="card-footer text-right">
            {!! Lte3::btnReset('Reset') !!}
            {!! Lte3::btnSubmit('Submit') !!}
        </div>
    </div>
</div>
{!! Lte3::formClose() !!}

@if(request('q'))
    @push('scripts')
        <script>
            var searchText = "{{ request('q') }}";

            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            var searchRegExp = new RegExp(escapeRegExp(searchText), 'gi');

            $('body .content').find('*').contents().each(function() {
                if (this.nodeType === 3 && this.nodeValue.trim() !== '') {
                    var text = this.nodeValue.trim();
                    if (searchRegExp.test(text)) {
                        var highlightedText = text.replace(searchRegExp, function(match) {
                            return '<span class="lte-filter-searched">' + match + '</span>';
                        });
                        $(this).replaceWith(highlightedText);
                    }
                }
            });
        </script>
    @endpush
@endif
