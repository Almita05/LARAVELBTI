{{--
    Encabezado estándar de PDF.
    Parámetros: $titulo, $subtitulo, $badge (opcional), $badgeSub (opcional),
                $logo (opcional, ruta relativa a /public; por defecto img/logo.png)
--}}
@php
    $logoPath = public_path($logo ?? 'img/logo.png');
    $logoSrc = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
@endphp
<div class="pdf-header">
    <table>
        <tr>
            @if($logoSrc)
                <td class="logo-cell"><img src="{{ $logoSrc }}" class="pdf-logo" alt="Logo"></td>
            @endif
            <td>
                <h1 class="pdf-title">{{ $titulo ?? 'Bachillerato Tecnológico Interamericano' }}</h1>
                <h2 class="pdf-subtitle">{{ $subtitulo ?? '' }}</h2>
            </td>
            @if(!empty($badge) || !empty($badgeSub))
                <td class="badge-cell">
                    @if(!empty($badge))<span class="pdf-badge">{{ $badge }}</span>@endif
                    @if(!empty($badgeSub))<div class="pdf-badge-sub">{{ $badgeSub }}</div>@endif
                </td>
            @endif
        </tr>
    </table>
</div>
