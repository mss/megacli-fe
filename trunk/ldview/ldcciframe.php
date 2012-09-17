<?php

include ("../globalfunc.inc");
global $selectedcontroller;

function main()
{
    global $MEGACLI;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $selectedop=GetIntFromRequest("selectedop");
    $selectedldisk=GetIntFromRequest("selectedldisk");

    $cmd = $MEGACLI . " -LDInfo -L" . $selectedldisk . " -a" . $selectedcontroller . " -NoLog"; //получить информацию по диску
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
	MegaCliError($outarray);
    $name = "Check Consistency";
    $percentdone =  GetStrValue($outarray, $name);
    if ( $percentdone != "")
        echo "<b>Check Consistency " . $percentdone . "</b>";
    $name = "Foreground Initialization";
    $percentdone =  GetStrValue($outarray, $name);
    if ( $percentdone != "")
        echo "<b>Foreground Initialization " . $percentdone . "</b>";
    $name = "Background Initialization";
    $percentdone =  GetStrValue($outarray, $name);
    if ( $percentdone != "")
        echo "<b>Background Initialization " . $percentdone . "</b>";

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
<body bgcolor="#FFFFAA">

<SCRIPT language="JavaScript">

<?php
    echo "setTimeout(\"checktimeupdate(".GetIntFromRequest("selectedcontroller")." , " . GetIntFromRequest("selectedldisk") . ")\",30000);";
?>

function checktimeupdate(controller, ldisk)
{
    s = "ldcciframe.php?selectedcontroller="+controller+"&selectedldisk="+ldisk;
    this.location.href = s;
}

</SCRIPT>

<?php
main();
?>

</body>
</html>
