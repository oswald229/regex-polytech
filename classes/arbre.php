<?php
    class arbre{

        private $_noeud_racine;

        private $_liste_noeuds;

        function __construct($nom_arbre, $noeuds_niveau_0=null){
            $this->_noeud_racine = new noeud($nom_arbre, null);
            $this->_noeud_racine->set_rang(-1);
            $this->_noeud_racine->set_numero(1);
            $this->_liste_noeuds = [];
            array_push($this->_liste_noeuds, $this->_noeud_racine);
            if($noeuds_niveau_0!=null){
                foreach($noeuds_niveau_0 as $noeud){
                    $this->_noeud_racine->add_fils($noeud);
                    if($noeud->get_parent()!=null || $noeud->get_fils()!=null)  array_push($this->_liste_noeuds, $noeud);
                }
            }
        }

        function add_noeud_niveau_0($noeud){
            $this->_noeud_racine->add_fils($noeud);
            if($noeud->get_parent()!=null || $noeud->get_fils()!=null)  array_push($this->_liste_noeuds, $noeud);
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
                    if(isset($tab[$i]) && strcmp($tab[$i], $fils[$j]->get_libelle())==0 && $i==$fils[$j]->get_rang()){
                        $noeud_temp = $fils[$j];
                        $i=$i+1;
                    }
                }
                $break++;
            }
            if($i==count($tab)){
                $noeud_temp->add_fils($noeud);
                if($noeud->get_parent()!=null || $noeud->get_fils()!=null)  array_push($this->_liste_noeuds, $noeud);
            }
        }

        function get_liste_noeuds(){
            return $this->_liste_noeuds;
        }

        function get_rang_max(){
            $tab = $this->get_liste_noeuds();
            $max = $tab[0]->get_rang();
            for($i=0; $i<count($tab); $i++){
                if($max < $tab[$i]->get_rang() ){
                    $max = $tab[$i]->get_rang();
                }
            }
            return $max;
        }

        function afficher(){
            $noeud_temp = $this->_noeud_racine;
            while($noeud_temp->get_fils!=null){
                var_dump($noeud_temp);
            }
        }

        function get_regexp(){
            $fils = $this->_noeud_racine->get_fils();
            $regex="";
            if(count($fils)==1){
                $regex = $regex . $fils[0]->get_regexp();
            }else if(count($fils)>1){
                $compteurVide=0;
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
            return $regex;
        }
    }
?>