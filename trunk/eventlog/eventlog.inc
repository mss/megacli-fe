<?php
//парсер файла евентлога

$bgcolor = "#e0e0e0"; //цвет фона таблицы эвентов

class TEvent
{
    var $seqnum;
    var $time;
    var $code;
    var $desc; //Event Description
    function Print2TR() //выводит эвент в виде строки таблицы
    {
	global $bgcolor;
	$bg = $bgcolor;
	printf("<tr>");
	printf("<td width=\"50px\" bgcolor=\"$bg\" align=\"center\" >");
    	printf("<a href=\"eventdetail.php?eventseqnum=%X\" >",$this->seqnum);
	printf("%X",$this->seqnum);
	printf("</a>");
	printf("</td>");
		//если код соотв ошибке то выделяем
	if ( ($this->code & 0xF0) == 0x70)
	    $bg = "#ffb0b0";
	if ( ($this->code & 0xF0) == 0xF0)
	    $bg = "#ffffb0";

	printf("<td width=\"50px\" bgcolor=\"$bg\" align=\"center\" >");
	printf("%X",$this->code);
	printf("</td>");
	    $bg = $bgcolor;
	printf("<td width=\"100px\" bgcolor=\"$bg\">");
	printf("%s",$this->time);
	printf("</td>");
	printf("<td bgcolor=\"$bg\">");
	printf("%s",$this->desc);
	printf("</td>");
	printf("</tr>");
    }
}


function GetNextEventPos( $textarray, &$index )
{
    $ev = array(); //массив строк евента
    //ищем начальную строку эвента
    for ($n1 = $index; $n1 < count($textarray); $n1++)
    {
	$pos = strpos($textarray[$n1], "seqNum:");
	//echo "n1=".$n1."pos=$pos --> ".$textarray[$n1]."<br>";
	if (!($pos === false)) //нашли заголовок евента
	{
	    $ev[]=$textarray[$n1];
	    break;
	}
    }    

    //ищем конечную строку эвента
    for ($n2 = $n1 + 1; $n2 < count($textarray); $n2++)
    {
	$pos = strpos($textarray[$n2], "seqNum:");
	//echo "n2=".$n2."pos=$pos --> ".$textarray[$n2]."<br>";
	if (!($pos === false)) //нашли заголовок СЛЕДУЮЩЕГО евента
	    break;
	$ev[]=$textarray[$n2];
    }    

    $index=$n2; //!!!

    //проверяем если дошли до конца массива
    if ( $n2 -$n1 == 1 )
    {
	return(false);
    }

    //создаем и заполняем структуру     
    $event = new TEvent();
    $event->seqnum = GetHexValueFromString($textarray[$n1], "seqNum:");
    $event->desc = GetStrValue($ev, "Event Description");
    $event->time = GetStrValue($ev, "Time");
    if ( $event->time == "" )
	$event->time = "boot + " . GetStrValue($ev, "Seconds since last reboot") . " s";
    $event->code = GetHexValue($ev, "Code:");
    return ($event);
}


function ParseEventLog( $filename)
{
    $textarray = file($filename); //читаем все в массив
    $evarray = array();
    $n=0; //начинаем с первой строки
    do
    {
	$event = GetNextEventPos($textarray, $n); //получаем следующий эвент
	if ( $event != false ) 
	    $evarray[] = $event;
    }
    while ( $event != false );
    return ( $evarray );
}



?>
