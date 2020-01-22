<html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'classes/event.php';
require 'classes/noeud.php';
require 'classes/arbre.php';

require 'process/functions.php';

?>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">



<body>



<div class="container">
<br><br>
<h2>Regex Project</h2>
<br>

<div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1">Lien ics</span>
  </div>
  <input type="text" class="form-control" placeholder="Url ics" aria-describedby="basic-addon1">
</div>
<br>



<div class="input-group">

  <div class="custom-file">
    	<input type="file" class="custom-file-input" accept=".ics,.txt" id="inputGroupFile04">
    	<label class="custom-file-label" for="inputGroupFile04">Choose file</label>
  </div>
  
	
</div>

<br>

<button type="button" class="btn btn-primary">Get selection</button>

<br>



<?php
	$events_array = parse_fichier("file/timetable (copie).txt");

	$parMatiere = get_par_matiere($events_array);

	$regex = get_regexp($events_array);



	$regex = get_regexp($parMatiere['Securite informatique']);

	echo $regex;
?>




</body>


</html>