<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top pain_header" role="navigation" style="margin-bottom: 0">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Mostrar menú de navegación</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<span class="navbar-brand"><span><?=TITULO?></span></span>
		</div>

		
		<ul class="nav navbar-top-links navbar-right">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<b id="pain_username"><?=username()?></b>
					<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu dropdown-user">
					<li><a href="<?=base()?>usuario/perfil<?=suffix()?>"><i class="fa fa-user fa-fw"></i> Perfil de usuario</a></li>
					<li class="divider"></li>
					<li><a href="<?=base()?>acceso/logout<?=suffix()?>"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a></li>
				</ul>
			</li>
		</ul>

		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse" id="side-menu">
				<div id="sideToogle"><a href="#" title="Ocultar/Mostrar menú lateral"><i class="fa fa-bars"></i></a></div>
				<?=$menuLateral?>
		</div>
		</div>
	</nav>
	
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">