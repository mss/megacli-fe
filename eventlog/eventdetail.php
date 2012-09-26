<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require '../globalfunc.inc';

$eventseqnum = GetHexFromRequest("eventseqnum");
printf("==== EVENT seqNum %X ====<br>",$eventseqnum);

$textarray = file($TMPEVENTLOG); //читаем все в массив



for ($i = 0 ; $i < count($textarray); $i++)
{

    $v = GetHexValueFromString($textarray[$i], "seqNum:");
    if ( $v == $eventseqnum )
    {
	$b = true; //эвент начался
    	//printf("%s<br>",$textarray[$i]);
    }
    else
    {
	if ( $b == true ) 
	{
	    $pos = strpos($textarray[$i], "seqNum:"); //проверяем на начало следующего
	    if (!($pos === false)) //нашли заголовок евента
		break;
    	    printf("%s<br>",$textarray[$i]);
	}
    }
}

?>

<table width="100%" border="1" cellspacing="5">
<?php
//for ($i = 0 ; $i < count($textarray); $i++)
//{
//    echo $textarray[$i] . " <br>";
//}
?>
</table>

</body>
</html>

