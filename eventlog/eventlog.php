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

include ("../globalfunc.inc");
include ("eventlog.inc");

$selectedcontroller=GetIntFromRequest("selectedcontroller");
//echo "SELECTEDCONTROLLER=".$selectedcontroller."<br>";
$events=GetIntFromRequest("events");
//echo "events=".$events."<br>";
if ($events==0) 
    $events=64;

//----- удаляем старый лог файл 
//if ( unlink($TMPEVENTLOG) == false )
//    echo "ERROR DELETE $TMPEVENTLOG";

//----- сохраняем лог во временном каталоге
//$cmd = $MEGACLI." -AdpEventLog -GetEvents -fatal -critical -f $TMPEVENTLOG -a".$selectedcontroller." -NoLog";
$cmd = $MEGACLI." -AdpEventLog -GetLatest".$events." -f $TMPEVENTLOG -a".$selectedcontroller." -NoLog";

exec($cmd, $outarray, $errcode);

if ($errcode > 0)
    MegaCliError($outarray);
else
    $evarray = ParseEventLog($TMPEVENTLOG); //получить массив эвентов

?>

<table width="100%" border="0" cellspacing="3">

<?php
for ($i = count($evarray) - 1; $i >=0; $i--)
{
    $evarray[$i]->Print2TR();
}
?>

</table>

</body>
</html>

