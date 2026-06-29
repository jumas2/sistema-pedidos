<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-file-import"></i> Importar Clientes</h4>
        <a href="<?= BASE_URL ?>clientes" class="btn btn-light">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="card-body">
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Formato del archivo:</strong> CSV (Excel)
            <ul class="mb-0 mt-2">
                <li><strong>Columnas requeridas:</strong> nombre, ruc_dni</li>
                <li><strong>Columnas opcionales:</strong> direccion_fiscal, zona, telefono, email</li>
                <li><strong>Ejemplo:</strong></li>
                <pre class="bg-light p-2 mt-1">nombre,ruc_dni,direccion_fiscal,zona,telefono,email
Juan Pérez,12345678901,Av. Principal 123,Miraflores,999999999,juan@email.com
María Gómez,98765432109,Calle Secundaria 456,Surco,888888888,maria@email.com</pre>
            </ul>
        </div>
        
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                <?= $_SESSION['flash']['message'] ?>
                <?php unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>
        
        <form id="formImportar" method="POST" action="<?= BASE_URL ?>clientes/importar" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Archivo CSV *</label>
                        <input type="file" class="form-control" name="archivo" accept=".csv,.txt" required>
                        <small class="text-muted">Máximo 2MB, archivos CSV o TXT</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Separador</label>
                        <select class="form-select" name="separador">
                            <option value=",">Coma ( , )</option>
                            <option value=";">Punto y coma ( ; )</option>
                            <option value="tab">Tabulador</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">¿Tiene encabezados?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_header" value="1" checked>
                            <label class="form-check-label">La primera fila contiene los nombres de las columnas</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-upload"></i> Importar Clientes
                </button>
                <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
        
        <hr>
        
        <!-- Vista previa del archivo -->
        <div id="previewArea" style="display:none;" class="mt-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-eye"></i> Vista previa (primeras 5 líneas)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="previewContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Vista previa del archivo
document.querySelector('input[name="archivo"]').addEventListener('change', function(e) {
    var file = this.files[0];
    if (!file) return;
    
    var previewArea = document.getElementById('previewArea');
    var previewContent = document.getElementById('previewContent');
    
    var reader = new FileReader();
    reader.onload = function(event) {
        var content = event.target.result;
        var lines = content.split('\n').filter(function(line) { return line.trim() !== ''; });
        var maxLines = Math.min(lines.length, 6);
        
        // Determinar separador (intentar con coma primero)
        var separator = ',';
        if (lines[0] && lines[0].includes(';') && !lines[0].includes(',')) {
            separator = ';';
        }
        
        var html = '<table class="table table-bordered table-striped table-sm">';
        
        // Encabezados
        if (lines.length > 0) {
            var headerCells = lines[0].split(separator);
            html += '<thead><tr>';
            headerCells.forEach(function(cell) {
                html += '<th>' + cell.trim() + '</th>';
            });
            html += '</tr></thead>';
        }
        
        // Cuerpo
        html += '<tbody>';
        for (var i = 1; i < maxLines; i++) {
            if (!lines[i]) continue;
            var cells = lines[i].split(separator);
            html += '<tr>';
            cells.forEach(function(cell) {
                html += '<td>' + cell.trim() + '</td>';
            });
            html += '</tr>';
        }
        html += '</tbody></table>';
        
        previewContent.innerHTML = html;
        previewArea.style.display = 'block';
        
        // Mostrar cantidad de líneas
        var info = document.createElement('p');
        info.className = 'text-muted mt-2';
        info.innerHTML = '<i class="fas fa-info-circle"></i> Total de líneas: ' + lines.length + ' (mostrando ' + (maxLines - 1) + ' líneas de datos)';
        previewContent.appendChild(info);
    };
    reader.readAsText(file);
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
