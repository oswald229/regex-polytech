<?php
    class noeud{
        private $_libelle;
        private $_parent;
        private $_fils;
        private $_rang=0;
        private $_numero;
        private static $_num_increment = 2;

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
    }
?>