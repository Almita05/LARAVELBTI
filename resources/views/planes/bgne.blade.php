@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/estilosBgnePlanes.css') }}">

<div class="planes-bgne-container">

    {{-- ENCABEZADO EJECUTIVO --}}
    <div class="planes-header">
        <div class="planes-header-left">
            <a href="{{ url()->previous() }}" class="btn-regresar-custom">
                <i class="ti ti-arrow-left"></i>
                <span>Regresar</span>
            </a>
            <div class="planes-title-wrapper">
                <h1>
                    <i class="ti ti-notebook" style="color: #1E5B66;"></i>
                    Planes de Estudio BGNE
                </h1>
                <p>Bachillerato General No Escolarizado • Plan Modular por Trimestres</p>
            </div>
        </div>

        <div>
            <span class="badge-cct-institucional">
                <i class="ti ti-school"></i>
                <span>Bachillerato Interamericano • CCT: 21PBH0353G</span>
            </span>
        </div>
    </div>

    {{-- CONTENEDOR PRINCIPAL UNIFICADO --}}
    <div class="planes-main-card">

        {{-- PESTAÑAS DE TRIMESTRES (1° AL 6°) --}}
        <div class="custom-tabs-container">
            <ul class="nav custom-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1"
                        type="button" role="tab" aria-controls="tab1" aria-selected="true">
                        <i class="ti ti-number-1"></i>
                        <span>1° Trimestre</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2"
                        type="button" role="tab" aria-controls="tab2" aria-selected="false">
                        <i class="ti ti-number-2"></i>
                        <span>2° Trimestre</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab3-tab" data-bs-toggle="tab" data-bs-target="#tab3"
                        type="button" role="tab" aria-controls="tab3" aria-selected="false">
                        <i class="ti ti-number-3"></i>
                        <span>3° Trimestre</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab4-tab" data-bs-toggle="tab" data-bs-target="#tab4"
                        type="button" role="tab" aria-controls="tab4" aria-selected="false">
                        <i class="ti ti-number-4"></i>
                        <span>4° Trimestre</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab5-tab" data-bs-toggle="tab" data-bs-target="#tab5"
                        type="button" role="tab" aria-controls="tab5" aria-selected="false">
                        <i class="ti ti-number-5"></i>
                        <span>5° Trimestre</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab6-tab" data-bs-toggle="tab" data-bs-target="#tab6"
                        type="button" role="tab" aria-controls="tab6" aria-selected="false">
                        <i class="ti ti-number-6"></i>
                        <span>6° Trimestre</span>
                    </button>
                </li>
            </ul>
        </div>


        {{-- CONTENIDO DE LAS PESTAÑAS --}}
        <div class="tab-content">

            {{-- ================================================= --}}
            {{-- 1ER TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab" tabindex="0">
                <div class="row g-3">

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-map-pin"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Geografía I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1QKqi7B-x-hfhZ1X2Baef8V-elYOWAJst/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1jroJFDA0kEaaECr8Ybb8H8ro7-csh1cw/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-device-laptop"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Informática I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1KIIvYlcHu8SX-iBfPiA0KF0wuiIG71Rp/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1RISe6X9RjXOlaZCmco1tU_7fRWrbZrvu/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1EMh-6GfBZVIeVQxSA7J3_0hBI6PUZ6Bl/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1X3WUWd5YezTgDyf1q4V0UfTKxJ4j9LY5/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Introducción a ciencias sociales I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1T7u40K-csXYjtClL1Vm_BOSX4AX4EtSc/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1xgD09AF8UNFhxX4eGmdBZpF05YoyrRcQ/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-symbols"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Matemáticas I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1x-ZtNJvC3ZX8LRRWct7UBIKbPCpCWhPj/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1X9pwTAZkRlIZSjGm2tHAayoPwXjOT4Kx/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-flask"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Química I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1-OYOIwULmWGMQmMcbdAc6pD5Ih9TEzz4/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1td6EuJJA-SM9Rf6mmv_caldlub9JPljn/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-writing"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Taller de lectura y redacción I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1JAIVXP4m0jWSIzv0QNZGnexzrPr48uaA/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1aM5pz-emTuLNssqyumxlOygevsBCY-CN/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- 2DO TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab" tabindex="0">
                <div class="row g-3">

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-dna"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Biología I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1ntPNYnqH9FdgzHKbjw3AN_UY1yPidxDN/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1uBE9Ke-HlmkSF2RpYM1I0bPGUJFLLe-T/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Historia de México I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1S9m6uL8DmP3n8Dwr3QGo-f1uL4__pM7d/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1Oxe2F4zmrAXu12peDHd_iQ2cJOaUompI/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-device-laptop"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Informática II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1a58ICh-EDtVwsIsE3LsbLf4W6vxn2_Iu/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1Oxe2F4zmrAXu12peDHd_iQ2cJOaUompI/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1JhgnssJOK0sofsEvxPa0su6fDjgKPhsh/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1amSM3EfAxTI8lBjARU_X4nwVu3OUmKZT/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-symbols"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Matemáticas II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1zSmUTKaSGaBXt-5Ye1XzLILOs44kVyUJ/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1plzjHY8Dccw4U72sER-SJcGps2StLAj8/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-flask"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Química II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1_rGi6RsDeHpBYnF26eTe19gptiEIEOGO/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1k07Q23QG6SSoo0ovIEuMe8PTMXrXhWBP/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-writing"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Taller de lectura y redacción II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1ccbl9e5ZaIZHokNh_Mzwnylphdbqn0fW/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/15WgfYYCxLma6eliKX09EtJmI-0zsxC3V/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- 3ER TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab" tabindex="0">
                <div class="row g-3">

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-dna"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Biología III</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1_4E5-MOh6p6rrvBmDR-z4IB8mI_ZOcNi/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1UuqtEQD3d2lFqpUyfSlI6TOheSceQXzB/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-atom"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Física I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1mNq1ARo3HXOeAbxaFypqbay6FaDABK33/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/16MDGcqltY3TuVDbvLz82i4qQ88C8PsBx/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-history"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Historia de México II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1d4IP8jQ2UbBnZwEIehMIVAnWkmO-ngcv/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1dtLj4tC9ZX0eXKx1v8WUx_iuUNgNSCqm/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés III</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1YQcnLYLYzKX5-QMUvUBPCtPjBdwFeG_U/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1f04wR2o6G_Sq8MOqAQJpdPPjgcXlfY3b/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-books"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Literatura I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1hQG8pjpo70F4uXSifxJhZQvAk89XKSBu/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1-MGbkg91U8VfR-4CvXERI2QRF7ok0hpJ/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-symbols"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Matemáticas III</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1BW9ykzuCL09m2SXCiNBazmyL2f5K-S-X/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1ggdqgctiFSuiwqDsnNAc_meM63WUOtcj/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-search"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Metodología de la investigación I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1PRFGlt4AdxlNC4G3Oz2eO7hPexFWj2o2/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1Twx4dJIKT5e8am5Oa1z7D-4DpuVeYxWI/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- 4TO TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab" tabindex="0">
                <div class="row g-3">

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-leaf"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Ecología</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1k2yklEyKaWdwgN7NIQ1mhxFbmPhId0Z9/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1WozlVfgBrKaX_ARfMw79saNH9G3uKnD5/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-building-bank"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Estructura socioeconómica de México</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1d36NSt30UGNHPQZfX62FrxSuTPzjWy8m/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1WcAKYKevxzmykm-dQkgWGodc9OlK5_Bp/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-atom-2"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Física II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1H6IcgmOA4DU1LMJ3qoFiPtp1PwGVNx-o/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1tGymhPvCZdX6CEVbmAdVkxB8kLzAnna4/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés IV</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1coVnqVydot7oSkOLQAXjTMyztK8zkjzh/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1fqMOlUahN2uQxGSdr20EHpYTUTrk_kxw/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-symbols"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Matemáticas IV</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1gxj751KV8JifVklwJW0WJ-QyMCBSU-9i/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1SKLbZUvwOKNcIR1cYYeMJN_kxGQk38Or/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-search"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Metodología de la investigación II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1wzZY_dyJ2LGIRXz-cqpU88PcZRXEkTTF/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1sTo47L-Mz0M0Ip8zRicYt62U0uBZBZuA/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-books"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Literatura II</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1Tvvgxd1c5GhdIbTfTPKQBLmbO0TVJjk6/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1KL516Oss7EgQaudpvux6fgNMpuev6lPz/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- 5TO TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5-tab" tabindex="0">
                <div class="row g-3">

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-user-circle"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Individuo y sociedad</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1cjRzq9FzFCC8iRXj9g9BxGNbdc8wLlOL/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1ca3CtwBbPSn3IsrONQlzYa6hr_t9MJG-/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-function"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Cálculo Diferencial</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1g3p3I1X_oEm8cxAqBJQm02P7C1EBENzX/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/11Pi9u08Z-JHqUpSibcCfiRGjabCFXOkV/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-calculator"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Contabilidad I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1q44AkQBomx3G2Gtky7573Rq0CRntiVZc/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/125cwHUv-SBdo-4SlssVngpo0Js6bO6Dd/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-device-laptop"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Informática III</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1-bO6wm-gCzQElgo8Uomw2x0ohqjt1oQL/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1DxbyHqrDb_inx9nyMgMEhEvw2ZmPqCAP/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés V</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1vHnG6zqLMl3NTdDQkXnrcJKWmiDjRQjk/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1PQA-UrCgURt6bRTgijORWfukMTDvuQjI/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-dna"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Temas selectos de biología I</h5>
                                        <span class="materia-tag"><i class="ti ti-certificate"></i> Plan Curricular BGNE</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/12h1lQ7G0v0zOA36QtaHh0EQ8xtgDRHsG/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1bdvZhIaIiHiO-owHhZhjWDopvi2NbY4r/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            {{-- ================================================= --}}
            {{-- 6TO TRIMESTRE --}}
            {{-- ================================================= --}}
            <div class="tab-pane fade" id="tab6" role="tabpanel" aria-labelledby="tab6-tab" tabindex="0">

                {{-- MATRIZ CALENDARIO EXCEL: SEXTO TRIMESTRE - MATERIAS CURSADAS --}}
                <div class="matriz-excel-card">
                    <div class="matriz-banner-title">
                        <h3>
                            <i class="ti ti-table" style="color: #1E5B66;"></i>
                            Esquema Oficial de Materias Cursadas (Calendario Trimestral)
                        </h3>
                        <span class="matriz-info-badge">
                            <i class="ti ti-calendar-time"></i>
                            Distribución en 3 Bloques Mensuales (4 Semanas c/u)
                        </span>
                    </div>

                    <div class="matriz-excel-wrapper">
                        <table class="matriz-excel-table">
                            <thead>
                                {{-- Fila 1: SEXTO TRIMESTRE --}}
                                <tr>
                                    <th colspan="24" class="tr-header-trimestre">
                                        SEXTO TRIMESTRE
                                    </th>
                                </tr>
                                {{-- Fila 2: MATERIAS CURSADAS --}}
                                <tr>
                                    <th colspan="24" class="tr-header-materias">
                                        MATERIAS CURSADAS
                                    </th>
                                </tr>
                                {{-- Fila 3: Indicadores 'x' en las 4 semanas de Informática IV --}}
                                <tr>
                                    <td class="cell-x-indicator">x</td>
                                    <td class="cell-x-indicator">x</td>
                                    <td class="cell-x-indicator">x</td>
                                    <td class="cell-x-indicator">x</td>
                                    <td colspan="20" class="cell-x-empty"></td>
                                </tr>
                                {{-- Fila 4: Bloques Mensuales (MES I, MES II, MES III) --}}
                                <tr>
                                    <th colspan="8" class="th-mes-1">MES I</th>
                                    <th colspan="8" class="th-mes-2">MES II</th>
                                    <th colspan="8" class="th-mes-3">MES III</th>
                                </tr>
                                {{-- Fila 5: Asignaturas del Trimestre --}}
                                <tr>
                                    <th colspan="4" class="th-mat-informatica">INFORMATICA IV</th>
                                    <th colspan="4" class="th-mat-filosofia">FILOSOFIA</th>
                                    <th colspan="4" class="th-mat-calculo">CALCULO INTEGRAL</th>
                                    <th colspan="4" class="th-mat-biologia">TEMAS SELC.DE BIO II</th>
                                    <th colspan="4" class="th-mat-contabilidad">CONTABILIDAD II</th>
                                    <th colspan="4" class="th-mat-ingles">INGLES VI</th>
                                </tr>
                                {{-- Fila 6: Semanas (SEM 1 - SEM 4) por Asignatura --}}
                                <tr>
                                    {{-- MES I: Informática IV --}}
                                    <td class="td-sem-informatica">SEM 1</td>
                                    <td class="td-sem-informatica">SEM 2</td>
                                    <td class="td-sem-informatica">SEM 3</td>
                                    <td class="td-sem-informatica">SEM 4</td>
                                    {{-- MES I: Filosofía --}}
                                    <td class="td-sem-filosofia">SEM 1</td>
                                    <td class="td-sem-filosofia">SEM 2</td>
                                    <td class="td-sem-filosofia">SEM 3</td>
                                    <td class="td-sem-filosofia">SEM 4</td>

                                    {{-- MES II: Cálculo Integral --}}
                                    <td class="td-sem-calculo">SEM 1</td>
                                    <td class="td-sem-calculo">SEM 2</td>
                                    <td class="td-sem-calculo">SEM 3</td>
                                    <td class="td-sem-calculo">SEM 4</td>
                                    {{-- MES II: Temas Selc. de Bio II --}}
                                    <td class="td-sem-biologia">SEM 1</td>
                                    <td class="td-sem-biologia">SEM 2</td>
                                    <td class="td-sem-biologia">SEM 3</td>
                                    <td class="td-sem-biologia">SEM 4</td>

                                    {{-- MES III: Contabilidad II --}}
                                    <td class="td-sem-contabilidad">SEM 1</td>
                                    <td class="td-sem-contabilidad">SEM 2</td>
                                    <td class="td-sem-contabilidad">SEM 3</td>
                                    <td class="td-sem-contabilidad">SEM 4</td>
                                    {{-- MES III: Inglés VI --}}
                                    <td class="td-sem-ingles">SEM 1</td>
                                    <td class="td-sem-ingles">SEM 2</td>
                                    <td class="td-sem-ingles">SEM 3</td>
                                    <td class="td-sem-ingles">SEM 4</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- TARJETAS DE TEMARIOS Y FORMATOS DE 6TO TRIMESTRE (ORGANIZADAS POR MES) --}}

                {{-- BLOQUE 1: MES I --}}
                <div class="mes-section-divider">
                    <span class="mes-section-badge badge-mes-1">
                        <i class="ti ti-calendar"></i> MES I • Semanas 1 a 4
                    </span>
                    <div class="mes-section-line"></div>
                </div>

                <div class="row g-3 mb-2">
                    {{-- Informática IV --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-device-laptop"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Informática IV</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes I</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1QnKDet8M58x22kEMmUICi_W9gzSa911N/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/18Xyn8mfYWODVYkewYZSrwnHf20QZOw70/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filosofía --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-bulb"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Filosofía</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes I</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1G2krz0oh5reFKsQc4KzxQUC38rgq8J8I/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1vFjr6z50PxSiCo3lcmGEpoVnKboTcUpu/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOQUE 2: MES II --}}
                <div class="mes-section-divider">
                    <span class="mes-section-badge badge-mes-2">
                        <i class="ti ti-calendar"></i> MES II • Semanas 5 a 8
                    </span>
                    <div class="mes-section-line"></div>
                </div>

                <div class="row g-3 mb-2">
                    {{-- Cálculo Integral --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-math-function"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Cálculo Integral</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes II</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1v93DDUstoX58ITg0s6GBPr5w5RAVFJkM/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1hc9h-ahD1-ZwyyYlOHDhaAvT4N2MvFiz/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Temas selectos de Biología II --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-dna"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Temas selectos de Biología II</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes II</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1NDICirCAP7fTJx7VVjWZDi8-zQfPcZmR/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1CyRN8RF9WTulVmIU9Qs4OgYNW_TPN1ps/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOQUE 3: MES III --}}
                <div class="mes-section-divider">
                    <span class="mes-section-badge badge-mes-3">
                        <i class="ti ti-calendar"></i> MES III • Semanas 9 a 12
                    </span>
                    <div class="mes-section-line"></div>
                </div>

                <div class="row g-3">
                    {{-- Contabilidad II --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-calculator"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Contabilidad II</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes III</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1SR2VE31dmzzLFDrcjK1mcrTVdT35B5Yr/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1FAH9xXLWb1UauY5mWar_A7RWPgN7Moyv/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Inglés VI --}}
                    <div class="col-md-6">
                        <div class="card materia-card h-100">
                            <div class="card-body">
                                <div class="materia-header">
                                    <div class="materia-icon-box">
                                        <i class="ti ti-world"></i>
                                    </div>
                                    <div>
                                        <h5 class="materia-title">Inglés VI</h5>
                                        <span class="materia-tag"><i class="ti ti-clock"></i> Asignatura Activa en Mes III</span>
                                    </div>
                                </div>
                                <div class="materia-actions">
                                    <a href="https://drive.google.com/file/d/1OBXFW8SGwwXproO7VeeoZc6ae2qF7UAS/view?usp=drive_link"
                                        target="_blank" class="btn btn-temario-bgne" title="Descargar Temario Oficial BGNE">
                                        <i class="ti ti-brand-google-drive"></i> Temario BGNE
                                    </a>
                                    <a href="https://docs.google.com/document/d/1rZn5ri-LwKqfNNRhso5_xly1cmi4E1Ht/edit?usp=drive_link&ouid=100635062143877049202&rtpof=true&sd=true"
                                        target="_blank" class="btn btn-temario-normal" title="Ver Formato en Google Docs">
                                        <i class="ti ti-file-description"></i> Formato Word
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
