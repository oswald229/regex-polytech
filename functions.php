<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'classes/event.php';
require 'classes/noeud.php';
require 'classes/arbre.php';
/**
 * Vérifie si une ligne est un SUMMARY
 * @param string Indique la chaîne de caractères à analyser
 */
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
/**
 * Permet de lire un fichier, chercher la ligne SUMMARY, découper la chaîne de SUMMARY en 4 sections (localisation, type, matiere, groupe) pour retourner une liste d'events_array
 * @param nom_fichier Indique le nom du fichier à analyser
 */
function parse_fichier($nom_fichier){
	$events_array = [];
	if(!is_null($nom_fichier)||!empty($nom_fichier)){
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
	}
	return $events_array;
}
/**
 * Permet de trier un tableau d'events et de récupérer les events ayant le même nom de matière
 * @param tab Indique le tableau d'events à trier
 */
function get_par_matiere($tab){
	$parMatiere=[];
	if(!is_null($tab) || !empty($tab)){
		foreach ($tab as $key => $event) {
			$tab_keys = array_keys($parMatiere);
			if(!check_array($tab_keys, to_case_and_accent_insensitive($event->getMatiere()))){
				$parMatiere[to_case_and_accent_insensitive($event->getMatiere())]=[];
				array_push($parMatiere[to_case_and_accent_insensitive($event->getMatiere())],$event);
			}else{
				array_push($parMatiere[to_case_and_accent_insensitive($event->getMatiere())],$event);
			} 
		}
	}
	return $parMatiere;
}
/**
 * Permet de rendree une chaîne de caractères insensible à la casse
 * @param string Indique le tableau d'events à trier
 */
function to_case_and_accent_insensitive($string){
	$temp_string = strtolower($string);
	$trans = array("é" => "e", "&eacute;" => "e", "&aacute;" => "a", "á" => "a", "&iacute;" => "i","í"=>"i", "ó"=>"o", "&oacute;" => "o", "&uacute;" => "u", "ú"=>"u","&ouml;" => "u", "ü"=>"u", "et" => "&", " "=>"");
	$temp_string = strtr($temp_string,$trans);
	return $temp_string;
}

function check_array($array, $string){
	$temp_string = to_case_and_accent_insensitive($string);
	foreach($array as $val){
		$temp_val = to_case_and_accent_insensitive($val);
	   if(strcasecmp($temp_val, $temp_string ) == 0){
		  return true;
	   }
	}
	return false;
 }

/**
 * Permet de récupérer les matières de tous les events d'une liste d'events. La récupération se fait de manière à ce qu'il n'y ait pas de doublure
 * @param tab Indique la liste d'events
 */
function get_matieres($tab){
	$communMatiereArray=[];
	if(!is_null($tab) || !empty($tab)){
		foreach ($tab as $value) {
			$matiereCourant=$value->getMatiere();
			if(!check_array($communMatiereArray, $matiereCourant)){//array_search(strtolower($matiereCourant),array_map('strtolower', $communMatiereArray)
				array_push($communMatiereArray,$matiereCourant);
			}
		}
	}
	return $communMatiereArray;
}
/**
 * Permet de récupérer les types (TD ou autres) de tous les events d'une liste d'events. La récupération se fait à manière à ce qu'il n'y ait pas de doublure
 * @param tab Indique la liste d'events
 */
function get_types($tab){//$parMatiere['Reseaux industriels']
	$communTypeArray=[];
	if(!is_null($tab) || !empty($tab)){
		foreach ($tab as $value) {
			$typeCourant=$value->getType();
		
			if(array_search($typeCourant,$communTypeArray)===false){
				
				array_push($communTypeArray,$typeCourant);
			}
			
		}
	}
	return $communTypeArray;
}
/**
 * Permet de récupérer les groupes de tous les events d'une liste d'events. La récupération se fait à manière à ce qu'il n'y ait pas de doublure
 * @param tab Indique la liste d'events
 */
function get_groupes($tab){
	$communGroupeArray=[];
	if(!is_null($tab) || !empty($tab)){
		foreach ($tab as $value) {
			$groupeCourant=$value->getGroupe();
		
			if(array_search($groupeCourant,$communGroupeArray)===false){
				
				array_push($communGroupeArray,$groupeCourant);
			}
			
		}
	}
	return $communGroupeArray;
}
/**
 * Permet de récupérer les localisations (salles) de tous les events d'une liste d'events. La récupération se fait à manière à ce qu'il n'y ait pas de doublure
 * @param tab Indique la liste d'events
 */
function get_localisations($tab){
	$communLocalisationArray=[];
	if(!is_null($tab) || !empty($tab)){
		foreach ($tab as $value) {
			$localisationCourant=$value->getLocalisation();
		
			if(array_search($localisationCourant,$communLocalisationArray)===false){
				array_push($communLocalisationArray,$localisationCourant);
			}
			
		}
	}
	return $communLocalisationArray;
}
/**
 * Permet de spliter chaque composant d'un tableau par l'espace
 * @param tab Indique le tableau à spliter
 */
function split_par_espace($tab){
	$tab_splitte = [];
	$i=0;
	if(!is_null($tab) || !empty($tab)){
		foreach($tab as $val){
			$split_temp = explode(" ", $val);
			array_push($tab_splitte, []);
			foreach($split_temp as $val_2){
				array_push($tab_splitte[$i], $val_2);
			}
			array_push($tab_splitte[$i], "");
			$i++;
		}
	}
	return $tab_splitte;
}
/**
 * Permet de convertir un tableau en arbre en tenant compte de l'unicité de chaque élément à chaque rang de l'arbre et en découpant les éléments (chaînes de caractères) contenant d'espace
 * @param arbre Indique l'arbre à générer
 * @param tab Indique le tableau à convertir
 */
function add_to_arbre($arbre, $tab){
	if(!is_null($tab) || !empty($tab)){
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
}
/**
 * Permet de générer une expression régulière à partir d'une liste d'events
 * @param tab Indique la liste d'events
 */
function get_regexp($tab){
	$regex = "^";
	if(!is_null($tab) || !empty($tab)){
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
	}
	return $regex;
}

function count_slash($chaine){
	$compteur=0;
	for($i=0;$i<strlen($chaine);$i++){
		if($chaine[$i]=='/' || $chaine[$i]=='\\'){
			$compteur++;
		}
	}
	return $compteur;
}


$events_array = parse_fichier("file/timetable (copie).txt");

$parMatiere = get_par_matiere($events_array);

/*echo "<h3>Regexp ensemble</h3>";
$regex = get_regexp($events_array);

echo $regex;

echo "<h3>Regexp Securite informatique</h3>";
$regex = get_regexp($parMatiere['Securite informatique']);

echo $regex;*/

$matieres = get_matieres($parMatiere['securiteinformatique']);
$arbre_matiere = new arbre("Arbre Matière", null);
add_to_arbre($arbre_matiere, $matieres);

var_dump($arbre_matiere->get_liste_noeuds());

//var_dump($parMatiere['securiteinformatique']);

?>