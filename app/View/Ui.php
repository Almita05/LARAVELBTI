<?php

namespace App\View;

/**
 * Clases de Tailwind compartidas por las vistas (antes cada vista definía sus propias
 * $input, $card, $label… en un bloque @php). Tailwind escanea este archivo
 * (ver @source en resources/css/app.css), así que aquí van completas, sin concatenar.
 *
 * Diseño formal: sin esquinas redondeadas (los radios del tema valen 0; ver resources/css/app.css).
 */
final class Ui
{
    public const INPUT = 'tw:h-10 tw:w-full tw:border tw:border-slate-300 tw:bg-white tw:px-3 tw:text-[0.9375rem] tw:text-slate-900 tw:outline-none tw:placeholder:text-slate-400 tw:hover:border-slate-400 tw:focus:border-brand-700 tw:focus:ring-2 tw:focus:ring-brand-700/20';

    public const INPUT_ICON = self::INPUT . ' tw:pl-10';

    /** Panel principal de una vista: borde, sin sombra. */
    public const PANEL = 'tw:border tw:border-slate-200 tw:bg-white';

    /** Subtítulo de sección dentro de un panel: texto normal, sin mayúsculas ni espaciado. */
    public const SECTION_TITLE = 'tw:m-0 tw:mb-3 tw:text-[0.9375rem] tw:font-semibold tw:text-slate-800';

    public const LABEL = 'tw:mb-1 tw:block tw:text-[0.875rem] tw:font-medium tw:text-slate-700';
}
