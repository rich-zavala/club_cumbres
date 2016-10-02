<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-2">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Ingreso al sistema</h3>
				</div>
				<div class="panel-body">
					<form role="form" class="loginform">
						<fieldset>
							<div class="form-group">
								<input type="text" class="form-control" id="InputUser" name="user" placeholder="Nombre de usuario" autofocus>
							</div>
							<div class="form-group">
								<input type="password" class="form-control" id="InputPassword" name="pass" placeholder="Contraseña">
							</div>
							<div id="login_notificaciones" class="hidden">
								<div id="login_working" class="alert alert-warning hidden">Verificando datos de acceso...</div>
								<div id="login_error" class="alert alert-danger hidden"></div>
							</div>
							<button type="submit" class="btn btn-primary btn-md login btn-block">Ingresar</button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
