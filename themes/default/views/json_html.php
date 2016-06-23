<?php
//header('Content-Type: application/json');
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

require_once(__CA_APP_DIR__.'/plugins/Pano360/lib/azimuth/lib/azimuth.php');


function Convert(array $origin, array $target, $angle_correc){
    $pre_result=Calculate($origin, $target);
    $yaw=$pre_result['azimuth'];
    if($yaw>180.0){
        $yaw=$yaw-360.0;
    }
    $diff_height=$target['elv']-$origin['elv'];
    $pitch=atan2($diff_height,1000*$pre_result['distKm'])*180.0/pi();
    return array("pitch"=>$pitch, "yaw"=>$yaw+$angle_correc);
    
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://root:pano360@providence.dev/service.php/auth/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$string = curl_exec($ch);
$answer = json_decode($string);

$id_place = $_GET['id'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://providence.dev/service.php/item/ca_places/id/".$id_place."?authToken=".$answer->authToken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$string_place = curl_exec($ch);
$answer_place = json_decode($string_place);
$pts_interet = $answer_place->related->ca_objects;
$centre_place_coords = trim(reset($answer_place->{"ca_places.georeference"})->fr_FR->georeference, "[]");
$centre_place_coords_array = explode(",", $centre_place_coords);
$centre_altitude = reset($answer_place->{"ca_places.altitude"})->fr_FR->altitude;
$angle_correc = reset($answer_place->{"ca_places.angle"})->fr_FR->angle;
$nom = reset($answer_place->preferred_labels->fr_FR)->name;
$url_image = str_replace("providence.dev", "pawtucket.dev", reset($answer_place->representations)->urls->original);



$centre = array("lat"=>$centre_place_coords_array[0], "lon"=>$centre_place_coords_array[1], "elv"=>$centre_altitude);


$result_array = array();

foreach($pts_interet as $pt_interet){
	$id_objet = $pt_interet->object_id;
	//$description = $pt_interet->get("description");
	//print "<pre>";
	//var_dump($description);
	//die();
	curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://providence.dev/service.php/item/ca_objects/id/".$id_objet."?authToken=".$answer->authToken);
	$string_objet = curl_exec($ch);
	$answer_objet = json_decode($string_objet);
	$coords = trim(reset($answer_objet->{"ca_objects.georeference"})->fr_FR->georeference, "[]");
	$coords_array = explode(",", $coords);
	$altitude = reset($answer_objet->{"ca_objects.altitude"})->fr_FR->altitude;
	$obj_array= array("lat"=>$coords_array[0], "lon"=>$coords_array[1], "elv"=>$altitude);
	$conversion = Convert($centre, $obj_array, $angle_correc);
	$t_object = new ca_objects($id_objet);

	
	$result_array[] = array("type"=>"info","text"=>"<b>".$pt_interet->label."</b>"."<br>".$t_object->get("ca_objects.description")."<br>"."<a href='http://pawtucket.dev/index.php/Detail/objects/'.$id_objet'>plus d'informations</a>", "pitch"=>$conversion['pitch'], "yaw"=>$conversion['yaw'], "URL"=>"http://pawtucket.dev/index.php/Detail/objects/".$id_objet);
}

$result = array("default"=>array(), "scenes"=>array("one"=>array()));

$result['default']['author']="";
$result['default']['firstScene']="one";
$result['default']['sceneFadeDuration']=2000;
$result['scenes']['one']=array("title"=>$nom, "panorama"=>$url_image);
$result['scenes']['one']['hotSpots'] = $result_array;	
print(json_encode($result, JSON_PRETTY_PRINT));


