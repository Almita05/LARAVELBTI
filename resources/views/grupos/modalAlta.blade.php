<div class="modal fade" id="modalGrupo" tabindex="-1">
    <div class="modal-dialog">
        <form id="formGrupo" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Nuevo Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label>Clave del grupo<span style="color:red;">*</span></label>
                        <input type="text" name="clave" class="form-control" required> <br>
                        <label>Fecha inicio<span style="color:red;">*</span></label>
                        <input type="date" name="fechaInicio" class="form-control" required> <br>

                        <label>Fecha fin<span style="color:red;">*</span></label>
                        <input type="date" name="fechaFin" class="form-control" required> <br>
                        <label>Centro de trabajo<span style="color:red;">*</span></label>
                        <input type="select" name="fechaFin" class="form-control" required> <br>
                        <label>Plan de estudios<span style="color:red;">*</span></label>
                        <input type="select" name="fechaFin" class="form-control" required><br>
                        <label>Tipo de periodo<span style="color:red;">*</span></label>
                        <input type="select" name="fechaFin" class="form-control" required><br>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar</button>
            </div>

        </form>
    </div>
</div>
<script>
document.addEventListener('submit', function(e) {

    if (e.target.id === 'formGrupo') {

        e.preventDefault();

        let formData = new FormData(e.target);
        let data = Object.fromEntries(formData.entries());

        fetch('/grupos', { // 👈 cambia endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                alert('Grupo guardado');

                let modal = bootstrap.Modal.getInstance(document.getElementById('modalGrupo'));
                modal.hide();

                location.reload();
            });

    }

});
</script>