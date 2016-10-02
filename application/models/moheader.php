<?php
/*
Rich 29 Abril 2015
Modelo que permite crear el objeto de cabecera de manera dinámica
$css > Contiene arreglo de css a agregar, aparte de los estándares
$js > Contiene arreglo de js a agregar, aparte de los estándares
*/
class Moheader extends CI_Model
{
	var $assetsPath; //Ruta de la carpeta con librerías
	
	//Librerías CSS predeterminadas
	var $css = array(
		'bower_components/bootstrap/dist/css/bootstrap.min.css',
		'bower_components/metisMenu/dist/metisMenu.min.css',
		'bower_components/font-awesome/css/font-awesome.min.css',
		'bower_components/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css',
		'dist/css/sb-admin-2.css',
		'librerias/estilos.css'
	);
	
	//Librerías JS predeterminadas
	var $js = array(
		'bower_components/jquery/dist/jquery.min.js',
		'bower_components/bootstrap/dist/js/bootstrap.min.js',
		'bower_components/metisMenu/dist/metisMenu.min.js',
		'bower_components/raphael/raphael-min.js',
		'bower_components/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js',
		'dist/js/sb-admin-2.js',
		'librerias/funciones.js',
	);
	
	function __construct() {
		parent::__construct();
		$this->assetsPath = $this->config->item('base_url') . 'r_/';
	}
	
	function init(){ return $this;	}
	
	//Agregar un css
	function addCss($ruta){ $this->css[] = $ruta; }
	
	//Agregar un js
	function addJs($ruta){ $this->js[] = $ruta; }
	
	//Generar cadena de includes CSS
	function include_css()
	{
		$s = '';
		$this->css = array_unique($this->css);
		foreach($this->css as $o) $s .= "<link rel='stylesheet' href='" . $this->assetsPath . $o . "'>\n";
		return $s;
	}
	
	//Generar cadena de includes CSS
	function include_js()
	{
		$s = '';
		$this->js = array_unique($this->js);
		foreach($this->js as $o) $s .= "<script src='" . $this->assetsPath . $o . "'></script>\n";
		return $s;
	}
}