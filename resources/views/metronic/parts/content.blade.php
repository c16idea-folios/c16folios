<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	@include("$theme/parts/alerts")
	<div id="alerts-ajax" class="none-empty"></div>
	@yield('content_page')
</div>
<!-- end:: Content -->


<!-- start: Modal change password  -->
<div class="modal fade" id="modal_change_password" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Cambio de Contraseña</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
			</div>


			<form action="{{route('user.admin.update_password')}}" method="POST"  autocomplete="off">
				@csrf
				@method('PUT')

				<div class="modal-body">


					<div>
						<p class="text-dark">Usuario</p>
						<p>{{ auth()->user()->username }}</p>
					</div>
					<div>
						<p class="text-dark">Nombre de usuario</p>
						<p> {{ auth()->user()->name.' '.auth()->user()->last_name.' '.auth()->user()->second_last_name }}</p>
					</div>

					<div class="form-group">
						<label for="password" class="form-control-label">Nueva contraseña *</label>
						<input type="password" name="password" class="form-control" id="password" required>
					</div>


					<div class="form-group">
						<label for="repeat_password" class="form-control-label">Vuelva a escribir la contraseña *</label>
						<input type="password" name="repeat_password" class="form-control" id="repeat_password" required>
					</div>
<br>
<br>
					<div class="text-center text-danger"><strong>La nueva contraseña debera cumplir con los siguientes parametros:
							<br>* Contener al menos una letra mayúscula
							<br>* Contener al menos una letra minúscula
							<br>* Contener al menos un número o carácter especial
							<br>* Una longitud de entre 6-15 caracteres.</strong></div>


							


					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>


						<!-- Botones de cancelar y guardar alineados a la derecha -->
						<div class="ml-auto">
							<button type="submit" class="btn btn-primary">Cambiar contraseña</button>
						</div>
					</div>
			</form>



		</div>
	</div>
</div>
<!-- end: Modal change password  -->

</div>