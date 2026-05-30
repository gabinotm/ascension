<form id="solicitudForm" class="form-solicitud">
  <div class="form-header">
    <h2>Formulario de Solicitud de Copias</h2>
  </div>

  <fieldset>
    <legend>Información del Solicitante</legend>
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="text" name="nombre" placeholder="Nombres y apellidos" required>
  </fieldset>

  <fieldset>
    <legend>Detalles del Material</legend>
    <input type="date" name="fecha_entrega" required>
    <input type="text" name="area" placeholder="Área">
    <input type="text" name="aula" placeholder="Aula(s)">
    <input type="text" name="contenido" placeholder="¿Qué se está fotocopiando?">
  </fieldset>

  <fieldset>
    <legend>Especificaciones</legend>
    <input type="number" name="paginas" placeholder="Cantidad de páginas por material">
    <input type="number" name="num_copias" placeholder="Número de copias totales">
    <select name="tipo_impresion">
      <option value="">Tipo de impresión</option>
      <option value="Blanco y negro">Blanco y negro</option>
      <option value="Color">Color</option>
    </select>
  </fieldset>

  <fieldset>
    <legend>Autorización y Notas</legend>
    <input type="text" name="autoriza" placeholder="¿Quién verifica y autoriza?">
    <textarea name="anotaciones" placeholder="Anotaciones adicionales"></textarea>
    <input type="url" name="url_archivo" placeholder="Enlace del archivo en Drive (Link compartido)">
  </fieldset>

  <button type="submit" class="btn-submit">Enviar Solicitud</button>
</form>