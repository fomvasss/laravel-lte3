<div class="modal-header"><h4 class="modal-title">Modal 1</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <p>AJAX data ...</p>
    {!! Lte3::checkbox('enabled', null, ['label' => 'Enabled',  'id' => 'modal-enabled',]) !!}
    {!! Lte3::select2('status', 'canceled', ['new' => 'New', 'canceled' => 'Canceled', 'delivered' => 'Delivered'], [
        'label' => 'Status',
    ]) !!}
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary">Save changes</button>
</div>
