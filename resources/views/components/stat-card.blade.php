<div class="stat-card {{ $class ?? '' }}">
    <div class="stat-icon {{ $iconClass ?? '' }}">
        {{ $icon ?? '📊' }}
    </div>
    <div class="stat-details">
        <div class="stat-value">{{ $value }}</div>
        <div class="stat-label">{{ $label }}</div>
        @if(isset($change))
            <div class="stat-change {{ $changeType ?? 'positive' }}">
                <span class="change-arrow">{{ ($changeType ?? 'positive') === 'positive' ? '↑' : '↓' }}</span>
                <span class="change-value">{{ $change }}</span>
            </div>
        @endif
    </div>
</div>
