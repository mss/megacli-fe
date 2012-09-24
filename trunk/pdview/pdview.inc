<?php

include ("../globalfunc.inc");

function main2crc32($simulate) //возвращает контрольную сумму
{
    global $MEGACLI;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $selectedop=GetIntFromRequest("selectedop");
    $selectedpdisk=GetIntFromRequest("selectedpdisk");
    $detailview=GetIntFromRequest("detailview");
    //---получаем список PDList
    $cmd = $MEGACLI . " -PDList -a" . $selectedcontroller . " -NoLog";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
    {
	MegaCliError($outarray);
	return;
    }
    //---получаем список ShowSummary
    $cmd = $MEGACLI . " -ShowSummary -a" . $selectedcontroller . " -NoLog";
    exec($cmd, $outarray2, $errcode);
    $sumarray = array_merge ($outarray,$outarray2); //массив для контрольной суммы
    if ($errcode > 0)
    {
	MegaCliError($outarray2);
	return;
    }
    //---парсим списки
    $n = 0;  //начинаем с первой строки
    do
    {
	$driveinfo = GetNextTextArray($outarray, "Enclosure Device ID:" , $n); //получаем следующий текстовый блок
	if ( $driveinfo != false )
	{
	    //ищем этот-же диск в summary
	    $slotnumber = GetIntValue($driveinfo, "Slot Number");
	    //echo "Slot Number=".$slotnumber."<br>\n";
    	    $n2 = 0; // для summary
	    do
	    {
		$driveinfo2 = GetNextTextArray($outarray2, "Connector" , $n2); //получаем следующий текстовый блок из Summary
		$s = GetStrValue($driveinfo2, "Connector");
		//echo "s=".$s."<br>\n";
		if ( strpos($s, "Slot " . $slotnumber) )
		{ //нашли
		    //echo "+";
		    break;
		}
	    }
	    while ( $driveinfo2 != false );
	    //получаем по этому диску статус ребилда
	    $enclosure = GetIntValue($driveinfo, "Enclosure Device ID");
	    $cmd = $MEGACLI . " -PDRbld -ShowProg -PhysDrv [".$enclosure.":".$slotnumber."] -a" . $selectedcontroller . " -NoLog | grep \"Completed\" ";
	    unset ($outarray3);
	    exec($cmd, $outarray3, $errcode);
	    $sumarray = array_merge( $sumarray, $outarray3 );
	    if ($errcode > 0) //операция не идет
	    {
		unset($outarray3);
		$outarray3 = "";
	    }
	    //printstringarray($outarray3);
	    //получаем по этому диску статус Clear
	    $enclosure = GetIntValue($driveinfo, "Enclosure Device ID");
	    $cmd = $MEGACLI . " -PDClear -ShowProg -PhysDrv [".$enclosure.":".$slotnumber."] -a" . $selectedcontroller . " -NoLog | grep \"Completed\" ";
	    unset ($outarray4);
	    exec($cmd, $outarray4, $errcode); //
	    $sumarray = array_merge( $sumarray, $outarray4);
	    if ($errcode > 0) //операция не идет
	    {
		unset($outarray4); //очищаем
		$outarray4 = "";
	    }
	    //printstringarray($outarray4);
	    if (!$simulate)
	    {
    		//генерируем таблицу
		echo "<br>\n";
		pd( $driveinfo, $driveinfo2, $outarray3, $outarray4, $detailview );
	    }
	}
    }
    while ( $driveinfo != false );

    //if (!$simulate)
    //	printstringarray($sumarray);
    //считаем контрольную сумму текста $sumarray
    $s = implode( "|" , $sumarray ); //масив в строку
    global $crc32sum;
    $crc32sum = crc32($s);
//    echo $s . "<br>" .$crc32sum;
    return( $crc32sum );
}


function pd($outarray, $outarray2, $outarray3, $outarray4, $detailview) //по одному диску
{
    printf("<div align=\"center\">");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" >");

    $value1 = GetIntValue($outarray, "Enclosure Device ID");
    $value2 = GetStrValue($outarray2, "Connector");

    printTR( "Enclosure / Port / Slot", $value1.": ".$value2, "#0000FF" , "#FFFFFF" );
