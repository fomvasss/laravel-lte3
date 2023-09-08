<div class="container-fluid">
    @if($message = Session::get('callout.error') || Session::get('callout.danger'))
    <div class="callout callout-danger">
        <h5>Danger!</h5>
        <p>{!! $message !!}</p>
    </div>
    @endif
    @if($message = Session::get('callout.info'))
    <div class="callout callout-info">
        <h5>Info!</h5>
        <p>{!! $message !!}</p>
    </div>
    @endif
    @if($message = Session::get('callout.warning'))
    <div class="callout callout-warning">
        <h5>Warning!</h5>
        <p>{!! $message !!}</p>
    </div>
    @endif
    @if($message = Session::get('callout.success'))
    <div class="callout callout-success">
        <h5>Success!</h5>
        <p>{!! $message !!}</p>
    </div>
    @endif
</div>