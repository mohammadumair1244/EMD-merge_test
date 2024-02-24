﻿<?php

/*
* @author Balaji
* @name Turbo Spinner: Article Rewriter - PHP Script
* @copyright 2019 ProThemes.Biz
*
*/

// Disable Errors
error_reporting(1);

class spin_my_data
{

    function randomSplit($string)
    {
        $string = Trim($string);
        $res = -1;
        $finalData = "";
        $loopinput = $this->parse_br($string);
        for ($loop = 0; $loop < count($loopinput); $loop++)
        {
            for ($loopx = 0; $loopx < count($loopinput[$loop]); $loopx++)
            {
                if (!$loopinput[$loop][$loopx] == "" || "/n")
                {
                    $res++;
                    if (strstr($loopinput[$loop][$loopx], "|"))
                    {
                        $out = explode("|", $loopinput[$loop][$loopx]);
                        $output[$res] = $out[rand(0, count($out) - 1)];
                    } else
                    {
                        $output[$res] = $loopinput[$loop][$loopx];
                    }
                }
            }
        }
        for ($loop = 0; $loop < count($output); $loop++)
        {
            $finalData .= $output[$loop];
        }
        return $finalData;
    }

    function spinMyData($data, $lang,$source="")
    {
        $patern_code_1 = "/<[^<>]+>/us";
        $patern_code_2 = "/\[[^\[\]]+\]/i";
        $patern_code_3 = '/\$@.*?\$@/i';

        $data = Trim($data);
        preg_match_all($patern_code_1, $data, $found1, PREG_PATTERN_ORDER);
        preg_match_all($patern_code_2, $data, $found2, PREG_PATTERN_ORDER);
        preg_match_all($patern_code_3, $data, $found3, PREG_PATTERN_ORDER);
        $htmlcodes = $found1[0];
        $bbcodes = $found2[0];
        $vbcodes = $found3[0];
        $founds = array();
        $current_dir = dirname(__file__);
        $sel_lang = Trim($lang);

        $arr_data = array_merge($htmlcodes, $bbcodes, $vbcodes);
        foreach ($arr_data as $code)
        {
            $code_md5 = md5($code);
            $data = str_replace($code, '%%!%%' . $code_md5 . '%%!%%', $data);
        }

        $file = file($current_dir . '/' . $sel_lang . '_db.sdata');
		
        foreach ($file as $line)
        {

            $synonyms = explode('|', $line);
            $arr = [];
            $one = true;
            foreach ($synonyms as $word)
            {
                $word = trim($word);
                if ($word != '')
                {
                    $word = str_replace('/', '\/', $word);

                    if (preg_match('/\b' . $word . ' \b/u', $data))
                    {   
                        $match = trim($word);                        
                        $line = str_replace(["|$match","$match|"],['',''],$line);
                        $line = trim($line,"|");
                        $line = $match.'|'.$line;
                        $line = trim($line,"|");
                        $founds[md5($word)] = str_replace(array("\n", "\r"), '', $line);
                        if($source == 'api'){
                            $data = preg_replace('/\b' . $word . '\b/u', 'md5Found'.md5($word).'md5Found', $data);
                        }else{
                            $data = preg_replace('/\b' . $word . '\b/u', md5($word), $data);
                        }
                        
                    }
                }
            }

        }
        
        foreach ($arr_data as $code)
        {
            $code_md5 = md5($code);
            $data = str_replace('%%!%%' . $code_md5 . '%%!%%', $code, $data);
        }
        
        if($source == 'api'){
            $_D['data'] = $data;
            $_D['founds'] = $founds;
            return $_D;
        }
        $array_count = count($founds);

        if ($array_count != 0)
        {
            foreach ($founds as $code => $value)
            {
                $data = str_replace($code, '{' . $value . '}', $data);
            }
        }
        return $data;
    }


    function parse_br($string)
    {
        @$string = explode("{", $string);
        for ($loop = 0; $loop < count($string); $loop++)
        {
            @$data[$loop] = explode("}", $string[$loop]);
        }
        return $data;
    }

}