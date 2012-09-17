<?php
include ("pdview.inc");

$crc32sum = 0;

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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>megacli frontend</title>
<meta http-equiv="content-type" content="text/html; charset=koi8-r">
</head>

<?php
main()
?>

</body>
</html>
