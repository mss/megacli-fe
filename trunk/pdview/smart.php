<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require '../globalfunc.inc';
    global $selectedcontroller;

    main();


function main()
{
    global $SMARTCTL;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $diskid=GetIntFromRequest("diskid");

    //echo "SELECTEDCONTROLLER=".$selectedcontroller."<br>";
    global ${"DEVICE".$selectedcontroller};
    $cmd = $SMARTCTL . " -a -d megaraid," . $diskid . " " . ${"DEVICE".$selectedcontroller};
    //echo "".$cmd."<br>";
    exec($cmd, $outarray, $errcode);
    // отключено иначе ложное сообщение об ошибке
    // if ($errcode > 0)
    // {
    //     MegaCliError($outarray);
    //	   return;
    // }

    printf("<div align=\"center\">\n");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n");

    printf("<tr bgcolor=\"#00FF00\" align=\"center\"><td>\n");
    printf("<font color=\"#FFFFFF\"><b>\n");
    printf("SMART VIEW (%s)\n",$cmd);
    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("<tr bgcolor=\"#FFFFAA\"><td>\n");
    printf("<font color=\"#000000\"><b>\n");
    printf("SMART VIEW\n");
    printf("<pre>");
    printstringarray($outarray);
    printf("</pre>");
    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("</table>");
    printf("</div>");
};

?>

</body>
</html>

