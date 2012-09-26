<?php
    $CSSPATH = "..";
    $BODYOPT = "bgcolor=\"#FFFFAA\"";
    require '../headerhtml.inc';
    require '../globalfunc.inc';
    global $selectedcontroller;

    main();

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

<SCRIPT language="JavaScript">
    setTimeout("checktimeupdate(<?php echo GetIntFromRequest("selectedcontroller").",".GetIntFromRequest("selectedldisk");?>)",30000);

function checktimeupdate(controller, ldisk)
{
    s = "ldcciframe.php?selectedcontroller="+controller+"&selectedldisk="+ldisk;
    this.location.href = s;
}

</SCRIPT>

</body>
</html>
