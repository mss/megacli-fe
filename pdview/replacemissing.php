<?php

include ("pdview.inc");
global $selectedcontroller;


function main()
{
    global $MEGACLI;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $encl=GetIntFromRequest("encl");
    $slot=GetIntFromRequest("slot");
    $array=GetIntFromRequest("array");
    $row=GetIntFromRequest("row");
    $checkedno=GetIntFromRequest("checkedno");
    $diskid=GetIntFromRequest("diskid");
    //---получаем список миссинг дисков
    $cmd = $MEGACLI . " -PDGetMissing -a" . $selectedcontroller . " -NoLog";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
    {
	MegaCliError($outarray);
	return;
    }
    //---парсим вывод
    $diskarray = parsemissing( $outarray );
    //---выводим таблицу
    printmissingtable($diskarray, false);
/*
    printf("<br><br><div align=\"center\">");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#FF0000\" >");

    printf("<tr><td align=\"center\" colspan=\"4\">");
    printf("<font color=\"#FFFFFF\"><b>");
    printf("List Of Missing Drives<br> <hr>");
    printf("</font></td></tr>");

    printf("<tr>");

    printf("<td> </td>");

    printf("<td align=\"center\">");
    printf("<font color=\"#FFFFFF\"><b>");
    printf("Array");
    printf("</font></td>");

    printf("<td align=\"center\">");
    printf("<font color=\"#FFFFFF\"><b>");
    printf("Row");
    printf("</font></td>");


    printf("<td>");
    printf("<font color=\"#FFFFFF\"><b>");
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
	printf("</tr>");
    }


//    echo "<tr bgcolor=\"#FFFFAA\"><td colspan=\"4\"><HR></td></tr>\n";
    echo "<tr bgcolor=\"#FFFFAA\"><td colspan=\"4\">\n";

    echo "<form action=\"replacemissing.php\">\n";
    echo "<br>";
    echo "<button value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($selectedcontroller, $encl, $slot, $array, $row)\" >\n";
    echo " Replace Disk </button>\n";
    echo "</form><br></td>\n";
    echo "</tr>\n";



    printf("</table></div>");
*/
//    echo "<font class=\"smallbold\">";
//    printstringarray($outarray);
//    echo "</font>";
};

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>megacli frontend</title>
<meta http-equiv="content-type" content="text/html; charset=koi8-r">
<style type="text/css">
    @import url("../style.css");
</style>
</head>

<body>

<?php
main();
?>

<SCRIPT language="JavaScript">

function onSelectMissingDrive(controller, encl, slot, array, row, checkedno) //вызывается при клике на радиобаттоне
{
//    alert(controller + " " + encl + " " + slot + " " + array + " " + row);
    var  s = "replacemissing.php?selectedcontroller=" + controller + "&encl=" + encl + "&slot=" + slot + "&array=" + array + "&row=" + row + "&checkedno=" + checkedno;
    this.location.href= s;
}

function onButton(controller, encl, slot, array, row) //вызывается при клике на кнопке Replace
{
    var  s = "pdview.php?selectedcontroller=" + controller + "&encl=" + encl + "&slot=" + slot + "&array=" + array + "&row=" + row + "&selectedop=5";
//    alert(controller + " " + encl + " " + slot + " " + array + " " + row);
    this.location.href= s;
}


</SCRIPT>


</body>
</html>

