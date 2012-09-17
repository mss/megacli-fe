<?php
//include ("../globalfunc.inc");
include ("pdview.inc");

$selectedcontroller = GetIntFromRequest("selectedcontroller");

define ("BGCOLOR", "#FFFFAA");
$crc32sum = 0; //контрольная сумма текста


function main()
{
    global $crc32sum;
    global $selectedcontroller;
    global $MEGACLI;
    //---если задан selectedop то выполняем
    $selectedop = GetIntFromRequest("selectedop");
    $encl = GetIntFromRequest("encl");
    $slot = GetIntFromRequest("slot");
    switch( $selectedop )
    {
	case 2: //Clear
	$cmd = $MEGACLI . " -PDClear -Start -PhysDrv[".$encl.":".$slot."] -a" . $selectedcontroller . " -NoLog";
	exec($cmd, $outarray, $errcode);
	echo $cmd;
        if ($errcode > 0)
	{
	    MegaCliError($outarray);
	    return;
	}
	break;

	case 3: //Rebuild
	$cmd = $MEGACLI . " -PDRbld -Start -PhysDrv[".$encl.":".$slot."] -a" . $selectedcontroller . " -NoLog";
	exec($cmd, $outarray, $errcode);
	echo $cmd;
        if ($errcode > 0)
	{
	    MegaCliError($outarray);
	    return;
	}
	break;

	case 4: //Make Good
	$cmd = $MEGACLI . " -PDMakeGood -PhysDrv[".$encl.":".$slot."] -a" . $selectedcontroller . " -NoLog";
	exec($cmd, $outarray, $errcode);
	echo $cmd;
        if ($errcode > 0)
	{
	    MegaCliError($outarray);
	    return;
	}
	break;

	case 5: //Replace Missing
	$array = GetIntFromRequest("array");
	$row = GetIntFromRequest("row");

	$cmd = $MEGACLI . " -PDReplaceMissing -PhysDrv[" . $encl . ":" . $slot . "] -array" . $array . " -row" . $row . " -a" . $selectedcontroller . " -NoLog";
	exec($cmd, $outarray, $errcode);
	echo $cmd;
        if ($errcode > 0)
	{
	    MegaCliError($outarray);
	    return;
	}
	break;

	default:
    }

    //---таблица миссинг дисков
    missingdisktable();    
    //---основной код
    $crc32sum = main2crc32(false);
}


function missingdisktable()
{
    global $selectedcontroller;
    global $MEGACLI;
    //---получаем список миссинг дисков
    $cmd = $MEGACLI . " -PDGetMissing -a" . $selectedcontroller . " -NoLog";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
    {
	MegaCliError($outarray);
	return;
    }
    //если миссинг дисков нет то ничего не делаем
    for ($i=0; $i < count($outarray); $i++)
    {
	//echo "---->".$rawarray[$i]."<br>";
	$pos = strpos($outarray[$i], "- No Missing Drive is Found.");
	if (!($pos === false)) //нашли
	    return;
    }
    //---парсим вывод
    $diskarray = parsemissing( $outarray );
    //---выводим таблицу
    printmissingtable($diskarray, true);
    echo "<br>\n";
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

<?php

$url="pdviewiframe.php?selectedcontroller=" . $selectedcontroller . "&detailview=" . GetIntFromRequest("detailview");
;
echo "    <iframe src=\"$url\" name=\"pdviewiframe\" height=\"1px\" width=\"1px\" frameborder=\"0\"> </iframe>";

main();
?>


<SCRIPT language="JavaScript">


<?php
    echo "var crc32sum=$crc32sum;\n";
    echo "setTimeout(\"smartupdate(\\\"pdview.php?selectedcontroller=". $selectedcontroller . "&detailview=" . GetIntFromRequest("detailview") . "\\\")\",30000);";
?>


function smartupdate(requeststring)
{
    //перезагружаем только iframe
    <?php
    echo "this.pdviewiframe.location.href = \"pdviewiframe.php?selectedcontroller=" . $selectedcontroller . "&detailview=" . GetIntFromRequest("detailview") . "\";";
    ?>
    //перезагружаем всю страницу суммы не совпали
    var remotecrs32 = this.pdviewiframe.document.forms[0].mydata.value;
    if ( (crc32sum  != 0) && (crc32sum != remotecrs32) )
    {
//	alert(requeststring + "  " + remotecrs32);
	this.location.href = requeststring;
    }
    else
	<?php
	echo "setTimeout(\"smartupdate(\\\"pdview.php?selectedcontroller=". $selectedcontroller . "&detailview=" . GetIntFromRequest("detailview") . "\\\")\",30000);\n";
	?>
}


function onButton(controller, encl, slot, diskid, op) //вызывается при клике на кнопке
{
//    var  s = "ldview.php?selectedcontroller=" + controller + "&selectedldisk=" + ldisk + "&selectedop=" + op;
//    this.location.href= s;
    switch (op)
    {
	case 5: //Replace missing
	    var  url = "replacemissing.php?selectedcontroller=" + controller + "&encl=" + encl + "&slot=" + slot + "&selectedop=" + op;
	    this.location.href= url;
	break;
	case 4: //Make Good
	case 3: //Rebuild
	case 2: //start Clear
	    var  url = "pdview.php?selectedcontroller=" + controller + "&encl=" + encl + "&slot=" + slot + "&selectedop=" + op;
	    this.location.href= url;
	break;
	case 1:
	case 0:
	    //alert ("view SMART button " + controller + " "  + encl+ " " + slot + " " + diskid + " " + op);
	    var  url = "smart.php?selectedcontroller=" + controller + "&diskid=" + diskid;
	    this.location.href= url;
	break;
	default:
	    alert ("???" + controller + " "  + encl+ " " + slot + " " + op);
    }
    
    
}


</SCRIPT>

</body>
</html>

