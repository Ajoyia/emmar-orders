@props(['type' => 'success', 'message' => null])

@if($message || isset($slot))
<div class="alert alert-{{ $type }} mb-3">
    <p class="mb-0">{{ $message ?? $slot }}</p>
</div>
@endif

