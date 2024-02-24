<?php

/*
* @author Balaji
* @name Turbo Spinner: Article Rewriter - PHP Script
* @copyright 2019 ProThemes.Biz
*
*/

// Disable Errors
error_reporting(1);

function turbo_spin($post, $lang,$source="") {
    
    //Define spin Class
    require_once ('spin.class.php');

    //lang check
    if ($lang == "" || $lang == null){
        $lang = "en";
    }
    
	//spin the data
	$post = html_entity_decode($post);
	//$post = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $post);
	
    $data = stripslashes($post);
	$spin = new spin_my_data;
	$spinned = $spin->spinMyData($data, $lang,$source);
	if($source == "api"){
		return $spinned;
	}
	$st = html_entity_decode($spinned);
	
    //do the regex
	preg_match_all('/({([^}]*)})/', $spinned, $arr);
	//
	if(isset($arr[2]) && count($arr[2]) > 0)
	{
		foreach($arr[2] as $v)
		{
			$arrr = explode('|', $v);
			$st = str_replace('{'.$v.'}', "<b class=\"qtiperar\" style=\"color:".randomColor()."; cursor:pointer\" data-title=\"".$v."\">".parsePhrase($v, 0)."</b>", $st);
		}
	}
    //return ignoring the break point	
	return $st;
}
function turbo_spin_api($post, $lang,$source="") {
    
    //Define spin Class
    require_once ('spin.class.php');

    //lang check
    if ($lang == "" || $lang == null){
        $lang = "en";
    }
    
	//spin the data
	$post = html_entity_decode($post);
	
    $data = stripslashes($post);
	
	$spin = new spin_my_data;
	
	$spinned = $spin->spinMyData($data, $lang,$source);
	$aparr = preg_split('/{(.*?)}/', $spinned, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	$arr3=[];
	$i=0;
	foreach ($aparr as $value) {
		if(!empty($value)){
			if(strpos($value,'|')!==false){
				$arr4 = explode('|',$value); 
				$arr3[$i]['original']=$arr4[0];
				$arr3[$i]['suggestions']=$arr4;
			}else{
				$arr3[$i]['original']=$value;
			}
			$i++;
		}
	}
	
	return json_encode($arr3);
	// exit;
    //return ignoring the break point	
	// return $arr3;
}

function turbo_spin_beta($post, $lang,$source="") {
    
    //Define spin Class
    require_once ('spin.class.php');

    //lang check
    if ($lang == "" || $lang == null){
        $lang = "en";
    }
    
	//spin the data
	$post = html_entity_decode($post);
	//$post = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $post);
	
    $data = stripslashes($post);
	$spin = new spin_my_data;
	$spinned = $spin->spinMyData($data, $lang,$source);
	if($source == "api"){
		return $spinned;
	}
	$st = html_entity_decode($spinned);
	$color = '#000';	
    //do the regex
	preg_match_all('/({([^}]*)})/', $spinned, $arr);
	//
	if(isset($arr[2]) && count($arr[2]) > 0)
	{
		foreach($arr[2] as $v)
		{
			$arrr = explode('|', $v);
			$st = str_replace('{'.$v.'}', "<b>".parsePhrase($v, 0)."</b>", $st);
		}
	}
    //return ignoring the break point	
	return $st;
}

function randomColor()
{
	global $gllastcolor;
    //generating a random color by html pure
	$arrColor = array('blue', 'green', 'red', 'black', 'orange', 'violet');
	if($gllastcolor!='')
	{
$arrColor = array_diff($arrColor, array($gllastcolor));
//reindex
$arrColor = array_values($arrColor);
	}
	$gllastcolor=$arrColor[rand(0, count($arrColor) - 1)];
	return $gllastcolor;
}
function parsePhrase($st, $keep)
{
    //use our clasicl separator bo break a phrase in pieces
	$arr = explode('|', $st);
	$arr2 = array();
	foreach($arr as $k => $v)
	{
		if($keep == 0 && $k == 0) continue; 
		if(trim($v) != '') $arr2[] = $v;
	}
	
	if(count($arr2) == 0){ return false;}
	return $arr2[rand(0, count($arr2) - 1)];
}

?>