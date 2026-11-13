{{--
    Fila de cajas de metadatos (equivalente a flex con gap: 12px; dompdf no soporta flexbox).
    Parámetro: $items = ['Docente' => '...', 'Asignatura' => '...', ...]
--}}
<table class="pdf-meta">
    <tr>
        @foreach($items as $label => $valor)
            @if(!$loop->first)<td class="meta-gap"></td>@endif
            <td class="meta-box">
                <span class="meta-label">{{ $label }}</span>
                <span class="meta-value">{{ $valor !== '' && $valor !== null ? $valor : '—' }}</span>
            </td>
        @endforeach
    </tr>
</table>
