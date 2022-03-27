<?php
class Functions
    
{

	

 public static function Log($file,$indicator,$data,$thread=""){
	 file_put_contents($file,Dates::timestamp()." seq.".$thread."  ".$indicator." ".$data.' '.PHP_EOL , FILE_APPEND | LOCK_EX);
 }


 public static function getTransactionCount($size,$count){
	$count_size=strlen($count);
	$index="";
	if($count_size<$size){

		while ($count_size<=$size){
			$index=$index."0";
			$count_size++;
		}
		$count =$index."".$count;
		
	} 
	return $count; 
}

public static function getDistanceInKm($latitude1, $longitude1, $latitude2, $longitude2) {  
	$earth_radius = 6371;
  
	$dLat = deg2rad($latitude2 - $latitude1);  
	$dLon = deg2rad($longitude2 - $longitude1);  
  
	$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
	$c = 2 * asin(sqrt($a));  
	$d = $earth_radius * $c;  
  
	return $d;  
  }
  



}
?>