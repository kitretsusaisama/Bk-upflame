<div class="table-container {{ $class ?? '' }}">
    <table class="table {{ $tableClass ?? '' }}">
        @if(isset($headers))
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        
        <tbody>
            {{ $slot }}
        </tbody>
        
        @if(isset($footer))
            <tfoot>
                {!! $footer !!}
            </tfoot>
        @endif
    </table>
</div>

@if(isset($pagination) && $pagination)
    <div class="table-pagination">
        <div class="pagination-info">
            Showing {{ $pagination->firstItem() }} to {{ $pagination->lastItem() }} of {{ $pagination->total() }} entries
        </div>
        <div class="pagination-links">
            {{ $pagination->links() }}
        </div>
    </div>
@endif
