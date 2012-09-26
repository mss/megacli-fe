<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require 'pdview.inc';

    $crc32sum = 0;
    main();

function main()
{
    global $crc32sum;
    $crc32sum = main2crc32(true);
    //echo "src32sum=".$crc32sum." req=".$_SERVER['QUERY_STRING'];
    echo "<form>";
    echo "<input type=\"hidden\" name=\"mydata\" value=\"$crc32sum\">";
    echo "</form>";
}

?>

</body>
</html>
