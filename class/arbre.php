<?php
    class arbre{

        private $_noeud_racine;
        private $_niveaux;

        function __construct($nom_arbre, $noeuds_niveau_0=null){
            $this->_noeud_racine = new noeud($nom_arbre, null);
            $this->_noeud_racine->set_rang(-1);
            $this->_noeud_racine->set_numero(1);
            if($noeuds_niveau_0!=null){
                foreach($noeuds_niveau_0 as $noeud){
                    $this->_noeud_racine->add_fils($noeud);
                }
            }
        }

        function add_noeud_niveau_0($noeud){
            $this->$_noeud_racine->add_fils($noeud);
        }

        function get_noeud_racine(){
            return $this->_noeud_racine;
        }

        function add_node($tab, $noeud){
            $i=0;
            $noeud_temp = $this->_noeud_racine;
            $break = 0;
            while($break<=count($tab) && $noeud_temp->get_fils()!=null){
                $fils = $noeud_temp->get_fils();
                for($j=0; $j<count($fils); $j++){
                    if(strcmp($tab[$i], $fils[$j]->get_libelle())==0 && $i==$fils[$j]->get_rang()){
                        $noeud_temp = $fils[$j];
                        $i=$i+1;
                    }
                }
                $break++;
            }
            if($i==count($tab)){
                $noeud_temp->add_fils($noeud);
            }
        }

        function afficher(){
            $noeud_temp = $this->_noeud_racine;
            while($noeud_temp->get_fils()!=null){
                var_dump($noeud_temp);
                
            }
        }
    }
?>