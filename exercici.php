
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>
 <title>CatalegWeb</title>
 <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

//Un cop indicat el format mitjançant html fent referència a l'arxiu style.css hem indicat que treballarem amb php (>?php)

if (isset($_REQUEST['NomDelGen']) && !empty($_REQUEST['NomDelGen'])) {
	//Reunim els valors del formulari 
	$valorDesplegable = $_POST['taula'];
	$valorGen = $_POST['NomDelGen'];

	//Preparem les variables
	$nomServidor = "localhost";  
	$usuari = "nparedes";
	$contrassenya = "123456";
	$database = "basededades";
	$taulaHuma = "HncbiRefSeq";
	$taulaCavall = "CncbiRefSeq";
	$taulaMosca = "MncbiRefSeq";
	$taulaPeix = "PncbiRefSeq";
	$taulaConnexio = "";
	$URL = "https://genome.ucsc.edu/cgi-bin/hgGene?db=hg19&hgg_gene=";

    //Connectem amb la base de dades
	$connexio = mysqli_connect($nomServidor, $usuari, $contrassenya) or 
			die("No s'ha pogut connectar amb la base de dades: " . mysqli_error());
	mysqli_select_db($connexio, $database) or "La base de dades no és accessible";

	
	// Muntem la consulta en base a les dades introduïdes de manera dinàmica
	if ($valorDesplegable == "Huma") {
		$taulaConnexio = $taulaHuma;
    } elseif ($valorDesplegable == "Cavall") {
		$taulaConnexio = $taulaCavall;
    } elseif ($valorDesplegable == "Mosca") {
		$taulaConnexio = $taulaMosca;
    } elseif ($valorDesplegable == "Peix") {
		$taulaConnexio = $taulaPeix;
    } else {
		die("Organisme no reconegut");
	}
	
	$seleccio = "SELECT name, chrom, strand, txStart, txEnd, cdsStart, cdsEnd, exonCount";
	$resultat = mysqli_query($connexio,"$seleccio FROM $taulaConnexio WHERE name2='$valorGen';");

?>
<?php
//Indiquem que només ens mostri els resultats el valor introduït per l'usuari correspon a un gen d'aquell organisme
	if ($resultat->num_rows > 0) { 
//Creem un títol i una taula per mostrar els resultats de forma ordenada
?>
		<h1>GEN <?=$valorGen; ?></h1>
		<p>
			<a href="<?=$URL.$valorGen;?>" target="_blank">Més informació de <?=$valorGen; ?> a UCSC</a>
		<table>
			<tr>
				<th>TRANSCRIT</th>
				<th>CROMOSOMA</th>
				<th>CADENA</th>
				<th>INICI TRANSCRIPCIÓ</th>
				<th>FINAL TRANSCRIPCIÓ</th>
				<th>INICI REGIÓ CODIFICANT</th>
				<th>FINAL REGIÓ CODIFICANT</th>
				<th>NOMBRE D'EXONS</th>
			</tr>
<?php
  		while ($fila = mysqli_fetch_array($resultat)) {
?>
			<tr>
				<td><?=$fila['name']; ?></td>
				<td><?=$fila['chrom']; ?></td>
				<td><?=$fila['strand']; ?></td>
				<td><?=$fila['txStart']; ?></td>
				<td><?=$fila['txEnd']; ?></td>
				<td><?=$fila['cdsStart']; ?></td>
				<td><?=$fila['cdsEnd']; ?></td>
				<td><?=$fila['exonCount']; ?></td>
			</tr>    
<?php
		} 
?>
		</table>

<?php
	} else { 
//Indiquem que ens mostri un missatge d'error si el valor introduït no correspon a cap gen d'aquell organisme
?>
		<h1>CATÀLEG DE GENS</h1>
		<p>No s'ha trobat cap coincidència per <?=$valorGen?></p>
<?php
	}
} else {
//Indiquem un altre missatge d'error si no s'ha omplert el camp
?>
    <h1>CATÀLEG DE GENS</h1>
	<p>Escriu el nom del gen!</p>
<?php
}
?>
  <p class=footer>
   Web server designed and implemented by Neus Paredes (2021)
  </p>
</body>
</html>
