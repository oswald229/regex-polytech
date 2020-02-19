<?php
    /**
     * Cette classe permet de créer une arbre avec un noeud racine dont le libellé est donnée en argument
     */
    class arbre{
        /**
         * Indique le noeud racine de l'arbre
         */
        private $_noeud_racine;
        /**
         * Indique la liste des noeuds composant l'arbre dans un ordre aléatoire
         */
        private $_liste_noeuds;
        private $_regexp = [];
        /**
         * Le constructeur de l'arbre
         * @param nom_arbre Le nom de l'arbre qui sera le nom du noeud racine
         * @param noeuds_niveau_0 Une liste éventuelle de noeuds de niveau 0, fils du noeud racine
         */
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
        /**
         * Permet d'ajouter un noeud de niveau 0 comme fils du noeud racine
         * @param noeud Indique le noeud à ajouter
         */
        function add_noeud_niveau_0($noeud){
            $this->_noeud_racine->add_fils($noeud);
            if($noeud->get_parent()!=null || $noeud->get_fils()!=null)  array_push($this->_liste_noeuds, $noeud);
        }
        /**
         * Permet de récupérer le noeud racine, en occurence, tout l'arbre
         */
        function get_noeud_racine(){
            return $this->_noeud_racine;
        }
        /**
         * Permet d'ajouter un noeud à une position définie par une séquence donnée dans l'arbre
         * @param tab L'unique séquence après laquelle le noeud doit être ajouté
         * @param noeud Le noeud à ajouter
         */
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
        /**
         * Permet de récupérer la liste des noeuds composants l'arbre
         */
        function get_liste_noeuds(){
            return $this->_liste_noeuds;
        }
        /**
         * Permet de récupérer le rang (ou niveau) maximal de l'arbre compté à partir de -1 comme rang du noeud racine
         */
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
        /**
         * Permet d'afficher l'arbre
         */
        function afficher(){
            $noeud_temp = $this->_noeud_racine;
            while($noeud_temp->get_fils!=null){
                var_dump($noeud_temp);
            }
        }
        /**
         * Permet de générer l'expression régulière de tout l'arbre en ne tenant pas compte du noeud racine
         */
        function get_regexp(){
            $fils = $this->_noeud_racine->get_fils();
            $regex="^";
            if($fils!=null && count($fils)==1){
                $regex = $regex . $fils[0]->get_regexp();
            }else if($fils!=null && count($fils)>1){
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

        function make_regexp(){
            $fils=$this->_noeud_racine->get_fils();
            $num=0;
            while($fils!=null){
                //array_push($this->_regexp, []);
                $i=0;
                $temp="";
                if($fils[0]->get_rang()==0){
                    $temp = $temp."^";
                }
                if(count($fils)>1){
                    $temp = $temp."(";
                }
                foreach($fils as $val_fils){
                    if($i!=0){
                        $temp = $temp."|";
                    }
                    $temp = $temp.$val_fils->get_libelle();
                    $i++;
                }
                if(count($fils)>1){
                    $temp = $temp.")";
                }
                $fils_string = [];
                foreach($fils as $val_fils){
                    array_push($fils_string, $val_fils->get_libelle());
                }
                if(count($fils)!=1 && count(get_unique($fils_string))>1){
                    $deb_temp="";
                    if($fils[0]->get_rang()==0){
                        $deb_temp = $deb_temp."^";
                    }
                    $deb_temp = $deb_temp.".*";
                    array_push($this->_regexp, trim($deb_temp));
                }
                if(isset($temp) && strcmp($temp, "")!=0){
                    array_push($this->_regexp, trim($temp));
                }
                $arbre = new arbre("Arbre".strval($num), null);
                add_to_arbre($arbre, $fils_string, true);
                $reg = $arbre->get_regexp();
                if(isset($reg) && strcmp($reg, "")!=0){
                    array_push($this->_regexp, trim($reg));
                }
                $temp_fils = [];
                foreach($fils as $val_fils){
                    if($val_fils->get_fils()!=null){
                        foreach($val_fils->get_fils() as $val){
                            if(strcmp($val->get_libelle(), " ")!==0){
                                array_push($temp_fils, $val);
                            }
                        }
                    }
                }
                $fils=$temp_fils;
                $num++;
            }
            $this->_regexp = get_unique($this->_regexp);
        }

        function get_regexp_1(){
            return $this->_regexp;
        }
    }
?>