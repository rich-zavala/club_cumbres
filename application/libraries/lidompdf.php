<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
require_once APPPATH."third_party/dp/dompdf_config.inc.php";
class Lidompdf extends DOMPDF
{
	public function __construct()
	{
		parent::__construct();
	}
}
?>