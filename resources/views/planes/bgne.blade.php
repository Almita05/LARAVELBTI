@extends('layouts.app')

@section('content')

<style>
body {
    background: linear-gradient(135deg, #eef2f7, #f8fafc);
}

.nav-tabs {
    border-bottom: none;
    justify-content: center;
}

.nav-tabs .nav-link {
    border: none;
    color: #046404;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 12px;
    margin: 5px;
    background: #e9f5ec;
    transition: 0.3s;
}

.nav-tabs .nav-link.active {
    background: #046404;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.card-materia {
    border: none;
    border-radius: 18px;
    padding: 20px;
    background: white;
    transition: 0.3s;
    position: relative;
}

.card-materia:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

.card-materia::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
}

.card-materia h5 {
    font-weight: 700;
    color: #046404;
}

.btn-temario {
    background: linear-gradient(135deg, #87CEEB, #00bcd4);
    border: none;
    border-radius: 10px;
    padding: 8px 12px;
    font-weight: 600;
    margin-top: 8px;
    display: inline-block;
}

.row-materias {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
</style>

<div class="container py-4">


    <h3 class="text-center mb-4 fw-bold">Planes de Estudio BGNE</h3>

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
                    <h5>Matemáticas I</h5><a
                        href="https://drive.google.com/file/d/1x-ZtNJvC3ZX8LRRWct7UBIKbPCpCWhPj/view" target="_blank"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1X9pwTAZkRlIZSjGm2tHAayoPwXjOT4Kx/edit" target="_blank"
                        class="btn btn-temario">Normal</a>
                </div>
                <div class="card-materia">
                    <h5>Química I</h5><a href="https://drive.google.com/file/d/1-OYOIwULmWGMQmMcbdAc6pD5Ih9TEzz4/view"
                        target="_blank" class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1td6EuJJA-SM9Rf6mmv_caldlub9JPljn/edit" target="_blank"
                        class="btn btn-temario">Normal</a>
                </div>
                <div class="card-materia">
                    <h5>Geografía</h5><a href="https://drive.google.com/file/d/1QKqi7B-x-hfhZ1X2Baef8V-elYOWAJst/view"
                        target="_blank" class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1jroJFDA0kEaaECr8Ybb8H8ro7-csh1cw/edit" target="_blank"
                        class="btn btn-temario">Normal</a>
                </div>
                <div class="card-materia">
                    <h5>Introducción a Ciencias Sociales I</h5><a
                        href="https://drive.google.com/file/d/1T7u40K-csXYjtClL1Vm_BOSX4AX4EtSc/view" target="_blank"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1xgD09AF8UNFhxX4eGmdBZpF05YoyrRcQ/edit" target="_blank"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

        <!-- 2 -->
        <div class="tab-pane fade" id="tab2">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Matemáticas II</h5><a
                        href="https://drive.google.com/file/d/1zSmUTKaSGaBXt-5Ye1XzLILOs44kVyUJ/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1plzjHY8Dccw4U72sER-SJcGps2StLAj8/edit"
                        class="btn btn-temario">Normal</a>
                </div>
                <div class="card-materia">
                    <h5>Química II</h5><a href="https://drive.google.com/file/d/1_rGi6RsDeHpBYnF26eTe19gptiEIEOGO/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1k07Q23QG6SSoo0ovIEuMe8PTMXrXhWBP/edit"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

        <!-- 3 -->
        <div class="tab-pane fade" id="tab3">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Matemáticas III</h5><a
                        href="https://drive.google.com/file/d/1BW9ykzuCL09m2SXCiNBazmyL2f5K-S-X/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1ggdqgctiFSuiwqDsnNAc_meM63WUOtcj/edit"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

        <!-- 4 -->
        <div class="tab-pane fade" id="tab4">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Matemáticas IV</h5><a
                        href="https://drive.google.com/file/d/1gxj751KV8JifVklwJW0WJ-QyMCBSU-9i/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1SKLbZUvwOKNcIR1cYYeMJN_kxGQk38Or/edit"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

        <!-- 5 -->
        <div class="tab-pane fade" id="tab5">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Cálculo Diferencial</h5><a
                        href="https://drive.google.com/file/d/1g3p3I1X_oEm8cxAqBJQm02P7C1EBENzX/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/11Pi9u08Z-JHqUpSibcCfiRGjabCFXOkV/edit"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

        <!-- 6 -->
        <div class="tab-pane fade" id="tab6">
            <div class="row-materias">
                <div class="card-materia">
                    <h5>Cálculo Integral</h5><a
                        href="https://drive.google.com/file/d/1v93DDUstoX58ITg0s6GBPr5w5RAVFJkM/view"
                        class="btn btn-temario">BGNE</a><a
                        href="https://docs.google.com/document/d/1hc9h-ahD1-ZwyyYlOHDhaAvT4N2MvFiz/edit"
                        class="btn btn-temario">Normal</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection