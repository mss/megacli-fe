<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require '../globalfunc.inc';
    require 'eventlog.inc';

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