//    $name = "Slot Number";
//    printTR( $name, GetIntValue($outarray, $name), BGCOLOR);
    $name = "Device Id";
    printTR( $name, GetIntValue($outarray, $name), BGCOLOR, "#000000");


    $value1 = GetIntValue($outarray, "Media Error Count");
    $value2 = GetIntValue($outarray, "Other Error Count");
    printTR( "Error Media/Other", $value1 ."/".$value2, BGCOLOR, "#000000");

    $name = "Disk Type";
    printTR( $name, GetStrValue($outarray2, $name), BGCOLOR, "#000000");

    $name = "Raw Size";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");

    if ($detailview == 1) //подробный вид
    {
	$name = "Predictive Failure Count";
	printTR( $name, GetIntValue($outarray, $name), BGCOLOR, "#000000");
	$name = "Last Predictive Failure Event Seq Number";
	printTR( $name, GetIntValue($outarray, $name), BGCOLOR, "#000000");
	$name = "Sequence Number";
	printTR( $name, GetIntValue($outarray, $name), BGCOLOR, "#000000");
	$name = "Non Coerced Size";
	printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
	$name = "Coerced Size";
	printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
	$value1 = GetStrValue($outarray, "Device Speed");
	$value2 = GetStrValue($outarray, "Link Speed");
	printTR( "Speed Device/Link", $value1." / ".$value2, BGCOLOR, "#000000");
    }

    $name = "Firmware state";
    $state = GetStrValue($outarray, $name);
    if ( $state == "Online, Spun Up" ) 
	printTR( $name, $state, BGCOLOR, "#00FF00");
    else
	if ( $state == "Rebuild" )
	    printTR( $name, $state, BGCOLOR, "#FFAAAA");
	else
	    printTR( $name, $state, BGCOLOR, "#FF0000");
    $name = "Inquiry Data";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");

    $name = "Foreign State";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");

    $encl = GetIntValue($outarray, "Enclosure Device ID");
    $slot = GetIntValue($outarray, "Slot Number");
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $diskid = GetIntValue($outarray, "Device Id");

    $name = "Operation In Progress";
    $value = GetStrValue($outarray2, $name);
    if ( ($value != "") && ($value != "Consistency Check") ) //подпорка от ложного появления "Consistency Check"
	printTR( $name, $value, "#FFFF00", "#000000");

    if ( (is_array($outarray3)) && (count($outarray3) > 0) )   //для Clear
	printTR( "", $outarray3[0], "#FFFF00", "#FF0000");
    if ( (is_array($outarray4)) && (count($outarray4) > 0) ) //для Rebuild
	printTR( "", $outarray4[0], "#FFFF00", "#FF0000");

    printButtons($selectedcontroller, $encl, $slot, $diskid, $state);
    
    printf("</table>");
    printf("</div>");

}


function printTR($name, $svalue, $bgcolor, $fgcolor)
{
    echo "<tr bgcolor=\"".$bgcolor."\"><td width=\"40%%\"> <font color=\"".$fgcolor."\"><b>\n";
    echo $name;
    echo "</b></font></td><td> <font color=\"".$fgcolor."\"><b>\n";
    echo $svalue;
    echo "</b></font></td></tr>\n";
}


function printButtons($controller, $encl, $slot, $diskid, $state)
{
    $disarray = Array();
    //для Online, Spun Up
    $disarray["SMART"]["Online, Spun Up"]          = "";
    $disarray["CLEAR"]["Online, Spun Up"]          = "disabled";
    $disarray["REBUILD"]["Online, Spun Up"]        = "disabled";
    $disarray["MAKEGOOD"]["Online, Spun Up"]       = "disabled";
    $disarray["REPLACEMISSING"]["Online, Spun Up"] = "disabled";

    //для Rebuild
    $disarray["SMART"]["Rebuild"]          = "";
    $disarray["CLEAR"]["Rebuild"]          = "disabled";
    $disarray["REBUILD"]["Rebuild"]        = "disabled";
    $disarray["MAKEGOOD"]["Rebuild"]       = "disabled";
    $disarray["REPLACEMISSING"]["Rebuild"] = "disabled";

    //для Unconfigured(bad)
    $disarray["SMART"]["Unconfigured(bad)"]          = "";
    $disarray["CLEAR"]["Unconfigured(bad)"]          = "disabled";
    $disarray["REBUILD"]["Unconfigured(bad)"]        = "disabled";
    $disarray["MAKEGOOD"]["Unconfigured(bad)"]       = "";
    $disarray["REPLACEMISSING"]["Unconfigured(bad)"] = "disabled";

    //для Unconfigured(good), Spun Up
    $disarray["SMART"]["Unconfigured(good), Spun Up"]          = "";
    $disarray["CLEAR"]["Unconfigured(good), Spun Up"]          = "";
    $disarray["REBUILD"]["Unconfigured(good), Spun Up"]        = "disabled";
    $disarray["MAKEGOOD"]["Unconfigured(good), Spun Up"]       = "disabled";
    $disarray["REPLACEMISSING"]["Unconfigured(good), Spun Up"] = "";

    //для Offline
    $disarray["SMART"]["Offline"]          = "";
    $disarray["CLEAR"]["Offline"]          = "disabled";
    $disarray["REBUILD"]["Offline"]        = "";
    $disarray["MAKEGOOD"]["Offline"]       = "disabled";
    $disarray["REPLACEMISSING"]["Offline"] = "disabled";


    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\"><HR></td></tr>\n";
    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\">\n";

    echo "<form action=\"pdview.php\">\n";

    echo "<button ".$disarray["SMART"][$state]." value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $encl, $slot, $diskid, 1)\" >\n";
    echo "View S.M.A.R.T.</button>\n";

    echo "<button ".$disarray["CLEAR"][$state]." value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $encl, $slot, $diskid, 2)\" >\n";
    echo "Start Clear</button>\n";

    echo "<button ".$disarray["REBUILD"][$state]." value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $encl, $slot, $diskid, 3)\" >\n";
    echo "Start Rebuild</button>\n";

    echo "<button ".$disarray["MAKEGOOD"][$state]." value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $encl, $slot, $diskid, 4)\" >\n";
    echo "Make Good</button>\n";

    echo "<button ".$disarray["REPLACEMISSING"][$state]." value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $encl, $slot, $diskid, 5)\" >\n";
    echo "Replace Missing</button>\n";

    echo "</form><br></td>\n";
    echo "</tr>\n";
}


