@props(['paginator'])

<div class="pagination-wrapper mt-4 text-right">
    {!! $paginator->appends(request()->except('page'))->links() !!}
</div>

