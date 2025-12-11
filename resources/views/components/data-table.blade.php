@props(['headers', 'emptyMessage' => 'No records found.', 'colspan' => null])

<table class="table table-bordered">
    <thead>
        <tr>
            @foreach($headers as $header)
            <th>{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if(isset($slot) && !empty(trim($slot)))
        {{ $slot }}
        @else
        <tr>
            <td class="text-center" colspan="{{ $colspan ?? count($headers) }}">
                <h4>{{ $emptyMessage }}</h4>
            </td>
        </tr>
        @endif
    </tbody>
</table>

