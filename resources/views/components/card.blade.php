<div class="card {{ $class ?? '' }}">
    @if(isset($title) || isset($actions))
        <div class="card-header">
            @if(isset($title))
                <h3 class="card-title">{{ $title }}</h3>
            @endif
            @if(isset($actions))
                <div class="card-actions">
                    {!! $actions !!}
                </div>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif
</div>
