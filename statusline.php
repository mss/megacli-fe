<?php
include ("globalfunc.inc");

$crc32sum = 0;

function getSummary($controller)
{
    global $MEGACLI;
    $result = Array();
    //---получаем список ShowSummary
    $cmd = $MEGACLI . " -ShowSummary -a" . $controller . " -NoLog";
    exec($cmd, $result, $errcode);
    return $result;
}


function getAlarmStatus($controller)
{
    global $MEGACLI;
    //получить текущий статус от контроллера
    $cmd = $MEGACLI . " -AdpGetProp AlarmDsply -a".$controller." -NoLog";
    exec($cmd, $outarray, $errcode);
//    if ($errcode > 0)
//	MegaCliError($outarray);
    $result = GetStrValue($outarray, "Adapter ".$controller); 
    return $result;
}


function main()
{
    global $crc32sum;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");

//    $crc32sum = main2crc32(true);
    //echo "src32sum=".$crc32sum." req=".$_SERVER['QUERY_STRING'];
    $summaryarray = getSummary($selectedcontroller);
    $status = GetStrValue($summaryarray, "Status"); //статус контроллера
    $productname = GetStrValue($summaryarray, "ProductName"); //имя контроллера
    $alarmstatus = getAlarmStatus($selectedcontroller); //старус аларма
    if ( ($status == "Optimal") && ($alarmstatus == "Alarm Status is Enabled") )
    {
	echo "<div style=\"width:100%;height:100%;background-color:#AAFFAA;text-align:left\">";
	echo "<b>Controller $selectedcontroller Status is ". $status . " " . $alarmstatus . " !</b>";
	echo "</div>";
    }
    else
    {
	echo "<div style=\"width:100%;height:100%;background-color:#FF5555;text-align:left\">";

//	echo "<marquee vspace=\"0\" width=\"100%\" height=\"100%\" align=\"center\"  bgcolor=\"#FF5555\" >";
	echo "<b><font color=\"#FFFF88\">WARNING Controller Status is <blink>". $status . " " . $alarmstatus . "</blink> !</font></b>";
//	echo "</marquee>";
	echo "</div>";

    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>megacli frontend</title>
<meta http-equiv="content-type" content="text/html; charset=koi8-r">
<?php
echo "<meta http-equiv=\"REFRESH\" content=\"30;url=statusline.php?selectedcontroller=".GetIntFromRequest("selectedcontroller")."\"> ";
?>
</head>
<body style="margin:0px;font-size:8pt;font-family: sans-serif;">
<?php
main()
?>

</body>
</html>
