@props(['title', 'value', 'subtext' => null, 'borderColor' => '#2563eb', 'valueColor' => '#1e3a8a'])

<div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid {{ $borderColor }};">
    <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">{{ $title }}</div>
    <div style="font-size: 1.8rem; font-weight: bold; color: {{ $valueColor }}; margin: 4px 0;">{{ $value }}</div>
    @if($subtext)
        <small style="color: #6b7280;">{{ $subtext }}</small>
    @endif
</div>