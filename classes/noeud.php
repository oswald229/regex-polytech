<?php
    class noeud{
        private $_libelle;
        private $_parent;
        private $_fils;
        private $_rang=0;
        private $_numero;
        private static $_num_increment = 2;
        private $_occurence=1;

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

        function get_libelle(){
            return $this->_libelle;
        }

        function get_parent(){
            return $this->_parent;
        }
        function get_fils(){
            return $this->_fils;
        }

        function get_rang(){
            return $this->_rang;
        }

        function get_numero(){
            return $this->_numero;
        }

        function set_numero($numero){
            $this->_numero = $numero;
        }

        function set_libelle($libelle){
            $this->_libelle = $libelle;
        }

        function set_parent($parent){
            $this->_parent = $parent;
            if($parent!=null){
                $parent->add_fils($this);
                $rang=$parent->get_rang()+1;
            }
        }

        function set_rang($rang){
            $this->_rang = $rang;
        }

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

        function get_occurence(){
            $fils=$this->get_fils();
            if($fils!=null && count($fils)==1){
                if(strcmp($fils[0]->get_libelle(), $this->_libelle)==0){
                    $this->_occurence = $this->_occurence + $fils[0]->get_occurence();
                }
            }
            return $this->_occurence;
        }

        function get_regexp(){
            $regex = $this->_libelle . " ";
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
                            $reg_fils = $fils[0]->get_regexp();
                            if(strcmp($reg_fils, "")!=0)    $regex = $regex . " ". $reg_fils;
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