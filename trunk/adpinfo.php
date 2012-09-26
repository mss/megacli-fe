<?php
    require 'headerhtml.inc';
    require 'globalfunc.inc';
    global $selectedcontroller;

    //$user_info=posix_getpwuid(posix_getuid());
    //echo $user_info['name'];
    main();
    //phpinfo();
?>

</body>
</html>

<?php

function main()
{
    global $MEGACLI;
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    //echo "SELECTEDCONTROLLER=".$selectedcontroller."<br>";

    $cmd = $MEGACLI . " -AdpAllInfo -a".$selectedcontroller." -NoLog";
    exec($cmd, $outarray, $errcode);
    if ($errcode > 0)
    {
        MegaCliError($outarray);
	return;
    }

    printf("<div align=\"center\">\n");
    printf("<table width=\"95%%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n");

    printf("<tr bgcolor=\"#00FF00\" align=\"center\"><td>\n");
    printf("<font color=\"#FFFFFF\"><b>\n");
    printf("Controller info\n");
    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("<tr bgcolor=\"#FFFFAA\"><td>\n");
    printf("<font color=\"#000000\"><b>\n");
    printstringarray($outarray);
    printf("</b></font>\n");
    printf("</td></tr>\n");

    printf("</table>");
    printf("</div>");

};

?>