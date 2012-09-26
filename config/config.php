<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require '../globalfunc.inc';

    if (ADMINMODE=="True")
    {
	if (1 == GetIntFromRequest("saveconfig"))
	{
	    SaveConfigFile();
	}
    }

    printf("<div align=\"center\">\n");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n");

    printf("<tr bgcolor=\"#00FF00\" align=\"center\"><td>\n");
    printf("<font color=\"#FFFFFF\"><b>\n");
    printf("Web Frontend Configuration\n");
    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("<tr bgcolor=\"#FFFFAA\"><td>\n");
    printf("<font color=\"#000000\"><b>\n");

    printf("<br>MegaCli Binary Full Path: <input type=\"text\" id=\"megaclipath\" value=\" %s \" onChange=\"onChangeCfg()\">",$MEGACLI);
    if (file_exists($MEGACLI))
    {
	printf("<font color=\"#00FF00\"><b>OK</b></font>\n");
	printf(" v%s<br>\n",GetMegaCliVerStr());
    }
    else
	printf("<font color=\"#FF0000\"><b>Not Found</b></font><br>\n");
    printf("<br>smartctl Binary Full Path: <input type=\"text\" id=\"smartctlpath\" value=\" %s \" onChange=\"onChangeCfg()\">",$SMARTCTL);
    if (file_exists($SMARTCTL))
	printf("<font color=\"#00FF00\"><b>OK</b></font><br>\n");
    else
	printf("<font color=\"#FF0000\"><b>Not Found</b></font><br>\n");

    echo "<br>";

    if (ADMINMODE=="True")
    {
	echo "<br><form action=\"config.php\">\n";
        echo "<button disabled value=\"XXX\" name=\"savecfgbutton\" id=\"savecfgbutton\" type=\"button\" onClick=\"javascript:onSaveCfgButton()\" >\n";
        echo "Save Config</button>\n";
        echo "</form>\n";
    }

    //проверяем с какими правами выполняется PHP 
    $user_info=posix_getpwuid(posix_getuid());
    if ($user_info['uid']!=0)
    {
	printf("<font color=\"#FF0000\">WARNING! This php program run under nonroot uid (%s)!<br>Check suphp engine.</b></font><br>\n",$user_info['name']);
    }

    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("</table>");
    printf("</div>");
?>

<SCRIPT language="JavaScript">

function onSaveCfgButton() //вызывается при клике на кнопке
{
    var  s = "config.php?saveconfig=1";
    alert("Config Will Save");
    this.location.href= s;
}

function onChangeCfg() //вызывается при изменении любого параметра конфига
{
    document.getElementById('savecfgbutton').disabled = false;
    //alert("Value Changed");
}

</SCRIPT>


</body>
</html>

