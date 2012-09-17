<?php

include ("../globalfunc.inc");
global $selectedcontroller;


function main()
{
    global $SMARTCTL;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $diskid=GetIntFromRequest("diskid");

    //echo "SELECTEDCONTROLLER=".$selectedcontroller."<br>";
    global ${"DEVICE".$selectedcontroller};
    $cmd = $SMARTCTL . " -a -d megaraid," . $diskid . " " . ${"DEVICE".$selectedcontroller};
    echo "".$cmd."";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
    {
        MegaCliError($outarray);
	return;
    }

    echo "<font class=\"smallbold\">";
    printstringarray($outarray);
    echo "</font>";
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

</body>
</html>

