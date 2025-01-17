<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
		<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<!-- <link rel="stylesheet" href="./pagina_v4/css/2.css"> -->
</head>
</head>
<body>
	<?php include 'menu.php';  ?>

<div class="" id="contenido">

	<div class="container">
  <!-- Content here -->
<br><center><h3>PROYECTOS DE ORGANIGRAMA ACTUALIZADOS</h3></center>
<center>(Proyectos con actualización menor a 3 años)</center><br><br>

<form method="GET" enctype="multipart/form-data" action="resultados_org_actualizados.php">
<div class="row">
	
		  <div class="col-md-8"><input type="text" name="buscar" class="form-control" id="inputAddress" placeholder="Buscar" required></div>
  		  <div class="col-md-4 "><button class="btn btn-primary" type="submit" >Buscar</button></div>
		<input type="hidden" name="sector" value="buscacentral" /><br><br>
	</form>
</div>



<div class="row">


	
	  
<?php  

$fechadeactualizacion = date('2019');
$anoactual = date('Y');
$mesactual = date ('m');
$proyecdisponibles1= $anoactual-1;
$proyecdisponibles2= $anoactual-2;
$proyecdisponibles3= $anoactual-3;
 

$buscar= $_GET["buscar"];
// echo $buscar;
// $pagina = $_GET["pagina"];
// include 'conexionbd.php';
// //  $sql = "SELECT *, SUBSTRING(fecha_autorizacion, -4) AS ano FROM sectorcentral INNER JOIN fechasectocentral ON sectorcentral.id_secretaria = fechasectocentral.id_secretaria WHERE fechasectocentral.id_fech IN (SELECT MAX(fechasectocentral.id_fech) FROM fechasectocentral GROUP BY fechasectocentral.id_secretaria) and sectorcentral.secretaria  like '%$buscar%'";
// //  $sql = "SELECT * FROM fechasectocentral where secretaria  like '%$buscar%'";
// // $sql=" SELECT * , MAX(fecha_verificacion), SUBSTRING(fecha_verificacion, -4) AS fecha1 from fechasectocentral where secretaria  like '%$buscar%' and estatus='autorizado' group by secretaria";

// // $sql="SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus, SUBSTRING(fecha_verificacion, -4) AS fecha1 FROM  (SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus,  max(fecha_verificacion) over (partition by id_secretaria) as max_fecha FROM fechasectocentral) con_max_fecha where  secretaria  like '%$buscar%' and estatus ='autorizado' and fecha_verificacion = max_fecha order by id_secretaria ";
// $sql="SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus, SUBSTRING(fecha_verificacion, -4) AS fecha1 FROM  (SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus,  max(fecha_verificacion) over (partition by id_secretaria) as max_fecha FROM fechasectocentral) con_max_fecha where  secretaria  like '%$buscar%' and fecha_verificacion!='' and estatus='autorizado' and fecha_verificacion = max_fecha order by id_secretaria ";
// $result = mysqli_query($conn, $sql);



include_once "conexionbd.php";

# Cuántos productos mostrar por página
$productosPorPagina = 3;
// Por defecto es la página 1; pero si está presente en la URL, tomamos esa
$pagina = 1;
if (isset ($_GET["pagina"])) {
	$pagina = $_GET["pagina"]; 
}
# El límite es el número de productos por página
$limit = $productosPorPagina;
# El offset es saltar X productos que viene dado por multiplicar la página - 1 * los productos por página
$offset = ($pagina - 1) * $productosPorPagina;
# Necesitamos el conteo para saber cuántas páginas vamos a mostrar
$sentencia = $base_de_datos->query("SELECT count(*) AS conteo FROM organigrama where estatus='autorizado' ");
$conteo = $sentencia->fetchObject()->conteo;
# Para obtener las páginas dividimos el conteo entre los productos por página, y redondeamos hacia arriba
$paginas = ceil($conteo / $productosPorPagina);

# Ahora obtenemos los productos usando ya el OFFSET y el LIMIT
$sentencia = $base_de_datos->prepare("SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus, SUBSTRING(fecha_verificacion, -4) AS fecha1 FROM  (SELECT id_fech,id_secretaria, secretaria, fecha_verificacion, comentario, estatus,  max(fecha_verificacion) over (partition by id_secretaria) as max_fecha FROM organigrama) con_max_fecha where secretaria  like '%$buscar%' and fecha_verificacion!='' and estatus='autorizado' and fecha_verificacion = max_fecha order by id_secretaria desc LIMIT ? OFFSET ? ");
$sentencia->execute([$limit, $offset]);
$productos = $sentencia->fetchAll(PDO::FETCH_OBJ);



?>
<div class="col-xs-12">
						
						
						<table class="table ">
							<thead>

								<th><center><h5>No</center></th>
								<th width="300"><center><h5>Nombre de la Institucion</center></th>
								<th scope="col"><center>Fecha de autorización</center></th>
								<th scope="col"><center>Antiguedad</center></th>
								<th scope="col"><center>Proyecto</center>	</th>
								<th></th>
								<th></th>
							  
							
							</thead>
							<tbody>
							<?php foreach ($productos as $producto) { ?>
								
								<!--  -->
								<?php 
				
								$x=1;
								$i = 0; 
								$max_cols = 6;
								$ano= $anoactual- $producto->fecha1;
								if($ano<=3){
													
										if($i==0||($max_cols == 0)){
											echo "<tr>";
										}
										
										
										echo "<td><center>". $producto->id_fech."</center></td>";
										echo "<td><center>". $producto->secretaria."</center></td>";
										echo "<td><center>". $producto->fecha_verificacion."</center><br></td>";
										echo "<td><center> Hace ". $ano." años</center></td>";
										echo "<td><center> </center></td>";
										
									
										
										if(($i%($max_cols-1)==4 && $i!= 0)||$i == ($conteo-1)){
											echo "</tr>";
										}
										$i++;
								 
								
								
								?>
								
									
								
							<?php } }?>
							</tbody>
						</table>
						
					</div>	