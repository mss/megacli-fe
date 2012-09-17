<?php

define ("ADMINMODE", "False"); //отключено для безопасности!

$DEVICE0 = "/dev/sda"; //линуксовый девайс (любой из), под которым видится контроллер (только для smartctl)
//$DEVICE1 = "/dev/sdg"; //для других контроллеров аналогично

$MEGACLI = ""; //Читается из конфига"/usr/local/bin/MegaCli.sh";
$SMARTCTL = "";//Читается из конфига"/usr/sbin/smartctl";

$TMPEVENTLOG = $_SERVER['DOCUMENT_ROOT']."/eventlog/megaraid-events.log"; //времменный
$cfgpath = $_SERVER['DOCUMENT_ROOT']."/config.cfg"; //полный путь к файлу конфига

ReadConfigFile();

//выводит массив строк в хтмл виде
function printstringarray($rawarray)
{
    for ($i=0; $i < count($rawarray); $i++)
    {
	echo $rawarray[$i]."<br>";
    }
}


function MegaCliError($rawarray)
{
    printf("<b><table align=\"center\" width=\"50%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\"  bgcolor=\"#FFFF00\" bordercolor=\"#FFFF00\">");
    printf("<tr bgcolor=\"#FF0000\">");
    printf("<td align=\"center\" ><font color=\"#FFFFFF\">MegaCli ERROR</font></td>");
    printf("</tr>");
    printf("<tr>");
    printf("<td align=\"left\" >");
    printstringarray($rawarray);
    printf("</td>");
    printf("</tr>");
    printf("</table></b>");
}

//-------------------------работа с файлом конфигурации---------------------------

function ReadConfigFile()
{
    global $cfgpath;
    global $MEGACLI;
    global $SMARTCTL;
    $fhandle = fopen($cfgpath, "r");
    if (!$fhandle)
    {
	printf("Can't read config file %s\n",$cfgpath);
    }
    else
    {
	//echo "ReadConfigFile() start<br>\n";
	while (!feof($fhandle)) 
	{
	    $line = fgets($fhandle);
	    list($value) = sscanf($line, MEGACLI."=%s");
	    if ($value!="")
		$MEGACLI = $value;
	    list($value) = sscanf($line, SMARTCTL."=%s");
	    if ($value!="")
		$SMARTCTL = $value;
	}
	fclose($fhandle);
	//echo "end<br>\n";
    }
}

function SaveConfigFile()
{
    global $MEGACLI;
    global $SMARTCTL;
    global $cfgpath;
    echo "SafeConfigFile()<br>\n";
    if (!$fhandle = fopen($cfgpath,'w'))
    {
	printf("Error Saving Config File %!<br>",$cfgpath);
	return;
    }
    fwrite($fhandle, "MEGACLI=".$MEGACLI."\n");
    fwrite($fhandle, "SMARTCTL=".$SMARTCTL."\n");

    fclose($fhandle);
}


//------------------получение параметров из строкового массива--------------------

//получить значение целочисленной переменной из массива вывода
function GetIntValue($rawarray, $varname)
{

    //echo "<br>GetIntValue($rawarray, $varname)";
    $result = "";
    for ($i=0; $i < count($rawarray); $i++)
    {
	//echo "---->".$rawarray[$i]."<br>";
	$pos = strpos($rawarray[$i], ":");
	$left = trim(substr($rawarray[$i], 0, $pos));
	$right = trim(substr($rawarray[$i], $pos + 1));
	if ( $left == $varname)
	{
	    //echo "+\n";
	    $result = $right;
	    break;
	}
    }
    //echo "---->$varname=".$result."<br>";
    settype($result,"integer");
    return ($result);
/*
    //echo "<br>GetIntValue($rawarray, $varname)";
    //$value = 0;
    for ($i=0; $i < count($rawarray); $i++)
    {
	//echo "<br>---->".$rawarray[$i]."<br>";
        list($value) = sscanf($rawarray[$i], $varname." %d");
	if ( !empty($value) ) 
	{
	//echo "+";
	    break;
	}
    }
    //echo "{$value}";
    return ($value);
*/
}


//получить значение hex переменной из массива вывода
function GetHexValue($rawarray, $varname)
{
    //echo "<br>";
    for ($i=0; $i < count($rawarray); $i++)
    {
	//echo "---->".$rawarray[$i]."<br>";
        list($value) = sscanf($rawarray[$i], $varname."%X");
	if ($value!="") 
	{
	    break;
	}
    }
    return ($value);
}


//получить значение строковой переменной из массива вывода
function GetStrValue($rawarray, $varname)
{
    //echo "<br>";
    $result = "";
    for ($i=0; $i < count($rawarray); $i++)
    {
	//echo "---->".$rawarray[$i]."<br>";
	$pos = strpos($rawarray[$i], ":");
	$left = trim(substr($rawarray[$i], 0, $pos));
	$right = trim(substr($rawarray[$i], $pos + 1));
	if ( $left == $varname)
	{
	    $result = $right;
	    break;
	}
    }
    $result = $result;
    //echo "---->$varname=".$result."<br>";
    return ($result);
}


//получить значение шестнадцатиричной переменной из одиночной стороки
function GetHexValueFromString($str, $varname)
{
    list($value) = sscanf($str,$varname."%X");
    return ($value);
}


//--------------------разделение текста на блоки по начальной строке-----------------------

function GetNextTextArray( $textarray, $pattern, &$index )
{
    $ev = array(); //массив строк для фрагмента для диска
    //ищем начальную строку
    for ($n1 = $index; $n1 < count($textarray); $n1++)
    {
	$pos = strpos($textarray[$n1], $pattern );
	//echo "n1=".$n1."pos=$pos --> ".$textarray[$n1]."<br>";
	if (!($pos === false)) //нашли заголовок блока
	{
	    $ev[]=$textarray[$n1];
	    break;
	}
    }    

    //ищем конечную строку эвента
    for ($n2 = $n1 + 1; $n2 < count($textarray); $n2++)
    {
	$pos = strpos($textarray[$n2], $pattern );
	//echo "n2=".$n2."pos=$pos --> ".$textarray[$n2]."<br>";
	if (!($pos === false)) //нашли заголовок СЛЕДУЮЩЕГО блока
	    break;
	$ev[]=$textarray[$n2];
    }    

    $index=$n2; //!!!

    //проверяем если дошли до конца массива
    if ( $n2 -$n1 == 1 )
    {
	return(false);
    }
    else
	return($ev);
}

//--------------------получение параметров запроса-----------------------

function GetIntFromRequest($varname, &$err) //получить целочисленный параметр из строки запроса
{
    $r = $_SERVER['QUERY_STRING'];
    $pos = strpos($r, $varname);
    $err=False;
    if ( $pos === false ) //в запросе нет
    {
	$result=0;
	$err=True;
    }
    else
    {
	list($result) = sscanf($_GET[$varname], "%d");
	if ($result=="")
	{
	    $result=0;
	    $err=True;
	}
    }
    return($result);
}


function GetHexFromRequest($varname) //получить hex параметр из строки запроса
{
    list($result) = sscanf($_GET[$varname], "%X");
    if ($result=="")
	$result=0;
    return($result);
}

?>