class TMissingDisk
{
    var $no;
    var $array;
    var $row;
    var $size;
    var $sizestr; //размерность "MB" etc.
}



function parsemissing( $outarray ) //парсить вывод -PDGetMissing
{
    $result = Array();
    //если миссинг дисков нет то ничего не делаем
    for ($i=0; $i < count($outarray); $i++)
    {
	$pos = strpos($outarray[$i], "- No Missing Drive is Found.");
	if (!($pos === false)) //нашли
	    return;
    }

    //формируем список миссинг дисков
    for ($i=0; $i < count($outarray); $i++)
    {
	//echo "---->".$outarray[$i]."<br>";

        list( $no, $array, $row, $size, $mb ) = sscanf($outarray[$i], " %d %d %d %d %s");
	//var_dump( $no, $array, $row, $size, $mb );
	if ( ($no !== NULL ) && ($array !== NULL) && ($row !== NULL) && ($size !== NULL) && ($mb == "MB") )
	{
	    $disk = new TMissingDisk();
	    $disk->no = $no;
	    $disk->array = $array;
	    $disk->row = $row;
	    $disk->size = $size;
	    $disk->sizestr = $mb;
	    $result[] = $disk; //сохраняем в массиве
//---убрать после отладки
//$disk->no++;
//$result[] = $disk; 
//$disk->no++;
//$result[] = $disk;
//-----------------------
	    //echo "*".$no."*".$array."*".$row."*".$size."*".$mb."<br>\n";
	}
    }
    return( $result );
}


function printmissingtable( $diskarray, $passive)
{
    $selectedcontroller = GetIntFromRequest("selectedcontroller");
    $encl = GetIntFromRequest("encl");
    $slot = GetIntFromRequest("slot");
    $array=GetIntFromRequest("array");
    $row=GetIntFromRequest("row");

    printf("<br><br><div align=\"center\">");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#008800\" >");

    printf("<tr><td align=\"center\" colspan=\"4\">");
    printf("<font color=\"#FFFFFF\"><b>");
    printf("List Of Missing Drives");
    printf("</font></td></tr>");

    printf("<tr bgcolor=\"#FFFFAA\">");

    printf("<td> </td>");

    printf("<td align=\"center\">");
    printf("<font color=\"#0000FF\"><b>");
    printf("Array");
    printf("</font></td>");

    printf("<td align=\"center\">");
    printf("<font color=\"#0000FF\"><b>");
    printf("Row");
    printf("</font></td>");


    printf("<td>");
    printf("<font color=\"#0000FF\"><b>");
    printf("Expected Size");
    printf("</font></td>");
    printf("</tr>");

    for ( $i = 0; $i < count($diskarray); $i++ )
    {
	printf("<tr bgcolor=\"#FFFFAA\">");

	printf("<td align=\"center\" width=\"50px\" >");
	printf("<font color=\"#000000\"><b>");
	$checked="";
	if ( $i == $checkedno )
	    $checked = "checked";
	if ( $passive == false )
	    printf("<input type=\"radio\" \"" . $checked . "\" onChange=\"onSelectMissingDrive($selectedcontroller, $encl, $slot," . $diskarray[$i]->array . "," . $diskarray[$i]->row . "," . $i . ")\" >");

	printf("</font>");
	printf("</td>");

	printf("<td align=\"center\" width=\"50px\">");
	printf("<font color=\"#000000\"><b>");
	echo $diskarray[$i]->array;
	printf("</font>");
	printf("</td>");

	printf("<td align=\"center\" width=\"50px\">");
	printf("<font color=\"#000000\"><b>");
	echo $diskarray[$i]->row;
	printf("</font>");
	printf("</td>");

	printf("<td>");
	printf("<font color=\"#000000\"><b>");
	echo $diskarray[$i]->size . " " . $diskarray[$i]->sizestr;
	printf("</font>");
	printf("</td>");
/*
	printf("<td>");
	printf("<font color=\"#FFFF00\"><b>");
	echo $diskarray[$i]->sizestr;
	printf("</font>");
	printf("</td>");
*/
	printf("</tr>");
    }

    if ( $passive == false )
    {
	//    echo "<tr bgcolor=\"#FFFFAA\"><td colspan=\"4\"><HR></td></tr>\n";
        echo "<tr bgcolor=\"#FFFFAA\"><td colspan=\"4\">\n";

	echo "<form action=\"replacemissing.php\">\n";
	echo "<br>";
	echo "<button value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($selectedcontroller, $encl, $slot, $array, $row)\" >\n";
	echo " Replace Disk </button>\n";
	echo "</form><br></td>\n";
	echo "</tr>\n";
    }


    printf("</table></div>");

}


?>
