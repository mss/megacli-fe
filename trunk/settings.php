<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>megacli frontend</title>
<meta http-equiv="content-type" content="text/html; charset=koi8-r">
<style type="text/css">
    @import url("style.css");
</style>
</head>

<body>



<SCRIPT language="JavaScript">

function onChangeAlarmMode(controller, mode) //вызывается при клике на радиобаттоне
{
	var  s = "settings.php?selectedcontroller=" + controller + "&setalarmmode=" + mode;
	this.location.href= s;
}

</SCRIPT>



<?php

include ("globalfunc.inc");

$selectedcontroller=GetIntFromRequest("selectedcontroller");
$setalarmmode=GetIntFromRequest("setalarmmode"); //если моду менять не нужно то 0

if ( $setalarmmode > 0 ) //если есть этот параметр то сначала меняем в контроллере
{
    echo "need change";
    switch($setalarmmode)
    {
	case 1:
	    $s = "AlarmDsbl";
	break;
/*
	case 2:
	    $s = "AlarmSilence";
	break;
*/
	case 3:
	    $s = "AlarmEnbl";
	break;
	default:
	
    };
    $cmd = $MEGACLI . " -AdpSetProp " . $s . " -a" . $selectedcontroller . " -NoLog";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
	MegaCliError($outarray);
    unset($outarray);
}

//получить текущий статус от контроллера
$cmd = $MEGACLI . " -AdpGetProp AlarmDsply -a".$selectedcontroller." -NoLog";
exec($cmd, $outarray, $errcode);
if ($errcode > 0)
    MegaCliError($outarray);

$alarmstatusstring = GetStrValue($outarray, "Adapter ".$selectedcontroller); 

?>
<br>
<div align="center">
<table width="100%" border="0" cellspacing="2" cellpadding="5">
<tr>
<?php
printf("<td bgcolor=\"#0000FF\">");
printf("<font color=\"#FFFFFF\">");
printf("<b>Controller %d %s</b>\n", $selectedcontroller, $alarmstatusstring);
printf("</font>");
printf("</td>");
?>
</tr>

<tr>
<td bgcolor="#FFFFAA" align="left">
<br>
<table width="150px" style="border:solid 1px gray;line-height:10pt" cellspacing="2" cellpadding="0">
<tr>
<td>
<b>Alarm is enable</b>
</td>
<td>
<?php
if ($alarmstatusstring == "Alarm Status is Enabled")
    $checked="checked";
else
    $checked="";
printf("<input type=\"radio\" onChange=\"onChangeAlarmMode($selectedcontroller, 3)\" $checked>");
?>
</td>
</tr>

<!--
<tr>
<td>
Alarm is temporary disable (silence before reboot)
</td>
<td>
<?php
printf("<input type=\"radio\" onChange=\"onChangeAlarmMode($selectedcontroller, 2)\">");
?>
</td>
</tr>
-->
<tr>
<td>
<b>Alarm is disable</b>
</td>
<td width="20px">
<?php
if ($alarmstatusstring == "Alarm Status is Disabled")
    $checked="checked";
else
    $checked="";
printf("<input type=\"radio\" onChange=\"onChangeAlarmMode($selectedcontroller, 1)\" $checked>");
?>
</td>
</tr>

</table>
<br>
</td>
</tr>
</table>
</p>
</body>
</html>

