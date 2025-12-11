@props(['editRoute' => null, 'deleteRoute' => null, 'viewRoute' => null, 'itemId' => null])

<div class="d-flex gap-2">
    @if($viewRoute && $itemId)
    <a class="btn btn-success btn-sm" href="{{ route($viewRoute, $itemId) }}" title="View">
        <i class="fa-solid fa-eye"></i>
    </a>
    @endif
    @if($editRoute && $itemId)
    <a class="btn btn-primary btn-sm" href="{{ route($editRoute, $itemId) }}" title="Edit">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    @endif
    @if($deleteRoute && $itemId)
    <form action="{{ route($deleteRoute, $itemId) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
            <i class="fa fa-trash" aria-hidden="true"></i>
        </button>
    </form>
    @endif
</div>

