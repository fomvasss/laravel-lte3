<div class="container">
    {!! Lte3::formOpen(['action' => $attrs['action'] ?? '#', null, 'method' => ($attrs['method'] ?? 'POST') === 'GET' ? 'GET' : 'POST']) !!}
    <div class="table__options-wrapper">
        <button type="button" class="btn table__options-button" data-toggle="modal" data-target="#table__options-modal">
            <i class="fas fa-sliders-h table__options-icon"></i>
            <span class="table__options-text">{!! $attrs['btn_modal_title'] ?? 'Select columns' !!}</span>
        </button>
    </div>

    <div class="modal fade" id="table__options-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" style="position: fixed; margin: auto; width: 320px; height: 100%; right: 0;" role="document">
            <div class="modal-content" style="height: 100%;">
                <div class="modal-header">
                    <h5>{!! $attrs['btn_modal_title'] ?? 'Select columns' !!}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="sortable-y" data-input-weight-class="js-input-weight">
                        @php
                            $table = $attrs['table'] ?? '';
                            $name = $attrs['name'] ?? 'options';
                            $columns = $columns ?? [];

                            // Сортуємо масив $columns за значенням weight
                            usort($columns, function ($a, $b) use ($options) {
                                $weightA = $options[$a['key']]['weight'] ?? 0;
                                $weightB = $options[$b['key']]['weight'] ?? 0;
                                return $weightA - $weightB;
                            });
                        @endphp

                        @foreach($columns as $column)
                            @if($column['hidden'] ?? false) @continue @endif

                            <div class="f-item d-flex align-items-center">
                                <i class="fa fa-sort cursor-move" style="margin: 0 5px 1rem 0;"></i>
                                {!! Lte3::checkbox("{$name}[{$table}][{$column['key']}][active]", $options[$column['key']]['active'] ?? $column['default'] ?? 1, [
                                    'label' => $column['name'],
                                    'class_control' => 'custom-switch'
                                ]) !!}
                                {!! Lte3::hidden("{$name}[{$table}][{$column['key']}][weight]", $options[$column['key']]['weight'] ?? $loop->index, [
                                    'class' => 'js-input-weight',
                                ]) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Lte3::btnSubmit($attrs['btn_reset_title'] ?? 'Reset', $name, '[]', ['class' => 'btn-dark btn-default']) !!}

                    {!! Lte3::btnSubmit($attrs['btn_save_title'] ?? 'Submit') !!}
                </div>
            </div>
        </div>
    </div>
    {!! Lte3::formClose() !!}
</div>

@if($attrs['preloader'] ?? true)
    <div class="overlay @if(session('lte_theme') === 'dark' || config('lte3.view.dark_mode')) dark @endif" style="backdrop-filter: blur(7px);" id="{{ $attrs['preloader_id'] ?? 'table-preloader' }}">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
@endif
