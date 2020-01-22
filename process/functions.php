<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require './classes/event.php';
require 'classes/noeud.php';
require 'classes/arbre.php';

function summary_check($string){

	$test="";
	
	for ($i=0; $i < 7; $i++) { 
		if(isset($string[$i])){
			$test=$test.$string[$i];
		}		
	}	
	
	if($test=="SUMMARY"){
		return true;
	}
	return false;
}

function location_check($string){
	$test="";

	for ($i=0; $i < 8; $i++) { 

		if(isset($string[$i])){

			$test=$test.$string[$i];
		}
		
	}

	if($test=="LOCATION"){
		return true;
	}

	return false;
}

function check_commun($s1,$s2){
	
	$size=min( strlen($s1), strlen($s2)	);

	$commun=0;
	for ($i=0; $i <$size ; $i++) { 
		if($s1[$i]===$s2[$i]){
			$commun++;
		}
	}

	return $commun;
}

function get_longer_string($s1,$s2){
	$ret=[
		"short"=>"",
		"long"=>"",
		"longer_size"=>0
	];
	if(strlen($s1)>strlen($s2)){ 
		$ret["long"]=$s1;
		$ret["short"]=$s2;
		$ret["longer_size"]=strlen($s1);
	}
	else{	
		$ret["long"]=$s2;
		$ret["short"]=$s1;
		$ret["longer_size"]=strlen($s2);
	}

	return $ret;
}

function replace_diff($s1,$s2){
	// La plus grande chaîne
	$longer=get_longer_string($s1,$s2);
	$ret=$longer["long"];

	for ($i=0; $i < $longer["longer_size"]; $i++) { 
				
		if(isset($longer["short"][$i])){
			
			if($longer["short"][$i]!=$ret[$i]){
				$ret[$i]="@";
			}


		}else{

			
			$ret[$i]="@";
		}
		
	}

	return $ret;
}

function parse_fichier($nom_fichier){
	echo "<h2>Regex Project</h2>";
	$events_array = [];
	
	$timetable = fopen($nom_fichier, "r");//"timetable (copie).txt"
	if ($timetable) {
		$i=0;
		while (($line = fgets($timetable, 4096)) !== false) {
	
			
			if(summary_check($line)){
				//echo $line."<br>";
				//SUMMARY:TD - 118 - Communication - 4A SAGI TD G2 
				$splitted=preg_split("/:/",$line);
				$splitted_=preg_split("/-/",$splitted[1]);
				//var_dump($splitted_);
				$events_array[$i] = new event(
					$splitted_[1],
					$splitted_[0],
					$splitted_[2],
					$splitted_[3]
				);
				
				$i++;
	
				
			}
			//$localisation, $type, $matiere, $groupe
		//	0 => string 'TP spe ' (length=7)
		//	1 => string ' Hall technologie CFAO, Hall technologie chaîne de production ' (length=63)
		//	2 => string ' Supervision industrielle ' (length=26)
		//	3 => string ' 4A SAGI TP G2
		  
	
	
			if(location_check($line)){
				//echo $line."<br>";
				//LOCATION:118
			}
		}
		if (!feof($timetable)) {
			echo "Erreur: fgets() a échoué\n";
		}
		fclose($timetable);
	}
	return $events_array;
}

function get_par_matiere($tab){
	$parMatiere=[];

	foreach ($tab as $key => $event) {
	
		if(!array_key_exists($event->getMatiere(),$parMatiere)){
			$parMatiere[$event->getMatiere()]=[];
			array_push($parMatiere[$event->getMatiere()],$event);
		}else{
			array_push($parMatiere[$event->getMatiere()],$event);
		}
		 
	}
	return $parMatiere;
}

function get_matieres($tab){
	$communMatiereArray=[];
	foreach ($tab as $value) {
		$matiereCourant=$value->getMatiere();
	
		if(array_search($matiereCourant,$communMatiereArray)===false){
			
			array_push($communMatiereArray,$matiereCourant);
		}
		
	}
	return $communMatiereArray;
}

function get_types($tab){//$parMatiere['Reseaux industriels']
	$communTypeArray=[];
	foreach ($tab as $value) {
		$typeCourant=$value->getType();
	
		if(array_search($typeCourant,$communTypeArray)===false){
			
			array_push($communTypeArray,$typeCourant);
		}
		
	}
	return $communTypeArray;
}

function get_groupes($tab){
	$communGroupeArray=[];
	foreach ($tab as $value) {
		$groupeCourant=$value->getGroupe();
	
		if(array_search($groupeCourant,$communGroupeArray)===false){
			
			array_push($communGroupeArray,$groupeCourant);
		}
		
	}
	return $communGroupeArray;
}

