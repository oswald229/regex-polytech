<?php
    /**
    * Cette classe permet de créer un noeud à partir d'un libellé de chaîne de caractères
    */
    class noeud{
        /**
         * Indique le libellé du noeud
         */
        private $_libelle;
        /**
         * Indique le noeud parent
         */
        private $_parent;
        /**
         * Indique les noeuds fils
         */
        private $_fils;
        /**
         * Indique le rang (ou niveau) dans la séquence de noeuds à laquelle il appartient
         */
        private $_rang=0;
        /**
         * Indique le numéro du noeud. Le numéro s'incrémente à chaque création d'un noeud
         */
        private $_numero;
        /**
         * Indique un numéro statique qui représente le numéro courant de création d'un noeud. Le numéro commence par 2 car le noeud racine porte le numéro 1
         */
        private static $_num_increment = 2;
        /**
         * Indique le nombre d'occurence du libellé avec ceux des noeuds fils. S'incrémente à chaque libellé d'un noeud fils identique à celui du noeud courant
         */
        private $_occurence=1;
        /**
         * Le constructeur du noeud
         * @param libelle Indique la chaîne de caractères à partir de laquelle le noeud sera créé
         * @param parent Indique un noeud parent éventuel
         */
        function __construct($libelle, $parent){
            $this->_libelle = $libelle;
            $this->_parent = $parent;
            $this->_numero = self::$_num_increment;
            self::$_num_increment++;
            if($parent!=null){
                $parent->add_fils($this);
                $this->_rang = $parent->get_rang()+1;
            }
        }
        /**
         * Permet de récupérer le libellé
         */
        function get_libelle(){
            return $this->_libelle;
        }
        /**
         * Permet de récupérer le noeud parent
         */
        function get_parent(){
            return $this->_parent;
        }
        /**
         * Permet de récupérer les noeuds fils
         */
        function get_fils(){
            return $this->_fils;
        }
        /**
         * Permet de récupérer le rang(ou niveau) du noeud dans la séquence de noeuds
         */
        function get_rang(){
            return $this->_rang;
        }
        /**
         * Permet de récupérer le numéro du noeud
         */
        function get_numero(){
            return $this->_numero;
        }
        /**
         * Permet d'attribuer un numéro au noeud
         * @param numero Indique le numéro à attribuer au noeud
         */
        function set_numero($numero){
            $this->_numero = $numero;
        }
        /**
         * Permet de donner un libellé au noeud
         * @param libelle Indique le libellé à attribuer au noeud
         */
        function set_libelle($libelle){
            $this->_libelle = $libelle;
        }
        /**
         * Permet d'indiquer le parent du noeud
         * @param parent Indique le parent à associer au noeud
         */
        function set_parent($parent){
            $this->_parent = $parent;
            if($parent!=null){
                $parent->add_fils($this);
                $rang=$parent->get_rang()+1;
            }
        }
        /**
         * Permet d'attribuer un rang au noeud
         * @param rang Indique le rang à attribuer au noeud
         */
        function set_rang($rang){
            $this->_rang = $rang;
        }
        /**
         * Permet d'ajouter un fils au noeud
         * @param fils Indique le noeud fils à ajouter
         */
        function add_fils($fils){
            $compteur=0;
            if($this->_fils==null){
                $this->_fils=[];
            }
            foreach($this->_fils as $fls){
                if(strcmp($fls->get_libelle(), $fils->get_libelle())==0){
                    $compteur++;
                }
            }
            if($compteur==0){
                array_push($this->_fils, $fils);
                $fils->set_parent($this);
                $fils->set_rang($this->_rang+1);
            }
        }
        /**
         * Permet de connaître le nombre d'occurence du libéllé du noeud propagé sur les noeuds fils
         */
        function get_occurence(){
            $fils=$this->get_fils();
            if($fils!=null && count($fils)==1){
                if(strcmp($fils[0]->get_libelle(), $this->_libelle)==0){
                    $this->_occurence = $this->_occurence + $fils[0]->get_occurence();
                }
            }
            return $this->_occurence;
        }
        /**
         * Permet de récupérer l'expression régulière. Concatène le libellé du noeud avec ceux des noeuds fils
         * Exemple : * Noeud : Libellé="TP"
         *              - Fils 1 : Libellé="Spe"
         *              - Fils 2 : Libellé="Imp"
         *           => Expression régulière : TP (Spe |Imp )
         */
        function get_regexp(){
            $regex = $this->_libelle." ";
            $fils = $this->get_fils();
            if($fils!=null){
                if(count($fils)==1){
                    $reg_fils = $fils[0]->get_regexp();
                    if(strcmp($reg_fils, "")!=0){
                        if($this->get_occurence()==1){
                            $regex = $regex . " " . $reg_fils;
                        }else{
                            $regex = $regex . "{" . $this->_occurence . "}";
                            for($i=0;$i<$this->_occurence-1; $i++){
                                $fils = $fils[0]->get_fils();
                            }
                            if(!is_null($fils[0])){
                                $reg_fils = $fils[0]->get_regexp();
                                if(strcmp($reg_fils, "")!=0)    $regex = $regex . " ". $reg_fils;
                            }
                        }
                    }    
                }else if(count($fils)>1){
                    $compteurVide = 0;
                    foreach($fils as $filsValue){
                        if(strcmp($filsValue->get_libelle(), "")==0){
                            $compteurVide++;
                        }
                    }
                    if($compteurVide==0){
                        $regex = $regex . "(";
                        $k=0;
                        foreach($fils as $filsValue){
                            $reg_fils = $filsValue->get_regexp();
                            $regex = $regex . $reg_fils;
                            if($k<count($fils)-1){
                                $regex = $regex . "|";
                            }
                            $k++;
                        }
                        $regex = $regex . ")";
                    }else{
                        $regex = $regex . "(";
                        $k=0;
                        foreach($fils as $filsValue){
                            if(strcmp($filsValue->get_libelle(),"")!=0){
                                $reg_fils = $filsValue->get_regexp();
                                $regex = $regex . $reg_fils;
                                if($k<count($fils)-2){
                                    $regex = $regex . "|";
                                }
                                $k++;
                            }
                        }
                        $regex = $regex . ")?";
                    }
                }
            }
            return $regex;
        }
    }
?>