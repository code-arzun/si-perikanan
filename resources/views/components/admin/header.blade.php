@props(['title', 'subtitle' => null])

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="color: #1e3a8a; margin: 0; font-size: 1.5rem;">{{ $title }}</h2>
        @if($subtitle)
            <span style="color: #6b7280; font-size: 0.9rem;">{{ $subtitle }}</span>
        @endif
    </div>
    @if(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>