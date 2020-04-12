<?php
	require 'DvValidation.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Validación de campos | DvDeveloper</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<style type="text/css">
		ul {
		  list-style-type: none;
		 }
	</style>
</head>
<body>
	<div class="container">
		<br/>
		<h1>Ejemplo</h1>
		<h4>Autor: Diego Valladares Q.</h4>
		<br/><br/>
		<?php

			$email 		= null;
			$clave 		= null;
			$nombre 	= null;
			$apellido 	= null;
			$numero 	= null;
			$flotante 	= null;
			$fecha1 	= null;
			$fecha2 	= null;
			$fecha3 	= null;
			$fecha4 	= null;
			$fecha5 	= null;
			$file 		= null;

		    if(!empty($_POST)){

		    	$val = new DvValidation($_POST['lan']);
		    	$nombre 	= $val->name('nombre')->value($_POST['nombre'])->type('text')->min(2)->required()->return();
			    $email 		= $val->name('email')->value($_POST['email'])->type('email')->required()->return();
			    $clave 		= $val->name('clave')->value($_POST['clave'])->customType('[A-Za-z0-9-.;_!#@]{5,15}')->message("Favor ingrese una clave entre 5 y 15 letras")->required()->return();
			    $apellido 	= $val->name('apellido')->value($_POST['apellido'])->type('text')->min(3)->message("Favor ingrese un apellido con mínimo de 3 letras")->required()->return();
			    $numero 	= $val->name('numero')->value($_POST['numero'])->type('int')->min(5)->max(12)->required()->return();
			    $flotante 	= $val->name('flotante')->value($_POST['flotante'])->type('float')->min(5)->max(12)->required()->return();
			    $fecha1 	= $val->name('fecha1')->value($_POST['fecha1'])->date()->required()->return();
			    $fecha2 	= $val->name('fecha2')->value($_POST['fecha2'])->date("d/m/Y")->required()->return();
			    $fecha3 	= $val->name('fecha3')->value($_POST['fecha3'])->date("d/m/Y")->maxDate("01/01/2020")->required()->return();
			    $fecha4 	= $val->name('fecha4')->value($_POST['fecha4'])->date("d/m/Y")->minDate("30/01/2020")->return();
			    $fecha5 	= $val->name('fecha5')->value($_POST['fecha5'])->date("d/m/Y")->betweenDate("01/01/2020","31/01/2020")->return();
			    $file 		= $val->name('file')->value($_FILES['file'])->file()->required()->return(); //array('png','jpg','gif'),1

		    if($val->isSuccess()){
		        echo 'Todos los campos estan correctos';        
		    }else{
		    	echo 'Total errores: '.$val->countError();
		    	echo '<br/>';
		        echo $val->displayErrors("alert alert-danger");
		        echo '<br/><br/>';
		        echo json_encode($val->getInputs(),true);
		        echo '<br/><br/>';
		    }

		  }
		?>
		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
			    <label for="">Seleccione un idioma de ejemplo</label>
			    <select name="lan" class="form-control">
			    	<option value="es" <?php echo (isset($_POST['lan']) && $_POST['lan'] == "es") ? "selected" : "" ?>> Español</option>
			    	<option value="en" <?php echo (isset($_POST['lan']) && $_POST['lan'] == "en") ? "selected" : "" ?>> Ingles</option>
			    </select>
			  </div>
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
			<div class="form-group">
			    <label for="">Flotante mínimo: 5 y máximo: 12, ejemplo: 5.1</label>
			    <input type="text" name="flotante" value="<?php echo $flotante['value'] ?>" class="form-control <?php echo $flotante['input_class'] ?>">
			    <?php echo $flotante['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">fech1 - Formato por defecto d-m-Y</label>
			    <input type="text" name="fecha1" value="<?php echo $fecha1['value'] ?>" class="form-control <?php echo $fecha1['input_class'] ?>">
			    <?php echo $fecha1['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">fecha2 d/m/Y</label>
			    <input type="text" name="fecha2" value="<?php echo $fecha2['value'] ?>" class="form-control <?php echo $fecha2['input_class'] ?>">
			    <?php echo $fecha2['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">fecha3 d/m/Y - Max: 01/01/2020</label>
			    <input type="text" name="fecha3" value="<?php echo $fecha3['value'] ?>" class="form-control <?php echo $fecha3['input_class'] ?>">
			    <?php echo $fecha3['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">fecha4 d/m/Y - Min: 30/01/2020</label>
			    <input type="text" name="fecha4" value="<?php echo $fecha4['value'] ?>" class="form-control <?php echo $fecha4['input_class'] ?>">
			    <?php echo $fecha4['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">fecha5 d/m/Y - fecha entre: 01/01/2020 - 31/01/2020 </label>
			    <input type="text" name="fecha5" value="<?php echo $fecha5['value'] ?>" class="form-control <?php echo $fecha5['input_class'] ?>">
			    <?php echo $fecha5['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">FileInput </label>
			    <input type="file" name="file" class="form-control <?php echo $file['input_class'] ?>">
			    <?php echo $file['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">Email</label>
			    <input type="text" name="email" value="<?php echo $email['value'] ?>" class="form-control <?php echo $email['input_class'] ?>">
			    <?php echo $email['error_html'] ?>
			</div>
			<div class="form-group">
			    <label for="">Clave</label>
			    <input type="password" name="clave" value="<?php echo $clave['value'] ?>" class="form-control  <?php echo $clave['input_class'] ?>">
			    <?php echo $clave['error_html'] ?>
			</div>
			<button type="submit" class="btn btn-primary">Enviar</button>
		</form>
	</div>
	<br/><br/>
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>