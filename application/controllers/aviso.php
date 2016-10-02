<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aviso extends CI_Controller {
	// extends CI_Controller for CI 2.x users
	
	public $data = array();
	public $upload_file_name = 'archivo';
	public $carpeta_archivos = 'r_/archivos_publicos/'; //La ruta de la carpeta donde se guardarán las imágenes
	public $catalogos = array( 'imagenes' => array('gif', 'jpg', 'png'), 'documentos' => array('doc', 'docx', 'pdf') );
	
	function __construct()
	{
		parent::__construct();
		// parent::__construct(); for CI 2.x users
 
		$this->load->helper('url'); //You should autoload this one ;)
		$this->load->helper('form');
		$this->load->helper('ckeditor');
 
		
		//Ckeditor's configuration
		$this->data['ckeditor'] = array(
		
			//ID of the textarea that will be replaced
			'id' 	=> 	'contenido', //<<<< AQUI VA EL ID DEL OBJETO TEXTAREA!!!!
			'path'	=>	'r_/librerias/ckeditor',
		
			//Optionnal values
			'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"650px",	//Setting a custom width
				'height' 	=> 	"450px",	//Setting a custom height,
				'filebrowserImageBrowseUrl' => base() . 'aviso/fileManager/imagenes' . suffix(), //Manejador de imágenes
				'filebrowserBrowseUrl' => base() . 'aviso/fileManager/documentos' . suffix() //Manejador de documentos
			)
		);
	}
	
	public function index()
	{
		if($this->moguardia->isloged())
		{
			$aviso = $this->moaviso->init();
			$torneo_activo = $aviso->torneo_activo();
			//Opciones del formulario
			$form_opciones = array(
				'boton' => array(
					'type' => 'submit',
					'class' => 'btn btn-primary marginTop10',
					'content' => '<i class="fa fa-upload marginRight10"></i> Guardar'
				)
			);
			
			
			$this->data['torneo_activo'] = $torneo_activo;
			$this->data['form_opciones'] = $form_opciones;
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			);
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			
			$this->load->view('layouts/header', $h);
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
			$this->load->view('aviso', $this->data);
			$this->load->view('layouts/footer');
		}
		else show_404();
	}
	
	
	
	public function lista()
	{
		
		if($this->moguardia->isloged())
		{
			//Generamos lista de categorias
			$aviso = $this->moaviso->init();
			$aviso->generar();
			$d['registros_cantidad'] = $aviso->registros;
			if($aviso->registros_cantidad > 0) $d['registros'] = $aviso->registros;
			
			
			$this->moheader->addJs('dist/js/validator.min.js');
			$this->moheader->addJs('librerias/usuario/usuarios_del.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			//Inicia carga de vistas
			
			
			
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
		$this->load->view('file_manager/alistado', $d);
		$this->load->view('layouts/footer');
			
			
			}
	}
	
	//Guardar información
	public function registrar()
	{
		if($this->moguardia->isloged())
		{
			$contenido = $this->input->post('contenido', true);
			$ID_Torneo = $this->db->where('estatus', 1)->get('torneos')->row()->ID_Torneo;
			
			
			if($this->db->insert('avisos', array( 'TextoAviso' => $contenido,'ID_Torneo' => $ID_Torneo ))){
				
				redirect(base() . 'aviso' . suffix());
				
				}
			
			
		}
	}
	
	//Manejador de archivos
	public function fileManager($tipo = 'imagenes')
	{
		if($this->moguardia->isloged())
		{
			//Switch según tipo de archivo seleccionado
			$d['tipo'] = $tipo;
			
			$this->load->helper('directory');
			$d['directorio'] = base() . $this->carpeta_archivos;

			//Indezar archivos
			$d['archivos'] = array();
			$map = directory_map($this->carpeta_archivos, 1);
			foreach($map as $archivo) if(in_array(archivo_extension($archivo), $this->catalogos[$tipo])) $d['archivos'][] = $archivo;

			//Opciones del formulario
			$d['form_opciones'] = array(
				'file' => array(
					'id' => $this->upload_file_name,
					'name' => $this->upload_file_name,
					'required' => true,
					'class' => 'form-control marginBottom10',
					'accept' => '.' . implode(',.', $this->catalogos[$tipo])
				),
				'tipo' => array(
					'type' => 'hidden',
					'value' => $tipo,
					'name' => 'tipo'
				),
				'boton' => array(
					'type' => 'submit',
					'class' => 'btn btn-primary',
					'content' => '<i class="fa fa-upload marginRight10"></i> Subir archivo al servidor'
				)
			);
			
			$this->moheader->addJs('librerias/file_manager.js'); //Agregar librería
			$this->moheader->addCss('librerias/file_manager.css'); //Agregar librería
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			);
			
			$this->load->view('layouts/header', $h);
			$this->load->view('file_manager/listado.php', $d);
			$this->load->view('layouts/footer');
		}
		else show_404();
	}
	
	//Subir archivo
	public function upload()
	{
		if($this->moguardia->isloged())
		{
			$tipo = $this->input->post('tipo', true);
			$extension = archivo_extension($_FILES['archivo']['name']);
			$config['upload_path'] = $this->carpeta_archivos;
			$config['allowed_types'] = implode('|', $this->catalogos[$tipo]);
			$config['max_size']	= '15000';
			$config['file_name'] = sanitize(str_replace('.' . $extension, '', $_FILES['archivo']['name'])) . '.' . $extension;
			
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload($this->upload_file_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('upload_error', $error);
			}
			else
			{
				$this->session->set_flashdata('upload_success', true);
			}
			
			redirect('aviso/fileManager/' . $tipo, 'location');
		}
		else show_404();
	}
	
	//Eliminar archivo
	public function delete()
	{
		if($this->moguardia->isloged())
		{
			$r = array( 'error' => 0 );
			$archivo = $this->input->post('archivo', true);
			if(!unlink($this->carpeta_archivos . $archivo)) $r['error']++;
			
			echo json_encode($r);
		}
		else show_404();
	}
}