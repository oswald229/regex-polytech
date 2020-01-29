<!doctype html>
<?php 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require 'process/functions.php';

$uploaddir = 'file/'; 
$path = null;
if(isset($_POST['file_path']) && strlen($_POST['file_path'])!=0 && isset($_POST['file_path_hidden']) && strlen($_POST['file_path_hidden'])==0){
    $path = $_POST['file_path'];
}
if(isset($_POST['file_path']) && strlen($_POST['file_path'])==0 && isset($_POST['file_path_hidden']) && strlen($_POST['file_path_hidden'])!=0){
    $path = $_POST['file_path_hidden'];
}
if(isset($_POST['file_path']) && strlen($_POST['file_path'])!=0 && isset($_POST['file_path_hidden']) && strlen($_POST['file_path_hidden'])!=0){
    $path = $_POST['file_path'];
}
$filename = "";
$events_array = null;
$parMatiere = null;
$matieres = null;
$regex = null;
$matiere = null;

if(strlen($path)!=0){
    if(count_slash($path)==0){
        move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.basename($_FILES['file']['name']));
        $filename = basename($_FILES['file']['name']);
        $path = "file/".$filename;
    }else{
        if(isset($_POST["submit_button"])){
            $url = $path;
            $file= file_get_contents($url);
            $uploadfile = 'file/'.basename($url);
            file_put_contents($uploadfile, $file);
            $filename = basename($url);
            $path = "file/".$filename;
        }
    }
    if(strlen($filename)!=0){
        $events_array = parse_fichier($path);
        $parMatiere = get_par_matiere($events_array);
        $regex = get_regexp($events_array);
        $matieres = get_matieres($events_array);
        if(isset($_POST['matiere-select']) && strlen($_POST['matiere-select'])!=0){
            $matiere = $_POST['matiere-select'];
            if(isset($parMatiere[to_case_and_accent_insensitive($matiere)])){
                $regex = get_regexp($parMatiere[to_case_and_accent_insensitive($matiere)]);
            }
        }
    }
}

?>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <meta name="description" content="A regular expression project at Polytech Angers">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=ZCOOL+QingKe+HuangYou&display=swap" rel="stylesheet"> 
</head>
<body>
    <header>
        <div class="container">
            <div class="row sph-row">
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
                <div class="col-xs-10 col-md-8 col-lg-6 sph-col-titre">
                    <img src="images/Polytech_Angers.png" alt="Logo Polytech"/>
                    <h3>REGEX-POLYTECH</h3>
                </div>
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
            </div>
        </div>
    </header>
    
        <div class="container">
            <div class="row sph-row">
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
                <div class="col-xs-10 col-md-8 col-lg-6">
                    <form id="regex-form" enctype="multipart/form-data" method="post" action="#">
                        <div class="row">
                            <input type="text" id="file_path" class="form-control col-8" name="file_path" value=""/>
                            <input type="file" id="file" name="file" accept="text/plain" style="display:none;"/>
                            <button type="button" id="bouton" class="form-control col-4">Parcourir</button>
                        </div>
                        <div class="row sph-row-buttons">
                            <input type="hidden" name="file_path_hidden" value="<?php if(!is_null($path)){ echo $path; }?>"/>
                            <input id="regex-submit" type="submit" class="form-control col-12btn btn-secondary" name="submit_button" value="Générer l'expression régulière"/>
                        </div>
                    </form>
                </div>
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
            </div>
            <div class="row sph-row">
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
                <div class="col-xs-10 col-md-8 col-lg-6 sph-col-select">
                    <div class="row">
                    <label>Trier par matière</label>
                        <select name="matiere-select" class="form-control col-12" id="matiere-select" form="regex-form">
                            <?php
                                if($matieres!=null){
                                    for($i=0; $i<count($matieres); $i++){
                                        ?>
                                        <option value="<?php echo $matieres[$i]; ?>"><?php echo $matieres[$i]; ?></option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
            </div>
            <div class="row sph-row">
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
                <div class="col-xs-10 col-md-8 col-lg-6 sph-col-textarea">
                    <div class="row">
                        <textarea name="regex-textarea" class="form-control" rows="10" cols="50"><?php if($regex!=null){ echo $regex; } ?></textarea>
                    </div>
                </div>
                <div class="col-xs-1 col-md-2 col-lg-3"></div>
            </div>
        </div>
    
    <script src="js/jquery.js"></script>
    <script src="js/script.js"></script>
</body>