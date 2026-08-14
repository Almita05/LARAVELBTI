@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/estilosBtiPlanes.css') }}">

<div class="page-container">


{{-- ENCABEZADO --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <a href="{{ url()->previous() }}" class="btn btn-regresar">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Regresar
    </a>

    <h3 class="page-title mb-0 text-center">
        <i class="fa-solid fa-chalkboard-user me-2"></i>
        Planes de estudio BTI
    </h3>

</div>


<div class="glass-card">

    {{-- PESTAÑAS --}}
    <div class="tabs-container mb-4">

        <ul class="nav nav-tabs justify-content-center" role="tablist">

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link active"
                    id="tab1-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab1"
                    type="button"
                    role="tab"
                    aria-controls="tab1"
                    aria-selected="true"
                >
                    1ER
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="tab2-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab2"
                    type="button"
                    role="tab"
                    aria-controls="tab2"
                    aria-selected="false"
                >
                    2DO
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="tab3-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab3"
                    type="button"
                    role="tab"
                    aria-controls="tab3"
                    aria-selected="false"
                >
                    3ER
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="tab4-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab4"
                    type="button"
                    role="tab"
                    aria-controls="tab4"
                    aria-selected="false"
                >
                    4TO
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="tab5-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab5"
                    type="button"
                    role="tab"
                    aria-controls="tab5"
                    aria-selected="false"
                >
                    5TO
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="tab6-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#tab6"
                    type="button"
                    role="tab"
                    aria-controls="tab6"
                    aria-selected="false"
                >
                    6TO
                </button>
            </li>

        </ul>

    </div>

    <div class="tab-content">


        {{-- ================================================= --}}
        {{-- 1ER SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade show active"
            id="tab1"
            role="tabpanel"
            aria-labelledby="tab1-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Álgebra</h5>
                            <p class="card-text">
                                Material de estudio de Álgebra.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1_P48AFfUycZ1XxjEZkc6-WsfwcdJQSgl/view?usp=sharing"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Química I</h5>
                            <p class="card-text">
                                Material de estudio de Química I.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1sj8UxdlvtttIBhE5b58yX129Vx-qOUTZ/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Lógica</h5>
                            <p class="card-text">
                                Material de estudio de Lógica.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1sTNbGfGfgD28TvN2NuiGDSEv04K6dgXy/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Lectura y Expresión I</h5>
                            <p class="card-text">
                                Material de estudio de Lectura y Expresión I.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1CNyn7VTVRvPGWGF5sns0EtvgDGxoo7lp/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Informática I</h5>
                            <p class="card-text">
                                Material de estudio de Informática I.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1cnglulpBivDMMvngRPkQ42gYZTGew2v_/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Inglés I</h5>
                            <p class="card-text">
                                Material de estudio de Inglés I.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1OS4yF6v-BwqoYooPjKAb5h-zvkOthLIT/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- 2DO SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade"
            id="tab2"
            role="tabpanel"
            aria-labelledby="tab2-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Geometría y Trigonometría</h5>
                            <p class="card-text">
                                Material de estudio de Geometría y Trigonometría.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1dFb3PRwQY5wfeXKvWOlNuMb2IBs_wDeo/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ética</h5>
                            <p class="card-text">
                                Material de estudio de Ética.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1o6ZKUfNTd3efaOonATtcmQo8o3H59HO6/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Inglés II</h5>
                            <p class="card-text">
                                Material de estudio de Inglés II.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1JE9YqD7DFtfMkVDV1k_ru0XCC0OrpF35/view?usp=sharing"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Lectura y Expresión II</h5>
                            <p class="card-text">
                                Material de estudio de Lectura y Expresión II.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1JQMFZZ2upT_1U14Ah0CV6nkSLTDnffuq/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- 3ER SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade"
            id="tab3"
            role="tabpanel"
            aria-labelledby="tab3-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Geometría Analítica</h5>
                            <p class="card-text">
                                Material de estudio de Geometría Analítica.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/133T5c_iK_Kx63TtZfnj42Y3bGvjWS3uF/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Biología</h5>
                            <p class="card-text">
                                Material de estudio de Biología.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/17tUp_LZrl8ftqtyFmElZOvZxuKAyxsD_/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Inglés III</h5>
                            <p class="card-text">
                                Material de estudio de Inglés III.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1kUJ_1RR0KX9GrkLpbWNb9h3PGQESQeyQ/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ética</h5>
                            <p class="card-text">
                                Material de estudio de Ética.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1oSyAzGErV-iWUrebv9YjvZUoaGwvp9vx/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- 4TO SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade"
            id="tab4"
            role="tabpanel"
            aria-labelledby="tab4-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Cálculo Diferencial</h5>
                            <p class="card-text">
                                Material de estudio de Cálculo Diferencial.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1C_7yhlPNQ163Zo_lQyj-ShdqQD1IlGbh/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Inglés</h5>
                            <p class="card-text">
                                Material de estudio de Inglés.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1h4K_sgWpesi1xHRiPGpn-bo8mtkY7w0F/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Física</h5>
                            <p class="card-text">
                                Material de estudio de Física.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1SfQsHRKT3fOMP8k1sOqbsIt5a0G0puAG/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ecología</h5>
                            <p class="card-text">
                                Material de estudio de Ecología.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1zaUyLjmlsU_sZJqPw19YyNFZu1LhNhCN/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- 5TO SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade"
            id="tab5"
            role="tabpanel"
            aria-labelledby="tab5-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Cálculo Integral</h5>
                            <p class="card-text">
                                Material de estudio de Cálculo Integral.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/188ZIGgqDWO_V6pcb5EhK-1l-ZE6kiNQD/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">CTSyV</h5>
                            <p class="card-text">
                                Material de estudio de CTSyV.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1-wUxXGcRQs_EXXogsrcifZtJev2W_7WO/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Física II</h5>
                            <p class="card-text">
                                Material de estudio de Física II.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1pCucIgw7vR8luqhr3UFj_1kuCvemEbW1/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- 6TO SEMESTRE --}}
        {{-- ================================================= --}}
        <div
            class="tab-pane fade"
            id="tab6"
            role="tabpanel"
            aria-labelledby="tab6-tab"
            tabindex="0"
        >

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Probabilidad y Estadística</h5>
                            <p class="card-text">
                                Material de estudio de Probabilidad y Estadística.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/1kIFUju6pof0LEW1lOej4WWJAlwx0MnBM/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card materia-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Filosofía</h5>
                            <p class="card-text">
                                Material de estudio de Filosofía.
                            </p>
                            <a
                                href="https://drive.google.com/file/d/16nZubuScda1uPkkyxZGN11bKLKy8KQbd/view?usp=drive_link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-primary"
                            >
                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>


</div>

@endsection
