<script type="text/html" id="tplDtColumnBtnEdit">
    {{#.}}
    <a data-toggle="tooltip" title="Editar" class="btn btn-icon btn-primary btnEdit" href="javascript:;" data-row-id="{{row_id}}" >
        <i class="fe fe-edit"></i>
    </a>
    <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a>
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnUsers">
    {{#.}}
    <a data-toggle="tooltip" title="Editar" class="btn btn-icon btn-primary btnEdit" href="javascript:;" data-row-id="{{row_id}}" >
        <i class="fe fe-edit"></i>
    </a>
    <a data-toggle="tooltip" title="Accesos" class="btn btn-icon  btn-info" href="<?= $uri["create_access"] ?>?userID={{row_id}}">
        <i class="fa fa-list-ul"></i>
    </a>
    <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a>
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnJornada">
    {{#.}}
    <a data-toggle="tooltip" title="Jornadas" class="btn btn-icon  btn-primary" href="<?= $uri["jornadas"] ?>?num={{row_id}}">
        <i class="fa fa-briefcase"></i>
    </a>
    <!-- a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a -->
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnCompra">
    {{#.}}
    <a data-toggle="tooltip" title="Compras" class="btn btn-icon  btn-primary" href="<?= $uri["compras"] ?>?num={{row_id}}">
        <i class="fa fa-shopping-bag"></i>
    </a>
    <!-- a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a -->
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnJornadaPersonal">
    {{#.}}
    <a data-toggle="tooltip" title="Editar" class="btn btn-icon btn-primary btnEdit" href="javascript:;" data-row-id="{{row_id}}" >
        <i class="fe fe-edit"></i>
    </a>
    <a data-toggle="tooltip" title="Asistencia" class="btn btn-icon  btn-warning btnAsist" href="<?= base_url() ?>jornadas/asistencia?astID={{row_id}}">
        <i class="fe fe-book"></i>
    </a>
    <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a>
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnDetalleCompra">
    {{#.}}
    <a data-toggle="tooltip" title="Ver Compra" class="btn btn-icon  btn-primary btnEdit" href="<?= base_url() ?>compras/detalle_compra_create?shopID={{row_id}}&siteID=<?= $obra_id ?>">
        <i class="fe fe-eye"></i>
    </a>
    <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a>
    {{/.}}
    {{> partialDetail }}
</script>

<script type="text/html" id="tplDtColumnAsistencia">
    {{#.}}
    <a data-toggle="tooltip" title="Asistencia" class="btn btn-icon  btn-light btnAsist" href="javascript:;" data-row-id="{{row_id}}">
        <i class="fa fa-calendar"></i>
    </a>
    <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="jj.actionRem('{{uri_remove}}', '{{row_id}}', '{{callback_rem}}')"  href="ajavscript:;">
        <i class="fe fe-trash"></i>
    </a>
    {{/.}}
    {{> partialDetail }}
</script>

<script id="productTemplate" type="text/html">
    <div class="row" style="margin-bottom: 1em;">
        <!-- input type="hidden" class="form-control producto-id" name="items[detalle_compra_id][]" -->
        <div class="col-md-4">
            <label class="form-label">Producto <span class="text-red">*</span> </label>
            <select required class="form-control select-producto" name="items[producto_id][]">
                <option value="" disabled selected hidden>-- Buscar producto --</option>
                {{#productos}}
                <option value="{{id}}">{{text}}</option>
                {{/productos}}
            </select>
        </div>
        <div class="col-md-1">
            <label class="form-label" >UM </label>
            <input type="text" class="form-control producto-undMedida" name="items[unidad_medida][]" placeholder="UM" readonly>
        </div>
        <div class="col-md-2">
            <label class="form-label" >Cant </label>
            <div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-light border-0 br-0 minus">
                        <i class="fa fa-minus"></i>
                    </button>
                </span>
                <input type="text" class="form-control text-center qty" name="items[cantidad][]" value="1">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-light border-0 br-0 add" >
                        <i class="fa fa-plus"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label" >Prec. </label>
            <input type="text" class="form-control text-center producto-precUnitario" name="items[precio_unitario][]" value="0.00" readonly>
        </div>
        <div class="col-md-1">
            <label class="form-label" >SubTot </label>
            <input type="text" class="form-control text-center subtotal" name="items[subtotal][]" readonly>
        </div>
        <div class="col-md-1">
            <label class="form-label" >IGV </label>
            <input type="text" class="form-control text-center igv" name="items[igv][]" readonly>
        </div>
        <div class="col-md-1">
            <label class="form-label" >Total </label>
            <input type="text" class="form-control text-center total" name="items[total][]" readonly>
        </div>
        <div class="col-md-1">
            <label class="form-label" >&nbsp;</label>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon btn-danger" onclick="deleteProducto(this)"><i class="fe fe-trash"></i></a>
        </div>
    </div>
</script>

<script id="personTemplate" type="text/html">
    <div class="row" style="margin-bottom: 1em;">
        <!-- input type="hidden" class="form-control personal-id" name="items[detalle_jornada_id][]" -->
        <div class="row" style="margin-bottom: 1em;">
            <div class="col-md-5"> 
            <label class="form-label">Personal </label> 
                <select required class="form-control select-personal" name="items[personal_id][]">
                    <option value="" disabled selected hidden>-- Buscar personal --</option>
                    {{#personales}}
                    <option value="{{id}}">{{text}}</option>
                    {{/personales}}
                </select>
            </div> 
            <div class="col-md-3">
                <label class="form-label" >Especialidad </label>
                <input type="text" class="form-control personal-especialidad" name="items[especialidad_name][]" placeholder="Especialidad" readonly>
            </div> 
            <div class="col-md-3"> 
                <label class="form-label" >Area </label>
                <input type="text" class="form-control personal-area" name="items[area_name][]" placeholder="Area" readonly> 
            </div>
            <div class="col-md-1"> 
                <label class="form-label" >Acc. </label>
                <a data-toggle="tooltip" title="Eliminar" class="btn btn-icon  btn-danger" onclick="deletePersonal(this)"><i class="fe fe-trash"></i></a>
            </div>
        </div>
    </div>
</script>