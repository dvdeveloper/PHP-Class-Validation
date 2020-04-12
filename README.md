# PHP-Class-Validation
Esta clase tiene como referencia: https://github.com/davidecesarano/Validation, la cual permite hacer validaciones más avanzadas en formularios HTML.
Se incluye manejo de idiomas, estilo en los errores e integración con bootstrap 

## Uso
```php 
require_once('DvValidation.php');
```

## Ejemplo
En el repositorio existe un archivo de ejemplo para hacer pruebas (index.php)
```php
 if(!empty($_POST)){
	$obj = new DvValidation($_POST['lan']);
	$nombre 	= $obj->name('nombre')->value($_POST['nombre'])->type('text')->min(2)->required()->return();
    $email 		= $obj->name('email')->value($_POST['email'])->type('email')->required()->return();
    $clave 		= $obj->name('clave')->value($_POST['clave'])->customType('[A-Za-z0-9-.;_!#@]{5,15}')->message("Favor ingrese una clave entre 5 y 15 letras")->required()->return();
    $apellido 	= $obj->name('apellido')->value($_POST['apellido'])->type('text')->min(3)->message("Favor ingrese un apellido con mínimo de 3 letras")->required()->return();
    $numero 	= $obj->name('numero')->value($_POST['numero'])->type('int')->min(5)->max(12)->required()->return();
    $flotante 	= $obj->name('flotante')->value($_POST['flotante'])->type('float')->min(5)->max(12)->required()->return();
    $fecha1 	= $obj->name('fecha1')->value($_POST['fecha1'])->date()->required()->return();
    $fecha2 	= $obj->name('fecha2')->value($_POST['fecha2'])->date("d/m/Y")->required()->return();
    $fecha3 	= $obj->name('fecha3')->value($_POST['fecha3'])->date("d/m/Y")->maxDate("01/01/2020")->required()->return();
    $fecha4 	= $obj->name('fecha4')->value($_POST['fecha4'])->date("d/m/Y")->minDate("30/01/2020")->return();
    $fecha5 	= $obj->name('fecha5')->value($_POST['fecha5'])->date("d/m/Y")->betweenDate("01/01/2020","31/01/2020")->return();
    $file 		= $obj->name('file')->value($_FILES['file'])->file(array('png','jpg','gif'),1)->required()->return();

if($obj->isSuccess()){
    echo 'Todos los campos estan correctos';        
}else{
	echo 'Total errores: '.$obj->countError();
	echo '<br/>';
    echo $obj->displayErrors("alert alert-danger");
}
```

## Multi-idioma
Esta clase se construyó pensando en el manejo de multi-idioma, lo cual puedes configurar al momento de instanciar la clase. Valor por defecto: en
```php
$obj = new DvValidation("es");
```

## Mensajes
Puedes cambiar los mensajes modificando el siguiente código en el archivo DvValidation.php
```php
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
```

## Ejemplo HTML con bootstrap 4
Se agregó la funcionalidad que permite visualizar los errores usando las clases de bootstrap, más información se puede ver en: https://getbootstrap.com/docs/4.4/components/forms/#server-side
```html
     <div class="form-group">
	    <label for="">Nombre</label>
	    <input type="text" name="nombre" value="<?php echo $nombre['value'] ?>" class="form-control <?php echo $nombre['input_class'] ?>">
	    <?php echo $nombre['error_html'] ?>
	</div>
	<div class="form-group">
	    <label for="">Apellido</label>
	    <input type="text" name="apellido" value="<?php echo $apellido['value'] ?>" class="form-control <?php echo $apellido['input_class'] ?>">
	    <?php echo $apellido['error_html'] ?>
	</div>
	<div class="form-group">
	    <label for="">Número mínimo: 5 y máximo: 12</label>
	    <input type="text" name="numero" value="<?php echo $numero['value'] ?>" class="form-control <?php echo $numero['input_class'] ?>">
	    <?php echo $numero['error_html'] ?>
	</div>
```

## Funciones

| función          | Parámetro | Descripción                                                                 | Ejemplo                           |
|-----------------|-----------|-----------------------------------------------------------------------------|-----------------------------------|
| name            | $name     | Retorna el atributo name de un input                                                         | name('nombre')                      |
| value           | $value    | Retorna el valor (value) de un input                                                          | value($_POST['nombre])              |
| file            | $array,$size    | Retorna $_FILES array. Recibe como parámetros opcional un array con las extensiones a validar y el tamaño máximo del archivo en MB | file(array('png','jpg',2))             |
| type         | $name  | Permite validar un input según patrones pre-establecidos en la variable $patterns (uri,url,int,float,tel,email)        | type('text')                   |
| customType   | $pattern  | Permite validar un input usando expresiones regulares | customType('[A-Za-z]')         |
| required        |           | Valida si un atributo es obligotorio                                     | required()                        |
| min             | $length   | Valida si el contenido es mayor que:                 | min(10)                           |
| max             | $length   | Valida si el contenido es menor que:                   | max(10)                           |
| minDate           | $length    | Validar si una fecha es mayor que:                  | minDate("01/01/2020")                     |
| maxDate         | $length    | Validar si una fecha es menor que         | maxDate("01/01/2020")                  |
| betweenDate             | $min,$max    | Valida si una fecha se encuentre entre fechas pre-establecidas             | betweenDate("01/01/2020","31/01/2020")          |
| countError       |           | Retorna la cantidad de errores vigentes                                          | countError()                       |
| isSuccess       |           | Retorna un bool que identifica si hay errores vigentes                                        | isSuccess()                       |
| getInputs       |           | Retorna una array con las caracteristicas de los inputs                                | getInputs()                       |
| displayErrors   | $class         | Retorna los errores en HTML, se puede añadir una clase CSS para customizar el error                      | displayErrors($class)                   |