<?php

define ("BGCOLOR", "#FFFFAA");


function main()
{
    global $MEGACLI;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    //echo "SELECTEDCONTROLLER=".$selectedcontroller."<br>";
    $selectedop=GetIntFromRequest("selectedop");
    $selectedldisk=GetIntFromRequest("selectedldisk");
    
    switch ($selectedop) //если не 0 то выполняем операцию
    {
	case 1: //включить CC
	    $cmd = $MEGACLI . " -LDCC -Start -L" . $selectedldisk . " -a" . $selectedcontroller . " -NoLog";
	    exec($cmd, $outarray, $errcode);
	    if ($errcode > 0)
	    {
		MegaCliError($outarray);
		return;
	    }
	break;
	
	case 2: //выключить CC
	    $cmd = $MEGACLI . " -LDCC -Stop -L" . $selectedldisk . " -a" . $selectedcontroller . " -NoLog";
	    exec($cmd, $outarray, $errcode);
	    if ($errcode > 0)
	    {
		MegaCliError($outarray);
		return;
	    }
	break;
	
	default: //ничего не делаем
    }

    $cmd = $MEGACLI . " -LDGetNum -a".$selectedcontroller." -NoLog"; //получить количестуо логических дисков
    exec($cmd, $outarray, $errcode);
    $ldcount = $errcode; //она его вернет как код возврата

    echo "<br>";
    //echo "<font class=\"smallbold\">";
    //printstringarray($outarray);
    //echo "</font>";

    
    for ( $i = 0; $i < $ldcount; $i++ )
    {
	ld($selectedcontroller, $i);
	echo "<br>";
    }
    

};


function ld($controller, $ldisk) //по одному диску
{
    global $MEGACLI;
    $cmd = $MEGACLI . " -LDInfo -L" . $ldisk . " -a" . $controller . " -NoLog"; //получить информацию по диску
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
	MegaCliError($outarray);

    printf("<div align=\"center\">");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" >");

    $name = "Virtual Drive";
    printTR( $name, GetStrValue($outarray, $name), "#0000FF" , "#FFFFFF" );
    $name = "Name";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    $name = "RAID Level";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    $name = "Size";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    $name = "State";
    $value = GetStrValue($outarray, $name);
    if ( $value == "Optimal" ) 
	printTR( $name, $value, BGCOLOR, "#00FF00");
    else
	printTR( $name, $value, BGCOLOR, "#FF0000");
    $name = "Number Of Drives";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    $name = "Span Depth";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    $name = "Current Cache Policy";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");

    $name = "Current Access Policy"; //для версии 8.04.07
    $value = GetStrValue($outarray, $name);
    if ($value=="")
    {
	$name = "Access Policy"; //для версии 8.00.48
	$value = GetStrValue($outarray, $name);
    }
    printTR( $name, $value, BGCOLOR, "#000000");

    $name = "Disk Cache Policy";
    printTR( $name, GetStrValue($outarray, $name), BGCOLOR, "#000000");
    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\"><HR></td></tr>\n";

    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\">";
    $url="ldcciframe.php?selectedcontroller=$controller&selectedldisk=$ldisk";
    echo "    <iframe src=\"$url\" name=\"ldcciframe\" height=\"20px\" width=\"100%\" frameborder=\"0\"> </iframe>";
    echo "</td></tr>";

    printButtons($controller, $ldisk);
    printf("</table>");
    printf("</div>");
}


function printTR($name, $svalue, $bgcolor, $fgcolor)
{
    echo "<tr bgcolor=\"".$bgcolor."\"><td> <font color=\"".$fgcolor."\"><b>\n";
    echo $name;
    echo "</b></font></td><td> <font color=\"".$fgcolor."\"><b>\n";
    echo $svalue;
    echo "</b></font></td></tr>\n";
}


function printButtons($controller, $ldisk)
{
    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\"><HR></td></tr>\n";

    echo "<tr bgcolor=\"".BGCOLOR."\"><td colspan=\"2\">\n";
    echo "<form action=\"ldview.php\">\n";
    echo "<button value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $ldisk, 1)\" >\n";
    echo "Start Consistency Check</button>\n";
    
//    $disabled="disabled";
//    echo "<button ".$disabled." value=\"operation2\" name=\"opbutton\" type=\"submit\" >\n";
    echo "<button value=\"XXX\" name=\"opbutton\" id=\"opbutton\" type=\"button\" onClick=\"javascript:onButton($controller, $ldisk, 2)\" >\n";
    echo "Stop Consistency Chack</button></form>\n";

    echo "<br></td></tr>\n";
}

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


<SCRIPT language="JavaScript">

function onButton(controller, ldisk, op) //вызывается при клике на радиобаттоне
{
    var  s = "ldview.php?selectedcontroller=" + controller + "&selectedldisk=" + ldisk + "&selectedop=" + op;
    this.location.href= s;
}

</SCRIPT>


<?php

include ("../globalfunc.inc");
global $selectedcontroller;

main();

?>

</body>
</html>

