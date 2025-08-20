<div class="modal fade" id="modal_add_client" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.admin') }}" method="POST" autocomplete="off" role="presentation" enctype="multipart/form-data">
                @csrf
                @method('post')
                <input style="display:none">

                <div class="modal-body">
                    <div class="row">

                        <div class="col-12">

                            <div class="form-group">
                                <label for="person_type" class="form-control-label">Tipo de Persona *</label>
                                <select name="person_type" id="person_type" class="form-control" required>
                                    <option value="física" {{ old('person_type') == 'física' ? 'selected' : '' }}>Física</option>
                                    <option value="moral" {{ old('person_type') == 'moral' ? 'selected' : '' }}>Moral</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="rfc" class="form-control-label">RFC</label>
                                <input type="text" name="rfc" class="form-control" maxlength="13" id="rfc" value="{{ old('rfc') }}">
                            </div>

                            <div class="form-group">
                                <label for="name" class="form-control-label">Nombre(s) o Razón Social *</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="form-group" id="last_name_container">
                                <label for="last_name" class="form-control-label">Primer apellido</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}">
                            </div>
                            <div class="form-group" id="second_last_name_container">
                                <label for="second_last_name" class="form-control-label">Segundo apellido</label>
                                <input type="text" name="second_last_name" class="form-control" id="second_last_name" value="{{ old('second_last_name') }}">
                            </div>

                            <div class="form-group" id="denomination_container">
                                <label for="denomination_id" class="form-control-label">Denominación *</label>
                                <select name="denomination_id" id="denomination_id" class="form-control" required>
                                    <option value="">Selecciona una denominación</option>
                                    @foreach($denominations as $denomination)
                                    <option value="{{ $denomination->id }}" {{ old('denomination_id') == $denomination->id ? 'selected' : '' }}>
                                        {{ $denomination->acronym }} ({{ $denomination->denomination }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group" id="legal_representative_container">
                                <label for="legal_representative" class="form-control-label">Representante legal</label>
                                <input type="text" name="legal_representative" class="form-control" id="legal_representative" value="{{ old('legal_representative') }}">
                            </div>

                            <div class="form-group">
                                <label for="phone_number" class="form-control-label">Número Teléfonico </label>
                                <input type="tel" name="phone_number" class="form-control" id="phone_number" value="{{ old('phone_number') }}" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-control-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="country" class="form-control-label">País</label>
                                <select name="country" id="country" class="form-control">
                                    @foreach (Helpers::getCountries() as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="street" class="form-control-label">Calle</label>
                                <input type="text" name="street" class="form-control" id="street" value="{{ old('street') }}">
                            </div>
                            <div class="form-group">
                                <label for="n_exterior" class="form-control-label">No. Exterior </label>
                                <input type="text" name="n_exterior" class="form-control" id="n_exterior"
                                    value="{{ old('n_exterior') }}">
                            </div>
                            <div class="form-group">
                                <label for="suburb" class="form-control-label">Colonia</label>
                                <input type="text" name="suburb" class="form-control" id="suburb"
                                    value="{{ old('suburb') }}">
                            </div>
                            <div class="form-group">
                                <label for="municipality" class="form-control-label">Municipio</label>
                                <input type="text" name="municipality" class="form-control" id="municipality"
                                    value="{{ old('municipality') }}">
                            </div>
                            <div class="form-group">
                                <label for="entity" class="form-control-label">Entidad</label>
                                <input type="text" name="entity" class="form-control" id="entity" value="{{ old('entity') }}">
                            </div>
                            <div class="form-group">
                                <label for="zip_code" class="form-control-label">C.P. </label>
                                <input type="text" name="zip_code" class="form-control" id="zip_code" maxlength="10" value="{{ old('zip_code') }}">
                            </div>
                            <div class="form-group">
                                <label for="observations" class="form-control-label">Observaciones</label>
                                <textarea name="observations" id="observations" class="form-control" rows="4">{{ old('observations') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
