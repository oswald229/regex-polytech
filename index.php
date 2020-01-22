<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'classes/event.php';
require 'classes/noeud.php';
require 'classes/arbre.php';

require 'process/functions.php';

$events_array = parse_fichier("file/timetable (copie).txt");

$parMatiere = get_par_matiere($events_array);

echo "<h3>Regexp ensemble</h3>";
$regex = get_regexp($events_array);

echo $regex;

echo "<h3>Regexp Securite informatique</h3>";
$regex = get_regexp($parMatiere['Securite informatique']);

echo $regex;

