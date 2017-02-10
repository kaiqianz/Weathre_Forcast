
<?php
$streetaddress = $city = $state = $degree = $status = "";
if($_SERVER["REQUEST_METHOD"] == "GET"){
	function test_input($data){
	$data = trim($data);
	return $data;
}
	if(!empty($_GET["streetaddress"])){
		$streetaddress = test_input($_GET["streetaddress"]);
	}
	if(!empty($_GET["city"])){
		$city = test_input($_GET["city"]);
	}
	if(!empty($_GET["state"])){
		$state = test_input($_GET["state"]);
	}
	if(!empty($_GET["degree"])){
		$degree = test_input($_GET["degree"]);
	}
}
$myGooleAPI = "AIzaSyBcDiVJzj6xNdnNDjt-ZQBYrBqKoztdoCs";
$urlForGoogle = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$streetaddress.",".$city.",".$state."&key=".$myGooleAPI;
$xmlGoogle = simplexml_load_file("$urlForGoogle");
$status = $xmlGoogle->xpath("/GeocodeResponse/status")[0];
if($status == "ZERO_RESULTS"){
	return;
}
$latitude = $xmlGoogle->xpath("/GeocodeResponse/result/geometry/location/lat")[0];
$longtitude = $xmlGoogle->xpath("/GeocodeResponse/result/geometry/location/lng")[0];
$myForecastAPI = "677af5e955adf1eb13d9b13da20eb944";
$units_value = "";
$tag_value = "";
if($degree == "Celsius")
{
	$units_value = "si";
	$tag_value = "°C";
}
else if($degree =="Fahrenheit")
{
	$units_value = "us";
	$tag_value = "°F";
}
$Jasonfile = file_get_contents("https://api.forecast.io/forecast/".$myForecastAPI."/".$latitude.",".$longtitude."?units=".$units_value."&exclude=flags");
$obj = json_decode($Jasonfile);
if(!isset ($obj->currently->precipIntensity)){
	$precipIntensity="N.A";
}
else{
$precipIntensityValue = $obj->currently->precipIntensity;
$precipIntensity = "";
if($precipIntensityValue >= 0 && $precipIntensityValue<0.002)
	$precipIntensity = "None";
else if($precipIntensityValue >= 0.002 && $precipIntensityValue<0.017)
	$precipIntensity = "Very Light";
else if($precipIntensityValue >=0.017 && $precipIntensityValue<0.1)
	$precipIntensity = "Light";
else if($precipIntensityValue >= 0.1 && $precipIntensityValue < 0.4)
	$precipIntensity = "Moderate";
else if($precipIntensityValue >= 0.4)
	$precipIntensity ="Heavy";
}
if(!isset ($obj->currently->precipProbability)){
	$chanceOfRain="N.A";
}
else{
$precipProbability = $obj->currently->precipProbability;
$chanceOfRain = strval(intval($precipProbability*100))."%";
}
$iconvalue = "";
if(!isset ($obj->currently->icon)){
	$iconvalue="N.A";
}
else{
$icon = $obj->currently->icon;
if($icon == "clear-day")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/clear.png";
else if($icon == "clear-night")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/clear_night.png";
else if($icon == "rain")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/rain.png";
else if($icon == "snow")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/snow.png";
else if($icon == "sleet")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/sleet.png";
else if($icon == "wind")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/wind.png";
else if($icon == "fog")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/fog.png";
else if($icon == "cloudy")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/cloudy.png";
else if($icon == "partly-cloudy-day")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_day.png";
else if($icon == "partly-cloudy-night")
	$iconvalue = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_night.png";
}
if(!isset ($obj->currently->summary)){
	$summary="N.A";
}
else{
	$summary = $obj->currently->summary;
}
if(!isset ($obj->latitude)){
	$latitude="N.A";
}
else{
	$latitude=$obj->latitude;
}
if(!isset ($obj->longitude)){
	$longitude="N.A";
}
else{
	$longitude=$obj->longitude;
}
if(!isset ($obj->currently->temperature)){
	$temperature="N.A";
}
else{
	$temperature = strval(intval($obj->currently->temperature));
}
if(!isset ($obj->daily->data[0]->temperatureMax)){
	$temperatureMax="N.A";
}
else{
$temperatureMax= strval(intval($obj->daily->data[0]->temperatureMax))."°";
}
if(!isset ($obj->daily->data[0]->temperatureMin)){
	$temperatureMin="N.A";
}
else{
$temperatureMin= strval(intval($obj->daily->data[0]->temperatureMin))."°";
}
if(!isset ($obj->currently->humidity)){
	$humidity="N.A";
}
else{
$humidity = strval(intval(($obj->currently->humidity)*100))."%";
}
date_default_timezone_set($obj->timezone);
if(!isset ($obj->daily->data[0]->sunriseTime)){
	$sunriseTime="N.A";
}
else{
$sunriseTime = date("h:i a", $obj->daily->data[0]->sunriseTime);
}
if(!isset ($obj->daily->data[0]->sunsetTime)){
	$sunsetTime="N.A";
}
else{
$sunsetTime = date("h:i a", $obj->daily->data[0]->sunsetTime);
}
if($degree == "Celsius"){
	$temp="Temp(°C)";
	if(!isset ($obj->currently->temperature)){
		$temperature = "N.A";
	}
	else{
		$temperature = strval(intval($obj->currently->temperature))."°C";
	}
	if(!isset ($obj->currently->windSpeed)){
		$windSpeed = "N.A";
	}
	else{
		$windSpeed = strval(round(floatval($obj->currently->windSpeed),2))." m/s";
	}
	if(!isset ($obj->currently->dewPoint)){
		$dewPoint = "N.A";
	}
	else{
		$dewPoint = strval(round(floatval($obj->currently->dewPoint),2))."° C";
	}
	if(!isset ($obj->currently->visibility)){
		$visibility = "N.A";
	}
	else{
		$visibility = strval(round(floatval($obj->currently->visibility),2))." km";
	}
}
else if($degree =="Fahrenheit"){
	$temp="Temp(°F)";
	if(!isset ($obj->currently->temperature)){
		$temperature = "N.A";
	}
	else{
		$temperature = strval(intval($obj->currently->temperature))."°F";
	}
	if(!isset ($obj->currently->windSpeed)){
		$windSpeed = "N.A";
	}
	else{
		$windSpeed = strval(round(floatval($obj->currently->windSpeed),2))." mph";
	}
	if(!isset ($obj->currently->dewPoint)){
		$dewPoint = "N.A";
	}
	else{
		$dewPoint = strval(round(floatval($obj->currently->dewPoint),2))."° F";
	}
	if(!isset ($obj->currently->visibility)){
		$visibility = "N.A";
	}
	else{
		$visibility = strval(round(floatval($obj->currently->visibility),2))." mi";
	}
}
for($i=1;$i<25;$i++){
	if($degree == "Celsius"){
		if(!isset($obj->hourly->data[$i]->windSpeed)){
			$wind[$i]="N.A";
		}
		else{
			$wind[$i]=strval(round(floatval($obj->hourly->data[$i]->windSpeed),2))." m/s";
		}
		if(!isset ($obj->hourly->data[$i]->visibility)){
		    $vis[$i] = "N.A";
	   }
	    else{
			$vis[$i]=strval(round(floatval($obj->hourly->data[$i]->visibility),2))." km";
	}
		if(!isset($obj->hourly->data[$i]->pressure)){
			$pressure[$i]="N.A";
		}
		else{
			$pressure[$i]=strval(round(floatval($obj->hourly->data[$i]->pressure),2))." hPa";
		}
}
	else if($degree =="Fahrenheit"){
		if(!isset($obj->hourly->data[$i]->windSpeed)){
			$wind[$i]="N.A";
		}
		else{
			$wind[$i]=strval(round(floatval($obj->hourly->data[$i]->windSpeed),2))." mph";
		}
		if(!isset ($obj->hourly->data[$i]->visibility)){
		    $vis[$i] = "N.A";
	   }
	    else{
			$vis[$i]=strval(round(floatval($obj->hourly->data[$i]->visibility),2))." mi";
	}
	if(!isset($obj->hourly->data[$i]->pressure)){
			$pressure[$i]="N.A";
		}
		else{
			$pressure[$i]=strval(round(floatval($obj->hourly->data[$i]->pressure),2))." mb";
		}
	}
	if(!isset ($obj->hourly->data[$i]->humidity)){
	    	$hum[$i]="N.A";
   }
	else{
			$hum[$i] = strval(intval(($obj->hourly->data[$i]->humidity)*100))."%";
   }
	if(!isset ($obj->hourly->data[$i]->time)){
		$daytime[$i]="N.A";
	}
	else{
		$daytime[$i]=date("h:i a",$obj->hourly->data[$i]->time);
	}
	$iconpicture[$i] = "";
	if(!isset ($obj->hourly->data[$i]->icon)){
		$iconpicture[$i]="N.A";
	}
	else{
		if(($obj->hourly->data[$i]->icon) == "clear-day")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/clear.png";
		else if(($obj->hourly->data[$i]->icon) ==  "clear-night")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/clear_night.png";
		else if(($obj->hourly->data[$i]->icon) ==  "rain")
			$iconpicture[$i]= "http://cs-server.usc.edu:45678/hw/hw8/images/rain.png";
		else if(($obj->hourly->data[$i]->icon) ==   "snow")
			$iconpicture[$i]= "http://cs-server.usc.edu:45678/hw/hw8/images/snow.png";
		else if(($obj->hourly->data[$i]->icon) ==   "sleet")
			$iconpicture[$i]= "http://cs-server.usc.edu:45678/hw/hw8/images/sleet.png";
		else if(($obj->hourly->data[$i]->icon) ==   "wind")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/wind.png";
		else if(($obj->hourly->data[$i]->icon) ==   "fog")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/fog.png";
		else if(($obj->hourly->data[$i]->icon) ==   "cloudy")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloudy.png";
		else if(($obj->hourly->data[$i]->icon) ==   "partly-cloudy-day")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_day.png";
		else if(($obj->hourly->data[$i]->icon) ==   "partly-cloudy-night")
			$iconpicture[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_night.png";
	}
	if(!isset ($obj->hourly->data[$i]->cloudCover)){
		$cloudCover[$i]="N.A";
	}
	else{
		$cloudCover[$i]=strval(intval(($obj->hourly->data[$i]->cloudCover)*100))."%";
	}
	if(!isset ($obj->hourly->data[$i]->temperature)){
		$Temp[$i]="N.A";
	}
	else{
		$Temp[$i]=strval(round(floatval($obj->hourly->data[$i]->temperature),2));
	}
}
date_default_timezone_set($obj->timezone);
for($i=1;$i<8;$i++){
		if($degree == "Celsius"){
		if(!isset($obj->daily->data[$i]->windSpeed)){
			$dailywind[$i]="N.A";
		}
		else{
			$dailywind[$i]=strval(round(floatval($obj->daily->data[$i]->windSpeed),2))." m/s";
		}
		if(!isset ($obj->daily->data[$i]->visibility)){
		    $dailyvis[$i] = "N.A";
	   }
	    else{
			$dailyvis[$i]=strval(round(floatval($obj->daily->data[$i]->visibility),2))." km";
	}
		if(!isset($obj->daily->data[$i]->pressure)){
			$dailypressure[$i]="N.A";
		}
		else{
			$dailypressure[$i]=strval(round(floatval($obj->daily->data[$i]->pressure),2))." hPa";
		}
}
	else if($degree =="Fahrenheit"){
		if(!isset($obj->daily->data[$i]->windSpeed)){
			$dailywind[$i]="N.A";
		}
		else{
			$dailywind[$i]=strval(round(floatval($obj->daily->data[$i]->windSpeed),2))." mph";
		}
		if(!isset ($obj->daily->data[$i]->visibility)){
		    $dailyvis[$i] = "N.A";
	   }
	    else{
			$dailyvis[$i]=strval(round(floatval($obj->daily->data[$i]->visibility),2))." mi";
	}
	if(!isset($obj->daily->data[$i]->pressure)){
			$dailypressure[$i]="N.A";
		}
		else{
			$dailypressure[$i]=strval(round(floatval($obj->daily->data[$i]->pressure),2))." mb";
		}
	}
    if(!isset ($obj->daily->data[$i]->time)){
		$day[$i]="N.A";
	}
	else{
		$day[$i] = date("N", $obj->daily->data[$i]->time);
		if($day[$i]=="1"){
			$daydate[$i]= "Monday";
		}
		else if($day[$i]=="2"){
			$daydate[$i]= "Tuesday";
		}
		else if($day[$i]=="3"){
			$daydate[$i]= "Wednesday";
		}
		else if($day[$i]=="4"){
			$daydate[$i]= "Thursday";
		}
		else if($day[$i]=="5"){
			$daydate[$i]= "Friday";
		}
		else if($day[$i]=="6"){
			$daydate[$i]= "Saturday";
		}
		else if($day[$i]=="7"){
			$daydate[$i]= "Sunday";
		}
	}
if(!isset ($obj->daily->data[$i]->time)){
		$month[$i]="N.A";
	}
	else{
		$month[$i] = date("M j",$obj->daily->data[$i]->time);
	}
$iconmap[$i] = "";
if(!isset ($obj->daily->data[$i]->icon)){
	$iconmap[$i]="N.A";
}
else{
if(($obj->daily->data[$i]->icon) == "clear-day")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/clear.png";
else if(($obj->daily->data[$i]->icon) == "clear-night")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/clear_night.png";
else if(($obj->daily->data[$i]->icon) == "rain")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/rain.png";
else if(($obj->daily->data[$i]->icon)== "snow")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/snow.png";
else if(($obj->daily->data[$i]->icon) == "sleet")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/sleet.png";
else if(($obj->daily->data[$i]->icon) == "wind")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/wind.png";
else if(($obj->daily->data[$i]->icon) == "fog")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/fog.png";
else if(($obj->daily->data[$i]->icon) == "cloudy")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloudy.png";
else if(($obj->daily->data[$i]->icon) == "partly-cloudy-day")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_day.png";
else if(($obj->daily->data[$i]->icon) == "partly-cloudy-night")
	$iconmap[$i] = "http://cs-server.usc.edu:45678/hw/hw8/images/cloud_night.png";
}
if(!isset ($obj->daily->data[$i]->temperatureMin)){
		$temperMin[$i]="N.A";
	}
	else{
		$temperMin[$i] = strval(intval($obj->daily->data[$i]->temperatureMin))."°";
	}
if(!isset ($obj->daily->data[$i]->temperatureMax)){
		$temperMax[$i]="N.A";
	}
	else{
		$temperMax[$i] = strval(intval($obj->daily->data[$i]->temperatureMax))."°";
	}
if(!isset ($obj->daily->data[$i]->summary)){
		$sum[$i]="N.A";
	}
else{
		$sum[$i] = $obj->daily->data[$i]->summary;
	}
if(!isset ($obj->daily->data[$i]->sunriseTime)){
		$sunrise[$i]="N.A";
	}
else{
		$sunrise[$i] = date("h:i a", $obj->daily->data[$i]->sunriseTime);
}
if(!isset ($obj->daily->data[$i]->sunsetTime)){
		$sunset[$i]="N.A";
	}
else{
		$sunset[$i] = date("h:i a", $obj->daily->data[$i]->sunsetTime);
}
	
if(!isset ($obj->daily->data[$i]->humidity)){
	$dailyhum[$i]="N.A";
}
else{
    $dailyhum[$i] = strval(intval(($obj->daily->data[$i]->humidity)*100))."%";
}

}

	$jarr=array(
		'list' =>array(
			array(
				'daytime'=>$daytime[1],
				'iconpicture'=>$iconpicture[1],
				'cloudCover'=>$cloudCover[1],
				'Temp'=>$Temp[1],
				'wind'=>$wind[1],
				'vis'=>$vis[1],
				'pressure'=>$pressure[1],
				'hum'=>$hum[1],
				'daydate'=>$daydate[1],
				'month'=>$month[1],
				'iconmap'=>$iconmap[1],
				'temperMin'=>$temperMin[1],
				'temperMax'=>$temperMax[1],
				'sum'=>$sum[1],
				'sunrise'=>$sunrise[1],
				'sunset'=>$sunset[1],
				'dailyhum'=>$dailyhum[1],
				'dailywind'=>$dailywind[1],
				'dailyvis'=>$dailyvis[1],
				'dailypressure'=>$dailypressure[1]

				),
			array(
				'daytime'=>$daytime[2],
				'iconpicture'=>$iconpicture[2],
				'cloudCover'=>$cloudCover[2],
				'Temp'=>$Temp[2],
				'wind'=>$wind[2],
				'vis'=>$vis[2],
				'pressure'=>$pressure[2],
				'hum'=>$hum[2],
				'daydate'=>$daydate[2],
				'month'=>$month[2],
				'iconmap'=>$iconmap[2],
				'temperMin'=>$temperMin[2],
				'temperMax'=>$temperMax[2],
				'sum'=>$sum[2],
				'sunrise'=>$sunrise[2],
				'sunset'=>$sunset[2],
				'dailyhum'=>$dailyhum[2],
				'dailywind'=>$dailywind[2],
				'dailyvis'=>$dailyvis[2],
				'dailypressure'=>$dailypressure[2]
				),
			array(
				'daytime'=>$daytime[3],
				'iconpicture'=>$iconpicture[3],
				'cloudCover'=>$cloudCover[3],
				'Temp'=>$Temp[3],
				'wind'=>$wind[3],
				'vis'=>$vis[3],
				'pressure'=>$pressure[3],
				'hum'=>$hum[3],
				'daydate'=>$daydate[3],
				'month'=>$month[3],
				'iconmap'=>$iconmap[3],
				'temperMin'=>$temperMin[3],
				'temperMax'=>$temperMax[3],
				'sum'=>$sum[3],
				'sunrise'=>$sunrise[3],
				'sunset'=>$sunset[3],
				'dailyhum'=>$dailyhum[3],
				'dailywind'=>$dailywind[3],
				'dailyvis'=>$dailyvis[3],
				'dailypressure'=>$dailypressure[3]
				),
			array(
				'daytime'=>$daytime[4],
				'iconpicture'=>$iconpicture[4],
				'cloudCover'=>$cloudCover[4],
				'Temp'=>$Temp[4],
				'wind'=>$wind[4],
				'vis'=>$vis[4],
				'pressure'=>$pressure[4],
				'hum'=>$hum[4],
				'daydate'=>$daydate[4],
				'month'=>$month[4],
				'iconmap'=>$iconmap[4],
				'temperMin'=>$temperMin[4],
				'temperMax'=>$temperMax[4],
				'sum'=>$sum[4],
				'sunrise'=>$sunrise[4],
				'sunset'=>$sunset[4],
				'dailyhum'=>$dailyhum[4],
				'dailywind'=>$dailywind[4],
				'dailyvis'=>$dailyvis[4],
				'dailypressure'=>$dailypressure[4]
				),
			array(
				'daytime'=>$daytime[5],
				'iconpicture'=>$iconpicture[5],
				'cloudCover'=>$cloudCover[5],
				'Temp'=>$Temp[5],
				'wind'=>$wind[5],
				'vis'=>$vis[5],
				'pressure'=>$pressure[5],
				'hum'=>$hum[5],
				'daydate'=>$daydate[5],
				'month'=>$month[5],
				'iconmap'=>$iconmap[5],
				'temperMin'=>$temperMin[5],
				'temperMax'=>$temperMax[5],
				'sum'=>$sum[5],
				'sunrise'=>$sunrise[5],
				'sunset'=>$sunset[5],
				'dailyhum'=>$dailyhum[5],
				'dailywind'=>$dailywind[5],
				'dailyvis'=>$dailyvis[5],
				'dailypressure'=>$dailypressure[5]
				),
			array(
				'daytime'=>$daytime[6],
				'iconpicture'=>$iconpicture[6],
				'cloudCover'=>$cloudCover[6],
				'Temp'=>$Temp[6],
				'wind'=>$wind[6],
				'vis'=>$vis[6],
				'pressure'=>$pressure[6],
				'hum'=>$hum[6],
				'daydate'=>$daydate[6],
				'month'=>$month[6],
				'iconmap'=>$iconmap[6],
				'temperMin'=>$temperMin[6],
				'temperMax'=>$temperMax[6],
				'sum'=>$sum[6],
				'sunrise'=>$sunrise[6],
				'sunset'=>$sunset[6],
				'dailyhum'=>$dailyhum[6],
				'dailywind'=>$dailywind[6],
				'dailyvis'=>$dailyvis[6],
				'dailypressure'=>$dailypressure[6]
				),
			array(
				'daytime'=>$daytime[7],
				'iconpicture'=>$iconpicture[7],
				'cloudCover'=>$cloudCover[7],
				'Temp'=>$Temp[7],
				'wind'=>$wind[7],
				'vis'=>$vis[7],
				'pressure'=>$pressure[7],
				'hum'=>$hum[7],
				'daydate'=>$daydate[7],
				'month'=>$month[7],
				'iconmap'=>$iconmap[7],
				'temperMin'=>$temperMin[7],
				'temperMax'=>$temperMax[7],
				'sum'=>$sum[7],
				'sunrise'=>$sunrise[7],
				'sunset'=>$sunset[7],
				'dailyhum'=>$dailyhum[7],
				'dailywind'=>$dailywind[7],
				'dailyvis'=>$dailyvis[7],
				'dailypressure'=>$dailypressure[7]
				),
			array(
				'daytime'=>$daytime[8],
				'iconpicture'=>$iconpicture[8],
				'cloudCover'=>$cloudCover[8],
				'Temp'=>$Temp[8],
				'wind'=>$wind[8],
				'vis'=>$vis[8],
				'pressure'=>$pressure[8],
				'hum'=>$hum[8]
				),
			array(
				'daytime'=>$daytime[9],
				'iconpicture'=>$iconpicture[9],
				'cloudCover'=>$cloudCover[9],
				'Temp'=>$Temp[9],
				'wind'=>$wind[9],
				'vis'=>$vis[9],
				'pressure'=>$pressure[9],
				'hum'=>$hum[9]
				),
			array(
				'daytime'=>$daytime[10],
				'iconpicture'=>$iconpicture[10],
				'cloudCover'=>$cloudCover[10],
				'Temp'=>$Temp[10],
				'wind'=>$wind[10],
				'vis'=>$vis[10],
				'pressure'=>$pressure[10],
				'hum'=>$hum[10]
				),
			array(
				'daytime'=>$daytime[11],
				'iconpicture'=>$iconpicture[11],
				'cloudCover'=>$cloudCover[11],
				'Temp'=>$Temp[11],
				'wind'=>$wind[11],
				'vis'=>$vis[11],
				'pressure'=>$pressure[11],
				'hum'=>$hum[11]
				),
			array(
				'daytime'=>$daytime[12],
				'iconpicture'=>$iconpicture[12],
				'cloudCover'=>$cloudCover[12],
				'Temp'=>$Temp[12],
				'wind'=>$wind[12],
				'vis'=>$vis[12],
				'pressure'=>$pressure[12],
				'hum'=>$hum[12]
				),
			array(
				'daytime'=>$daytime[13],
				'iconpicture'=>$iconpicture[13],
				'cloudCover'=>$cloudCover[13],
				'Temp'=>$Temp[13],
				'wind'=>$wind[13],
				'vis'=>$vis[13],
				'pressure'=>$pressure[13],
				'hum'=>$hum[13]
				),
			array(
				'daytime'=>$daytime[14],
				'iconpicture'=>$iconpicture[14],
				'cloudCover'=>$cloudCover[14],
				'Temp'=>$Temp[14],
				'wind'=>$wind[14],
				'vis'=>$vis[14],
				'pressure'=>$pressure[14],
				'hum'=>$hum[14]
				),
			array(
				'daytime'=>$daytime[15],
				'iconpicture'=>$iconpicture[15],
				'cloudCover'=>$cloudCover[15],
				'Temp'=>$Temp[15],
				'wind'=>$wind[15],
				'vis'=>$vis[15],
				'pressure'=>$pressure[15],
				'hum'=>$hum[15]
				),
			array(
				'daytime'=>$daytime[16],
				'iconpicture'=>$iconpicture[16],
				'cloudCover'=>$cloudCover[16],
				'Temp'=>$Temp[16],
				'wind'=>$wind[16],
				'vis'=>$vis[16],
				'pressure'=>$pressure[16],
				'hum'=>$hum[16]
				),
			array(
				'daytime'=>$daytime[17],
				'iconpicture'=>$iconpicture[17],
				'cloudCover'=>$cloudCover[17],
				'Temp'=>$Temp[17],
				'wind'=>$wind[17],
				'vis'=>$vis[17],
				'pressure'=>$pressure[17],
				'hum'=>$hum[17]
				),
			array(
				'daytime'=>$daytime[18],
				'iconpicture'=>$iconpicture[18],
				'cloudCover'=>$cloudCover[18],
				'Temp'=>$Temp[18],
				'wind'=>$wind[18],
				'vis'=>$vis[18],
				'pressure'=>$pressure[18],
				'hum'=>$hum[18]
				),
			array(
				'daytime'=>$daytime[19],
				'iconpicture'=>$iconpicture[19],
				'cloudCover'=>$cloudCover[19],
				'Temp'=>$Temp[19],
				'wind'=>$wind[19],
				'vis'=>$vis[19],
				'pressure'=>$pressure[19],
				'hum'=>$hum[19]
				),
			array(
				'daytime'=>$daytime[20],
				'iconpicture'=>$iconpicture[20],
				'cloudCover'=>$cloudCover[20],
				'Temp'=>$Temp[20],
				'wind'=>$wind[20],
				'vis'=>$vis[20],
				'pressure'=>$pressure[20],
				'hum'=>$hum[20]
				),
			array(
				'daytime'=>$daytime[21],
				'iconpicture'=>$iconpicture[21],
				'cloudCover'=>$cloudCover[21],
				'Temp'=>$Temp[21],
				'wind'=>$wind[21],
				'vis'=>$vis[21],
				'pressure'=>$pressure[21],
				'hum'=>$hum[21]
				),
			array(
				'daytime'=>$daytime[22],
				'iconpicture'=>$iconpicture[22],
				'cloudCover'=>$cloudCover[22],
				'Temp'=>$Temp[22],
				'wind'=>$wind[22],
				'vis'=>$vis[22],
				'pressure'=>$pressure[22],
				'hum'=>$hum[22]
				),
			array(
				'daytime'=>$daytime[23],
				'iconpicture'=>$iconpicture[23],
				'cloudCover'=>$cloudCover[23],
				'Temp'=>$Temp[23],
				'wind'=>$wind[23],
				'vis'=>$vis[23],
				'pressure'=>$pressure[23],
				'hum'=>$hum[23]
				),
			array(
				'daytime'=>$daytime[24],
				'iconpicture'=>$iconpicture[24],
				'cloudCover'=>$cloudCover[24],
				'Temp'=>$Temp[24],
				'wind'=>$wind[24],
				'vis'=>$vis[24],
				'pressure'=>$pressure[24],
				'hum'=>$hum[24]
				),
			),
		);


$arr=array('summary'=>$summary,'temperature'=>$temperature,'humidity'=>$humidity,'windSpeed'=>$windSpeed,'iconvalue'=>$iconvalue,'temperatureMax'=>$temperatureMax,'temperatureMin'=>$temperatureMin,'dewPoint'=>$dewPoint,'visibility'=>$visibility,'precipIntensity'=>$precipIntensity,'chanceOfRain'=>$chanceOfRain,'sunriseTime'=>$sunriseTime,'sunsetTime'=>$sunsetTime,'state'=>strtoupper($state),'city'=>ucwords($city),'latitude'=>$latitude,'longitude'=>$longitude,'temp'=>$temp,'jarr'=>$jarr);
$jason_file=json_encode($arr);
echo $_GET["callback"]."(".$jason_file.")"; 
//echo $_GET["callback"]."(".$Jasonfile.")"; 
?>


