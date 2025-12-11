@props(['title', 'action' => null, 'actionLabel' => null, 'backUrl' => null])

<div class="row mb-4">
    <div class="col-md-6">
        <h3>{{ $title }}</h3>
    </div>
    <div class="col-md-6 text-right">
        @if($backUrl)
        <a class="btn btn-primary mr-2" href="{{ $backUrl }}">Back</a>
        @endif
        @if($action && $actionLabel)
        <a class="btn btn-success" href="{{ $action }}">{{ $actionLabel }}</a>
        @endif
    </div>
</div>

