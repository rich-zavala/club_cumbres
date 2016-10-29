<?php
/*
Este modelo pretende generar el menú lateral del sitio.
Considera permisos y perfiles
*/
class Momenu extends CI_Model
{
	/*
	Catálogo de opciones predeterminadas
	0 > Etiqueta
	1 > Icono (fa-)
	2 > URL de destino
	3 > Subniveles (Opcional)
	4 > Activo (Opcional)
	*/
	var $opciones = array();
	
	function __construct() {
		parent::__construct();
		$this->generar();
	}
	
	function init(){ return $this;	}
	
	//Crear un menú bajo solicitud
	function generar($opciones = array())
	{
		if(count($opciones) == 0) //Si no hay opciones generará el menú predeterminado
		{
			$opciones = array(
				array('Torneos', 'sitemap', base() . 'torneos/listado' . suffix()),
				array('Avisos', 'comments','',array(array('Publicar', 'microphone',  base() . 'aviso' . suffix()),
				array('Gesti&oacute;n', 'th-list',  base() . 'aviso/lista' . suffix()))),
				
				array('Encuestas', 'tags', base() . 'encuestas' . suffix(),
					array(
						array('Encuesta', 'tag', base() . 'encuestas/lista' . suffix()),
						array('Encuestas árbitros', 'tag', base() . 'encuestas/arbitros' . suffix()),
						array('Encuestas jugadores', 'tag', base() . 'encuestas/jugadores' . suffix()),
					)
				),
				
				array('Publicidad', 'copy', base() . 'publicidad/lista' . suffix()),
				array('Administrar', 'gear', '#',
					array(
						array('Usuarios', 'user', base() . 'usuario/lista' . suffix()),
						array('Categorías', 'th-list', base() . 'categoria/lista' . suffix()),
						array('Árbitros', 'male', base() . 'arbitros/listado' . suffix()),
					)
				)
			);
		}
		
		$this->opciones = $opciones;
	}
	
	//Activar opción
	//Recibe índices del arreglo del menú
	function activar($k)
	{
		$k = explode(',', $k);
		if(count($k) > 0)
		{
			$e = '';
			$anterior = 0;
			foreach($k as $k_ => $v)
			{
				$e = "[{$v}]";
				if($k_ == 0)
					$ev = '$this->opciones' . $e . '[4] = "true";';
				else
					$ev = '$this->opciones[' . $anterior . '][3]' . $e . '[4] = "true";';
				$anterior = $v;
				// echo $ev . "<br>";
				eval($ev);
			}
		}
	}
	
	//Etiqueta de nivel
	function navLevel($n)
	{
		$n++;
		switch($n)
		{
			case 1: return '';
			case 2: return 'nav-second-level';
			case 3: return 'nav-third-level';
		}
	}
	
	//Generar elementos UL, LI del menú recursivamente
	function menuLateralUl($opciones, $nivel = 0)
	{
		$ul = "<ul class='nav {$this->navLevel($nivel)}'>";
		foreach($opciones as $opcion)
		{
			$conOpciones = false;
			$active = $caret = '';
			if(isset($opcion[4]) and $opcion[4] == true) $active = 'active';
			if(isset($opcion[3]) and is_array($opcion[3]) and count($opcion[3]) > 0)
			{
				$conOpciones = true;
				$caret = '<span class="fa arrow"></span>';
			}
			
			$ul .= "\n<li class='{$active}'><a href='{$opcion[2]}'><i class='fa fa-fw fa-{$opcion[1]}'></i> {$opcion[0]}{$caret}</a>";
			if($conOpciones) $ul .= $this->menuLateralUl($opcion[3], $nivel + 1);
			$ul .= "</li>\n";
		}
		$ul .= "</ul>";
		return $ul;
	}
	
	//Generar menú lateral
	function menuLateralGenerar()
	{
		return $this->menuLateralUl($this->opciones);
	}
}