<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require 'pdview.inc';
    global $selectedcontroller;
    main();


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
};

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

