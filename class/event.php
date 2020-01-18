<?php

//SUMMARY:TP - 319 - Programmation JAVA J2EE - 4A SAGI TD G2

class event {

	private $_localisation;
	private $_type;
	private $_matiere;
	private $_groupe;
	
	
  


	public function __construct($localisation, $type, $matiere, $groupe)
	{
        
        $this->setLocalisation(trim($localisation," "));
        $this->setType(trim($type," "));
        $this->setMatiere(trim($matiere," "));
        $this->setGroupe(trim($groupe," "));
       
		

    }
    public function getLocalisation(){
        return $this->_localisation;

    }
    public function getType(){
        return $this->_type;
    }
    public function getMatiere(){
        return $this->_matiere;
    }
    public function getGroupe(){
        return $this->_groupe;
    }



    public function setLocalisation($localisation){
        $this->_localisation=$localisation;

    }
    public function setType($type){
        $this->_type=$type;
    }
    public function setMatiere($matiere){
        $this->_matiere=$matiere;
    }
    public function setGroupe($groupe){
        
        $this->_groupe=$groupe;
    }

  // Notez que le mot-clé static peut être placé avant la visibilité de la méthode (ici c'est public).
  // Access : event::display();
  	public static function display()
  	{
    		
    		echo $this->getType()."de ".$this->getMatiere()."en ".$this->getLocalisation.". Groupe : ".$this->getGroupe();
  		}





}