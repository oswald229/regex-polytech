function usingStaticMotifDiffSize($strTab){
	static $motif=[];
	
	if(sizeof($motif)==0){
		$motif=$strTab;
	}else{	
		if(sizeof(array_intersect($motif,$strTab))!=0){
			$motif=array_intersect($motif,$strTab);
		}
		else{			
			return -1;
		}
	}		
	return array($motif);
}

for ($i=0; $i < sizeof($stringTab) ; $i++) { 
	
	$str=clean($stringTab[$i]);//"Lundi Lundi Lundi Mardi Mercredi Mercredi"
	$strTab=explode(" ",$str);//[Lundi, Lundi, Lundi, Mardi, Mercredi, Mercredi]
	
	if(($motif=usingStaticMotifDiffSize($strTab))[0]==-1 || is_null($motif[0])){
		$motif="Accun motif.";
	}else{
		
		
	}
	
}

var_export($motif);
