<?php

//SUMMARY:TP - 319 - Programmation JAVA J2EE - 4A SAGI TD G2

class event {

	private $localisation;
	private $type;
	private $matiere;
	private $groupe;
	
	const FORCE_PETITE = 20;
  	const FORCE_MOYENNE = 50;
  	const FORCE_GRANDE = 80;
	// Acces : Personnage::FORCE_MOYENNE
	  
  	private static $_compteur = 0;


	public function __construct()
	{
		
		

		}



  // Notez que le mot-clé static peut être placé avant la visibilité de la méthode (ici c'est public).
  // Access : event::parler();
  	public static function parler()
  	{
    		echo 'Je vais tous vous tuer !';
    		
  		}





}