function get_localisations($tab){
	$communLocalisationArray=[];
	foreach ($tab as $value) {
		$localisationCourant=$value->getLocalisation();
	
		if(array_search($localisationCourant,$communLocalisationArray)===false){
			
			array_push($communLocalisationArray,$localisationCourant);
		}
		
	}
	return $communLocalisationArray;
}

function split_par_espace($tab){
	$tab_splitte = [];
	$i=0;
	foreach($tab as $val){
		$split_temp = explode(" ", $val);
		array_push($tab_splitte, []);
		foreach($split_temp as $val_2){
			array_push($tab_splitte[$i], $val_2);
		}
		array_push($tab_splitte[$i], "");
		$i++;
	}
	return $tab_splitte;
}

function add_to_arbre($arbre, $tab){
	$tab_splitte = split_par_espace($tab);
	for($i=0; $i<count($tab_splitte); $i++){
		$noeud = new noeud($tab_splitte[$i][0], null);
		$arbre->add_noeud_niveau_0($noeud);
	}
	for($i=0; $i<count($tab_splitte); $i++){
		for($j=1; $j<count($tab_splitte[$i]); $j++){
			$tab_temp = [];
			for($k=0; $k<$j; $k++){
				array_push($tab_temp, $tab_splitte[$i][$k]);
			}
			$noeud = new noeud($tab_splitte[$i][$j], null);
			$arbre->add_node($tab_temp, $noeud);
		}
	}
}

function get_regexp($tab){
	$regex = "^";
	$matieres = get_matieres($tab);
	$arbre_matiere = new arbre("Arbre Matière", null);
	add_to_arbre($arbre_matiere, $matieres);
	$types = get_types($tab);
	$arbre_type = new arbre("Arbre Type", null);
	add_to_arbre($arbre_type, $types);
	$groupes = get_groupes($tab);
	$arbre_groupe = new arbre("Arbre Groupe", null);
	add_to_arbre($arbre_groupe, $groupes);
	$localisations = get_localisations($tab);
	$arbre_localisation = new arbre("Arbre Localisation", null);
	add_to_arbre($arbre_localisation, $localisations);
	$regex = $regex . $arbre_type->get_regexp() . " - " . $arbre_localisation->get_regexp() . " - " . $arbre_matiere->get_regexp() . " - " . $arbre_groupe->get_regexp();
	return $regex;
}


/*$events_array = parse_fichier("file/timetable (copie).txt");

$parMatiere = get_par_matiere($events_array);

echo "<h3>Regexp ensemble</h3>";
$regex = get_regexp($events_array);

echo $regex;

echo "<h3>Regexp Securite informatique</h3>";
$regex = get_regexp($parMatiere['Securite informatique']);

echo $regex;*/

// Matiere Regex	
// .*(Reseaux industriels).* 	
/*$regMatiere="";

echo "Par matiere";

var_dump($parMatiere['Reseaux industriels']);

/*

var_dump($communMatiereArray);*/

// Type Regex
/*$regType="";

$communTypeArray = get_types($parMatiere['Reseaux industriels']);
/**
 * Faire un tableau avec les mots differents : 
 * [TP, TP spe, CM ,Contrôle continu ]
 * 
 * Au moins 2 caracters en commun.
 */

	/*for ($i=0; $i < sizeof($communTypeArray) ; $i++) { 
		
		if(isset($communTypeArray[$i+1])){
			$com=check_commun($communTypeArray[$i],$communTypeArray[$i+1]);
			
			if($com>=2){
				$regType=replace_diff($communTypeArray[$i],$communTypeArray[$i+1]);

			}
		}
	}*/


/*var_dump($communTypeArray);
var_dump($regType);
// Groupe Regex
$regGroupe="";

$communGroupeArray = get_groupes($parMatiere['Reseaux industriels']);

$arbre_groupe = new arbre("Arbre Groupe", null);

add_to_arbre($arbre_groupe, $communGroupeArray);

echo "Mon arbre groupe";
var_dump($arbre_groupe);

echo "Rang max groupe";
var_dump($arbre_groupe->get_rang_max());

var_dump($communGroupeArray);
// Localisation Regex

$regLocalisation="";

$communLocalisationArray = get_localisations($parMatiere['Reseaux industriels']);

$arbre = new arbre("Arbre Localisation", null);

add_to_arbre($arbre, $communLocalisationArray);

$fils = $arbre->get_noeud_racine()->get_fils();
echo "Mon arbre localisation";
var_dump($arbre);

echo "Mon regexp localisation";
var_dump($arbre->get_regexp());

echo "Rang max localisation";
var_dump($arbre->get_rang_max());

echo "Mes fils";

var_dump($fils[5]->get_fils());

echo "Liste noeuds";
var_dump($arbre->get_liste_noeuds());

var_dump($communLocalisationArray);*/