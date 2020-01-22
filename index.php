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



<body>




<h2>Regex Project</h2>
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