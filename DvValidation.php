<?php
/**
 * PHP Class DvValidation 
 *
 * Esta clase tiene como referencia: https://github.com/davidecesarano/Validation, la cual permite hacer
 * validaciones más avanzadas en formularios HTML.
 * Se incluye manejo de idiomas, estilo en los errores e integración con bootstrap 
 *
 * @author Diego Valladares Q.
 * @copyright (c) 2020, Diego Valladares Q.
 * @link https://github.com/dvdeveloper/PHP-Class-Validation
 * @version 1.0.0
 */

class DvValidation {

	private $errors = array();
	private $language;
	private $idioma = array('en' => 1,'es' => 1);
	private $input_class;
	private $error_class;
	private $type = null;
	private $format = null;

     /**
     * Array con expresiones regulares que permiten validar los input del formulario
     * @var array $patterns
     * @access private
     */
	private $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]+'
    );


    /**
    * Constructor de la clase que setea valores básicos no obligatorios
    * Integración con Bootstrap en diseño y HTML, link referencia: https://getbootstrap.com/docs/4.4/components/forms/#server-side
    *
    * @param mixed $lang idioma  (opcional), por defecto: en
    * @param string $input_class (opcional), setea una clase CSS para el input html. Por defecto: is-invalid
    * @param string $error_class (opcional), setea una clase CSS para mostrar el error del input. Por defecto: invalid-feedback
    * @access public
    */
    public function __construct($lang = null,$input_class = null,$error_class = null) {
    	$this->language = isset($this->idioma[$lang]) ? $lang : "en" ;
    	$this->input_class = is_null($input_class) ? "is-invalid" : $input_class;
    	$this->error_class = is_null($error_class) ? "invalid-feedback" : $error_class;
    }


    /**
    * Función que traduce los mensajes de error según idioma seteado al instanciar la clase
    *
    * @param string $input  (obligatorio). Valor que permite setear el mensaje a mostrar
    * @param string $custom (opcional). Información adicional del mensaje
    * @access private
    */
    private function lang_error($input,$custom = null){
    	
    	$this->idioma['en'] = array(
    		'valid_format' => 'Please enter a valid format',
            'valid_format_param' => 'Please enter a valid format ' .$custom,
    		'required' => 'This field is required',
    		'least_string' => 'Please enter at least '.$custom.' characters.',
    		'least' => 'Please enter a value greater than  '.$custom,
    		'more_string' => 'Please enter no more than '.$custom.' characters.',
    		'more' => 'Please enter a value less than '.$custom,
    		'date' => 'Format date is invalid: '.$custom,
            'betweenDate' => 'Please enter a value between : '. $custom,
            'valid_extension' => 'Invalid file format .'.$custom,
            'size_file' => 'The file cannot be greater than '.$custom.'MB'
    	);

    	$this->idioma['es'] = array(
    		'valid_format' => 'Favor ingrese un formato válido',
            'valid_format_param' => 'Favor ingrese un formato válido ' .$custom,
    		'required' => 'Este campo es requerido',
    		'least_string' => 'Favor ingrese al menos '.$custom.' caracteres.',
    		'least' => 'Valor ingresado debe ser mayor o igual que '.$custom,
    		'more_string' => 'Favor no ingrese mas de '.$custom.' caracteres.',
    		'more' => 'Valor ingresado debe ser menor o igual que '.$custom,
    		'date' => 'Formato de fecha invalido, seguir el formato: '.$custom,
            'betweenDate' => 'fecha debe estar entre: '. $custom,
            'valid_extension' => 'Formato de archivo no valido .'.$custom,
            'size_file' => 'El archivo no puede ser mayor que '.$custom.'MB'
    	);
	    return $this->idioma[$this->language][$input];
    }

    /**
    * Función que permite validar si una fecha ingresada es valida.
    *
    * @access private
    */
    private function checkIsDate(){
        $d = DateTime::createFromFormat($this->format, $this->value);
        if(is_object($d) && $d->format($this->format) === $this->value){
            return date_format($d, $this->format);
        }
        return false;
    }

    /**
    * Función que permite setear los parámetros básicos de errores usado en la función return
    *
    * @param string $input_error    (opcional). Valor que permite buscar en la función lang_error y obtiene el mensaje a mostrar al usuario
    * @param string $param          (opcional). Información adicional del mensaje
    * @access private
    */
    private function setError($input_error = 'valid_format',$param = null){
        $this->errors[$this->name]['error'] = true;
        $this->errors[$this->name]['message'] = $this->lang_error($input_error,$param);
        $this->errors[$this->name]['value'] = $this->value ;
    }

	/**
    * Función que permite setear el name input del HTML, ejemplo: <input type="text" name="name"/>
    *
    * @param string $name, se debe ingresar el valor del atributo name de un input
    * @access public
    */
	public function name($name){ 

        $this->name = $name;
        $this->errors[$this->name]['error'] = false;
        $this->errors[$this->name]['message'] = null;
        $this->errors[$this->name]['input_class'] = null;
        $this->errors[$this->name]['error_html'] = null;       

        return $this;
    }

    /**
    * Función que permite setear el valor del input del HTML, ejemplo: <input type="text" value="Mi Nombre"/>
    *
    * @param string $value, se debe ingresar el valor del input
    * @access public
    */
    public function value($value){
        $this->value = $value;
        $this->errors[$this->name]['value'] = $this->value ;
        return $this;
    }


    /**
    * Función que permite validar una fecha en el input HTML
    *
    * @param format $date, formato de la fecha a validar: link format: https://www.php.net/manual/es/function.date.php
    * @access public
    */
    public function date($format = "d-m-Y"){
    	$this->type = 'date';
        $this->format = $format;
        
        if(strlen($this->value) > 0 ){
            $newDate = $this->checkIsDate();
            if($newDate){
                $this->value = $newDate;
                $this->errors[$this->name]['value'] = $this->value;
            }else{
                $this->setError('date',$format);
            }
        }
    	return $this;
    }

    /**
    * Función que permite configurar el tipo de atributo a validar, ejemplo: text,int,float.
    *
    * @param string $type debe coincider con la llave (key) del atributo $patterns declarado al inicio de la clase
    * @access public
    */
    public function type($name){
    	$this->type = $name;
    	if(isset($this->patterns[$name])){
	        $regex = '/^('.$this->patterns[$name].')$/u';
	        if($this->value != '' && !preg_match($regex, $this->value)){
                $this->setError('valid_format');
	        }
	        
	    }
        return $this;
    }

    /**
    * Función que permite validar el valor de un input con expresiones regulares.
    *
    * @param string $pattern Expresión Regular, ejemplo: [A-Za-z0-9-.;_!#@]{5,15}
    * @access public
    */
    public function customType($pattern){
        $this->type = 'text';
        $regex = '/^('.$pattern.')$/u';
        if($this->value != '' && !preg_match($regex, $this->value)){
            $this->setError('valid_format');
        }
        return $this;
    }

    /**
    * Función que valida si un campo es obligatorio
    *
    * @access public
    */
    public function required(){ 
        if( ($this->type == 'file' && $this->value['error'] == 4) ||  ($this->value == '' || $this->value == null) ){
            $this->setError('required');
        }            
        return $this;
    }

    /**
    * Función que permite setear un mensaje personalizado de error.
    *
    * @param string $message Mensaje personalizado
    * @access public
    */
    public function message($message){ 
        if( isset($this->errors[$this->name]) && $this->errors[$this->name]['error'] ){
            $this->setError('required');
            $this->errors[$this->name]['message'] = $message;
        }            
        return $this;
    }

    /**
    * Retorna un array de información con algún input a validar
    * ejemplo: $obj->name('nombre')->value('x')->type('text')->min(2)->required()->return()
    *
    * @access public
    */
    public function return(){ 

        if( isset($this->errors[$this->name]) && $this->errors[$this->name]['error'] ){
        	$this->errors[$this->name]['input_class'] = $this->input_class;
        	$this->errors[$this->name]['error_html'] = '<div class="'.$this->error_class.'">'.$this->errors[$this->name]['message'].'</div>';
            return $this->errors[$this->name];
        } 
        return $this->errors[$this->name];
    }

    /**
    * Función que valida si un parámetro esta dentro de un mínimo establecido
    * si el valor es de tipo string, valida la cantidad de caracteres
    * si el valor es de tipo int, valida un valor mínimo
    *
    * ejemplo: $obj->name('nombre')->value('x')->type('text')->min(2)
    *
    * @param int $length valor minimo de un input
    * @access public
    */
    public function min($length){

    	$this->value = ($this->type == "int") ? (int)$this->value : $this->value; //validamos si el input es tipo numerico
    	$this->value = ($this->type == "float") ? (float)$this->value : $this->value; //validamos si el input es tipo float
        
        if(is_string($this->value)){
            if(strlen($this->value) < $length){
                $this->setError('least_string',$length);
            }
       
        }else{
            if($this->value < $length){
                $this->setError('least',$length);
            }
        }
        return $this;
    }

    /**
    * Función que valida si la fecha ingresada esta dentro del mínimo establecido
    * ejemplo: $obj->name('fecha')->value("30/01/2020")->date("d/m/Y")->minDate("30/01/2020")->return();
    *
    * @param date $length fecha minima. El valor ingresado debe considerar el formato de la fecha, ejemplo: d/m/Y
    * @access public
    */
    public function minDate($length){
        if($this->type == "date" && strlen($this->value) > 0 ){
            $date = $this->checkIsDate();
            if($date){
                $dateCompa  = DateTime::createFromFormat($this->format, $length);
                $dateCompa  = $dateCompa->format($this->format);
                if($date < $dateCompa){
                    $this->setError('least',$length);
                }
            }
        }
        return $this;
    }
    

    /**
    * Función que valida si un parámetro esta dentro de un máximo establecido
    * si el valor es de tipo string, valida la cantidad de caracteres
    * si el valor es de tipo int, valida un valor máximo
    *
    * ejemplo: $obj->name('nombre')->value('x')->type('text')->max(20)
    *
    * @param int $length valor máximo de un input
    * @access public
    */        
    public function max($length){
    	$this->value = ($this->type == "int") ? (int)$this->value : $this->value; //validamos si el input es tipo numerico
    	$this->value = ($this->type == "float") ? (float)$this->value : $this->value; //validamos si el input es tipo float

        if(is_string($this->value)){
            if(strlen($this->value) > $length){
                $this->setError('more_string',$length);
            }
        }else{
            if($this->value > $length){
                $this->setError('more',$length);
            }
        }
        return $this;
    }

    /**
    * Función que valida si la fecha ingresada esta dentro del máximo establecido
    * ejemplo: $obj->name('fecha')->value("30/01/2020")->date("d/m/Y")->maxDate("30/01/2020")->return();
    *
    * @param date $length fecha máxima. El valor ingresado debe considerar el formato de la fecha, ejemplo: d/m/Y
    * @access public
    */
    public function maxDate($length){
        if($this->type == "date"){
            $date = $this->checkIsDate();
            if($date){
                $dateCompa  = DateTime::createFromFormat($this->format, $length);
                $dateCompa  = $dateCompa->format($this->format);
                if($date > $dateCompa){
                    $this->setError('more',$length);
                }
            }
        }
        return $this;
    }

    /**
    * Función que valida si la fecha ingresada se encuentre entre fechas
    * ejemplo: $obj->name('fecha')->value("05/01/2020")->date("d/m/Y")->betweenDate("01/01/2020","31/01/2020")->return();
    *
    * @param date $min fecha mínima. El valor ingresado debe considerar el formato de la fecha, ejemplo: d/m/Y
    * @param date $max fecha máxima. El valor ingresado debe considerar el formato de la fecha, ejemplo: d/m/Y
    * @access public
    */
    public function betweenDate($min,$max){
        if(strlen($this->value) > 0 ){
            if($this->type == "date"){
                $NewMin  = DateTime::createFromFormat($this->format, $min);
                $NewMax  = DateTime::createFromFormat($this->format, $max);
                $NewValue = DateTime::createFromFormat($this->format, $this->value);

                if( $this->checkIsDate() && ($NewMin && $NewMin->format($this->format) === $min) && ($NewMax && $NewMax->format($this->format) === $max)  ){
                    
                    $min  = $NewMin->format("Y-m-d");
                    $max  = $NewMax->format("Y-m-d");
                    $value = $NewValue->format("Y-m-d");

                    if ( ($value >= $min) && ($value <= $max) ){
                        //date ok
                    }else{
                        $this->setError('betweenDate',($min.' - '.$max));
                    }
                }else{
                    $this->setError('valid_format_param',($min.' - '.$max));
                }
            }
        }
        return $this;
    }

    /**
    * Función que valida un input file
    * ejemplo: $obj->name('file')->value($_FILES['file'])->file()->required()->return();
    *
    * @param array $extensions (opcional) recibe un arreglo con las extensiones permitidas, ej: array("png","jpg","gif")
    * @param int $size (opcional) tamaño del archivo en MB
    * @access public
    */
    public function file($extensions = null,$size = null){
        $this->type = 'file';
        if(!empty($this->value['tmp_name'])){
            //validación de extensión
            $ext = pathinfo($this->value['name'], PATHINFO_EXTENSION);
            $fileSize = ($this->value['size'])/(1024*1024); //escala en MB
            
            if(is_array($extensions) && !in_array($ext, $extensions)){
                $this->setError('valid_extension',$ext);
            }else if(is_numeric($size) && $fileSize > $size){
                $this->setError('size_file',$size);
            }
        }
        return $this;
    }

    /**
    * Función que permite contar la cantidad de errores vigentes
    *
    * @access public
    */
    public function countError(){
        $count = 0;
        foreach($this->errors as $key => $value){
        	if($value['error']){
        		$count++;
        	}
        }
        return $count;
    }

    /**
    * Función que permite identificar si existen errores vigentes
    * return bool, true = sin errores, false =  errores vigentes
    *
    * @access public
    */
    public function isSuccess(){
        $count = 0;
        foreach($this->errors as $key => $value){
        	if($value['error']){
        		$count++;
        	}
        }
        return ($count == 0) ? true : false;
    }

    /**
    * Función que retorna un arreglo con los inputs vigente describiendo:
    * error: true = existe errores en el input, false: sin errores
    * message: mensaje del errores
    * input_class: input CSS
    * error_html: retorna el error en HTML
    * value: valor del input
    *
    * @access public
    */
    public function getInputs(){
        if(!$this->isSuccess()){
        	return $this->errors;
        }
        return null;
    }

    /**
    * Función que permiti imprimir en HTML todos los errores 
    *
    * @param string $class (opcional) clase CSS que permite darle un estilo personalizado al HTML a imprimir
    * @access public
    */
    public function displayErrors($class = null){
    	
    	$class = (is_null($class)) ? "" : "class='".$class."'";
        $html = "<ul ".$class.">";
            foreach($this->errors as $key => $value){
            	if($value['error']){
            		$html .= "<li><b>".ucfirst($key)."</b> : ".$value['message']."</li>";
            	}
            }
        $html .= "</ul>";
        return $html;
    }
}