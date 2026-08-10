@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/estilosBtiPlanes.css') }}">

<div class="mb-4">
    <a href="{{ url()->previous() }}" class="btn-regresar">
        <i class="fa-solid fa-arrow-left me-2"></i>
        Regresar
    </a>
</div>

<div class="page-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="page-title mb-0"> <i class="fa-solid fa-book-open-reader me-2"></i> Planes de Estudio - BTI </h3>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1">1ER</button>
        </li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2">2DO</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab3">3ER</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab4">4TO</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab5">5TO</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab6">6TO</button></li>
    </ul>

    <div class="tab-content">

        <!-- 1 -->
        <div class="tab-pane fade show active" id="tab1">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Álgebra</h5><a
                        href="https://drive.google.com/file/d/1_P48AFfUycZ1XxjEZkc6-WsfwcdJQSgl/view?usp=sharing"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Química I</h5><a
                        href="https://drive.google.com/file/d/1sj8UxdlvtttIBhE5b58yX129Vx-qOUTZ/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Lógica</h5><a
                        href="https://drive.google.com/file/d/1sTNbGfGfgD28TvN2NuiGDSEv04K6dgXy/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Lectura y Expresión I</h5><a
                        href="https://drive.google.com/file/d/1CNyn7VTVRvPGWGF5sns0EtvgDGxoo7lp/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Informática I</h5><a
                        href="https://drive.google.com/file/d/1cnglulpBivDMMvngRPkQ42gYZTGew2v_/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Inglés I</h5><a
                        href="https://drive.google.com/file/d/1OS4yF6v-BwqoYooPjKAb5h-zvkOthLIT/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

        <!-- 2 -->
        <div class="tab-pane fade" id="tab2">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Geometría y Trigonometría</h5><a
                        href="https://drive.google.com/file/d/1dFb3PRwQY5wfeXKvWOlNuMb2IBs_wDeo/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Ética</h5><a
                        href="https://drive.google.com/file/d/1o6ZKUfNTd3efaOonATtcmQo8o3H59HO6/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Inglés II</h5><a
                        href="https://drive.google.com/file/d/1JE9YqD7DFtfMkVDV1k_ru0XCC0OrpF35/view?usp=sharing"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Lectura y Expresión II</h5><a
                        href="https://drive.google.com/file/d/1JQMFZZ2upT_1U14Ah0CV6nkSLTDnffuq/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

        <!-- 3 -->
        <div class="tab-pane fade" id="tab3">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Geometría Analítica</h5><a
                        href="https://drive.google.com/file/d/133T5c_iK_Kx63TtZfnj42Y3bGvjWS3uF/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Biología</h5><a
                        href="https://drive.google.com/file/d/17tUp_LZrl8ftqtyFmElZOvZxuKAyxsD_/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Inglés III</h5><a
                        href="https://drive.google.com/file/d/1kUJ_1RR0KX9GrkLpbWNb9h3PGQESQeyQ/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Ética</h5><a
                        href="https://drive.google.com/file/d/1oSyAzGErV-iWUrebv9YjvZUoaGwvp9vx/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

        <!-- 4 -->
        <div class="tab-pane fade" id="tab4">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Cálculo Diferencial</h5><a
                        href="https://drive.google.com/file/d/1C_7yhlPNQ163Zo_lQyj-ShdqQD1IlGbh/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Inglés</h5><a
                        href="https://drive.google.com/file/d/1h4K_sgWpesi1xHRiPGpn-bo8mtkY7w0F/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Física</h5><a
                        href="https://drive.google.com/file/d/1SfQsHRKT3fOMP8k1sOqbsIt5a0G0puAG/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Ecología</h5><a
                        href="https://drive.google.com/file/d/1zaUyLjmlsU_sZJqPw19YyNFZu1LhNhCN/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

        <!-- 5 -->
        <div class="tab-pane fade" id="tab5">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Cálculo Integral</h5><a
                        href="https://drive.google.com/file/d/188ZIGgqDWO_V6pcb5EhK-1l-ZE6kiNQD/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>CTSyV</h5><a
                        href="https://drive.google.com/file/d/1-wUxXGcRQs_EXXogsrcifZtJev2W_7WO/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Física II</h5><a
                        href="https://drive.google.com/file/d/1pCucIgw7vR8luqhr3UFj_1kuCvemEbW1/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

        <!-- 6 -->
        <div class="tab-pane fade" id="tab6">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Probabilidad y Estadística</h5><a
                        href="https://drive.google.com/file/d/1kIFUju6pof0LEW1lOej4WWJAlwx0MnBM/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
                <div class="card-materia">
                    <h5>Filosofía</h5><a
                        href="https://drive.google.com/file/d/16nZubuScda1uPkkyxZGN11bKLKy8KQbd/view?usp=drive_link"
                        target="_blank" class="btn btn-temario mt-2">Descargar</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection