<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'class/event.php';
require 'class/noeud.php';
require 'class/arbre.php';


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
	$s1=strval($s1); $s2=strval($s2);
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


echo "<h2>Regex Project</h2>";

$events_array = [
	
];



/**
 * Création de chaque objet à partir de la chaîne "SUMMARY". */
$timetable = fopen("file/timetable (copie).txt", "r");
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











$summaries=[
	'types'=>[],
	'localisations'=>[],
	'matieres'=>[],
	'groupes'=>[],
];


/**
 * Création d'un tableau regroupant les "SUMMARY" .
 */




foreach ($events_array as $key => $event) {

	if(!array_key_exists($event->getMatiere(),$summaries['matieres'])){
		$summaries['matieres'][$event->getMatiere()]=0;
		$summaries['matieres'][$event->getMatiere()]++;
	
		
		
	}else{
		$summaries['matieres'][$event->getMatiere()]++;
	}


	if(!array_key_exists($event->getType(),$summaries['types'])){
		$summaries['types'][$event->getType()]=0;
		$summaries['types'][$event->getType()]++;
	
		
		
	}else{
		$summaries['types'][$event->getType()]++;
	}

	if(!array_key_exists($event->getLocalisation(),$summaries['localisations'])){
		$summaries['localisations'][$event->getLocalisation()]=0;
		$summaries['localisations'][$event->getLocalisation()]++;
	
		
		
	}else{
		$summaries['localisations'][$event->getLocalisation()]++;
	}

	if(!array_key_exists($event->getGroupe(),$summaries['groupes'])){
		$summaries['groupes'][$event->getGroupe()]=0;
		$summaries['groupes'][$event->getGroupe()]++;
	
		
		
	}else{
		$summaries['groupes'][$event->getGroupe()]++;
	}
	 
}






$matiere="Reseaux industriels";
$selection=[
	'events'=>[],
	'occ'=>[
		'localisation'=>[],
		'type'=>[],
		'matiere'=>[],
		'groupe'=>[],
	]
	
];
foreach ($events_array as $key => $event){


	if($event->getMatiere()===$matiere){
		array_push($selection['events'],$event);
	}
}





//Sélection des occurences
foreach ($selection['events'] as $key => $value) {
	
	
	//Type
	if(!array_key_exists($value->getType(),$selection['occ']['type'])){

		$selection['occ']['type'][$value->getType()]=0;
		$selection['occ']['type'][$value->getType()]++;
	}else{
		$selection['occ']['type'][$value->getType()]++;
	}

	//Matiere
	if(!array_key_exists($value->getMatiere(),$selection['occ']['matiere'])){

		$selection['occ']['matiere'][$value->getMatiere()]=0;
		$selection['occ']['matiere'][$value->getMatiere()]++;
	}else{
		$selection['occ']['matiere'][$value->getMatiere()]++;
	}

	//Groupe
	if(!array_key_exists($value->getGroupe(),$selection['occ']['groupe'])){

		$selection['occ']['groupe'][$value->getGroupe()]=0;
		$selection['occ']['groupe'][$value->getGroupe()]++;
	}else{
		$selection['occ']['groupe'][$value->getGroupe()]++;
	}

	//Localisation
	if(!array_key_exists($value->getLocalisation(),$selection['occ']['localisation'])){

		$selection['occ']['localisation'][$value->getLocalisation()]=0;
		$selection['occ']['localisation'][$value->getLocalisation()]++;
	}else{
		$selection['occ']['localisation'][$value->getLocalisation()]++;
	}

}


//var_dump($selection);


$test="4A SAGI TD G2";
$futur_liste_de_noeuds=preg_split('/ /',$test);




/*

$arbre_test= new arbre("arbre");

$tp=new noeud("td",$arbre_test);
$td=new noeud("tp",$arbre_test);
$cm=new noeud("cm",$arbre_test);
var_dump($arbre_test);
*/






$parcours_test=[

	'TP'=>[
		"1"=>[],	//TP 1
		"2"=>[],	//TP 1
		"Spe"=>[
			"1"=>[],	//TP Spe 1
			"2"=>[]		//TP Spe 2
		]
	],
	'CM'=>[],	//CM
	'TD'=>[
		"1"=>[],	//TD1	
		"2"=>[]		//TD2
	],
	
	'Sport'=>[],	//Sport
];

function get_or(){

}
function parcours($tab,$size,$chaine=""){

	

	foreach ($tab as $key=> $value) {

		$_chaine=$chaine.$key." ";
		$_size=sizeof($value);

		
		if(sizeof($value)!=0 && is_array($value)){				
			$_chaine.="|";
			parcours($value,sizeof($value),$_chaine);
			
		}
		

		
		else{

			
			echo"<h2>".$_chaine."</h2>";
			
		}
		
	
	}
	
	/*for ($i=0; $i < $size ; $i++) { 
		
		if(is_array($tab[$i]) && sizeof($tab[$i])!=0){
			
			parcours($tab[$i],sizeof($tab[$i]));
			
	
		}else{
			echo"<h2>".$tab[$i]."</h2>";
			
		}



	}*/
	
}

parcours($parcours_test,sizeof($parcours_test));