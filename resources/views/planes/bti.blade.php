@extends('layouts.app')

@section('content')

<style>

html, body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

/* Fondo igual al portal principal */
body {
    background: linear-gradient(
        135deg,
        #0F2E6D 0%,
        #1E6FA8 50%,
        #6BC7E8 100%
    );
}

/* Título */
h3 {
    color: white;
    text-shadow: 0 2px 8px rgba(0,0,0,.2);
}

/* Tabs */
.nav-tabs {
    border-bottom: none;
    justify-content: center;
}

.nav-tabs .nav-link {
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 12px;
    margin: 5px;

    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.15);

    transition: all .3s ease;
}

.nav-tabs .nav-link:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-2px);
}

.nav-tabs .nav-link.active {
    background: rgba(255,255,255,0.20);
    color: white;
    border: 1px solid rgba(255,255,255,0.25);
    box-shadow: 0 5px 15px rgba(0,0,0,.2);
}

/* Grid */
.row-materias {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

/* Tarjetas */
.card-materia {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);

    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 18px;

    padding: 25px;
    color: white;

    transition: all .3s ease;

    box-shadow: 0 5px 15px rgba(0,0,0,.15);
}

.card-materia:hover {
    transform: translateY(-8px);
    background: rgba(255,255,255,0.12);
    box-shadow: 0 12px 25px rgba(0,0,0,.25);
}

.card-materia::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;

    width: 100%;
    height: 4px;

    background: linear-gradient(
        90deg,
        #6BC7E8,
        #ffffff
    );
}

.card-materia h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Botón */
.btn-temario {
    background: linear-gradient(
        135deg,
        #6BC7E8,
        #1E6FA8
    );

    border: none;
    color: white;
    font-weight: 600;

    border-radius: 10px;
    padding: 10px 16px;

    transition: all .3s ease;
}

.btn-temario:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107,199,232,.4);
}

/* Espaciado general */
.container {
    padding-top: 30px;
    padding-bottom: 40px;
}
.btn-regresar {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    font-weight: 600;
    border-radius: 12px;
    padding: 10px 18px;
    text-decoration: none;
    transition: all .3s ease;
}

.btn-regresar:hover {
    background: rgba(255,255,255,0.2);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,.2);
}

</style>

<div class="container py-4">

    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn-regresar">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Regresar
        </a>
    </div>

    <h3 class="text-center mb-5 fw-bold text-white">
        <i class="fa-solid fa-book-open-reader me-2"></i>
        Planes de Estudio - BTI
    </h3>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1">1ER</button></li>
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
<div class="card-materia"><h5>Álgebra</h5><a href="https://drive.google.com/file/d/1_P48AFfUycZ1XxjEZkc6-WsfwcdJQSgl/view?usp=sharing" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Química I</h5><a href="https://drive.google.com/file/d/1sj8UxdlvtttIBhE5b58yX129Vx-qOUTZ/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Lógica</h5><a href="https://drive.google.com/file/d/1sTNbGfGfgD28TvN2NuiGDSEv04K6dgXy/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Lectura y Expresión I</h5><a href="https://drive.google.com/file/d/1CNyn7VTVRvPGWGF5sns0EtvgDGxoo7lp/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Informática I</h5><a href="https://drive.google.com/file/d/1cnglulpBivDMMvngRPkQ42gYZTGew2v_/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Inglés I</h5><a href="https://drive.google.com/file/d/1OS4yF6v-BwqoYooPjKAb5h-zvkOthLIT/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

<!-- 2 -->
<div class="tab-pane fade" id="tab2">
<div class="row-materias">
<div class="card-materia"><h5>Geometría y Trigonometría</h5><a href="https://drive.google.com/file/d/1dFb3PRwQY5wfeXKvWOlNuMb2IBs_wDeo/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Ética</h5><a href="https://drive.google.com/file/d/1o6ZKUfNTd3efaOonATtcmQo8o3H59HO6/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Inglés II</h5><a href="https://drive.google.com/file/d/1JE9YqD7DFtfMkVDV1k_ru0XCC0OrpF35/view?usp=sharing" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Lectura y Expresión II</h5><a href="https://drive.google.com/file/d/1JQMFZZ2upT_1U14Ah0CV6nkSLTDnffuq/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

<!-- 3 -->
<div class="tab-pane fade" id="tab3">
<div class="row-materias">
<div class="card-materia"><h5>Geometría Analítica</h5><a href="https://drive.google.com/file/d/133T5c_iK_Kx63TtZfnj42Y3bGvjWS3uF/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Biología</h5><a href="https://drive.google.com/file/d/17tUp_LZrl8ftqtyFmElZOvZxuKAyxsD_/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Inglés III</h5><a href="https://drive.google.com/file/d/1kUJ_1RR0KX9GrkLpbWNb9h3PGQESQeyQ/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Ética</h5><a href="https://drive.google.com/file/d/1oSyAzGErV-iWUrebv9YjvZUoaGwvp9vx/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

<!-- 4 -->
<div class="tab-pane fade" id="tab4">
<div class="row-materias">
<div class="card-materia"><h5>Cálculo Diferencial</h5><a href="https://drive.google.com/file/d/1C_7yhlPNQ163Zo_lQyj-ShdqQD1IlGbh/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Inglés</h5><a href="https://drive.google.com/file/d/1h4K_sgWpesi1xHRiPGpn-bo8mtkY7w0F/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Física</h5><a href="https://drive.google.com/file/d/1SfQsHRKT3fOMP8k1sOqbsIt5a0G0puAG/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Ecología</h5><a href="https://drive.google.com/file/d/1zaUyLjmlsU_sZJqPw19YyNFZu1LhNhCN/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

<!-- 5 -->
<div class="tab-pane fade" id="tab5">
<div class="row-materias">
<div class="card-materia"><h5>Cálculo Integral</h5><a href="https://drive.google.com/file/d/188ZIGgqDWO_V6pcb5EhK-1l-ZE6kiNQD/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>CTSyV</h5><a href="https://drive.google.com/file/d/1-wUxXGcRQs_EXXogsrcifZtJev2W_7WO/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Física II</h5><a href="https://drive.google.com/file/d/1pCucIgw7vR8luqhr3UFj_1kuCvemEbW1/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

<!-- 6 -->
<div class="tab-pane fade" id="tab6">
<div class="row-materias">
<div class="card-materia"><h5>Probabilidad y Estadística</h5><a href="https://drive.google.com/file/d/1kIFUju6pof0LEW1lOej4WWJAlwx0MnBM/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
<div class="card-materia"><h5>Filosofía</h5><a href="https://drive.google.com/file/d/16nZubuScda1uPkkyxZGN11bKLKy8KQbd/view?usp=drive_link" target="_blank" class="btn btn-temario mt-2">Descargar</a></div>
</div>
</div>

</div>
</div>

@endsection