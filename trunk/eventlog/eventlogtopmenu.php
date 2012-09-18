<?php
include ("../globalfunc.inc");

define ("BGCOLOR", "#FFFFAA");
//global $request;
$request = $_SERVER['QUERY_STRING'];
$selectedbutton = GetIntFromRequest("selectedbutton");

function main()
{
//    echo "TOPMENU<br>\n";
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

<table class="a" width="100%" border="0" cellspacing="0" cellpadding="0"  bordercolor="#0000FF">
<tr>
<?php
if ( $selectedbutton == 1)
    $bgcolor = "#FF0000";
else
    $bgcolor = "#0000FF";
$name="buttonE1";
echo "<td id=\"".$name."\" onClick=\"onMouseClick('".$name."');\"  onMouseOver=\"onMouseIn('".$name."')\" onMouseOut=\"onMouseOut('".$name."')\" align = \"center\" width=\"100px\" bgcolor=\"".$bgcolor."\">";
?>
<font color="#FFFFFF">
<b>Last 64 Events</b>
</font>
</td>
<td width="5px"></td>
<?php
if ( $selectedbutton == 2)
    $bgcolor = "#FF0000";
else
    $bgcolor = "#0000FF";
$name="buttonE2";
echo "<td id=\"".$name."\" onClick=\"onMouseClick('".$name."');\"  onMouseOver=\"onMouseIn('".$name."')\" onMouseOut=\"onMouseOut('".$name."')\" align = \"center\" width=\"100px\" bgcolor=\"".$bgcolor."\">";
?>
<font color="#FFFFFF">
<b>Last 512 Events</b>
</font>
</td>


<td width="5px"></td>
<td width="70px" align = "center" bgcolor="#0000FF">
<font color="#FFFFFF">
<input type="checkbox" checked disabled id="check1"><label for="check1"><b>Info</b></label>
</font>
</td>

<td width="70px" align = "center" bgcolor="#0000FF">
<font color="#FFFFFF">
<input type="checkbox" checked disabled id="check2"><label for="check2"><b>Warning</b></label>
</font>
</td>

<td width="70px" align = "center" bgcolor="#0000FF">
<font color="#FFFFFF">
<input type="checkbox" checked disabled id="check3"><label for="check3"><b>Critical</b></label>
</font>
</td>

<td width="70px" align = "center" bgcolor="#0000FF">
<font color="#FFFFFF">
<input type="checkbox" checked disabled id="check4"><label for="check4"><b>Fatal</b></label>
</font>
</td>

<td width="5px"></td>
<td  align = "center"  bgcolor="#0000FF">
</td>
</tr>
</table>

<?php




main();
?>

<SCRIPT language="JavaScript">
<?php
echo "var request = \"$request\";\n";
echo "var selectedcontroller = " . GetIntFromRequest("selectedcontroller") . ";\n";
echo "var selectedbutton = " . GetIntFromRequest("selectedbutton") . ";\n";

?>

function onMouseClick(button) //клик на кнопке
{
    // обновляем правую панель
    var url = "eventlog.php?selectedcontroller=" + selectedcontroller;
    if ( button == 'buttonE2' )
    {
    	url = url + "&events=512";
    	selectedbutton = 2;
    }
//    if ( button == 'buttonE1' )
    else
    {
    	url = url + "&events=64";
    	selectedbutton = 1;
    }
    //перезагрузить фрейм с евентами
    parent.rightpanel.location.replace('about:blank'); //поскольку грузится долго, то 
    parent.rightpanel.document.write('<blink><b>Processing...</b></blink>'); //очищаем и выводим сообщение
    parent.rightpanel.location.replace(url);
    //перезагрузить фрейм с кнопками
    url = "eventlogtopmenu.php?selectedcontroller=" + selectedcontroller + "&selectedbutton=" + selectedbutton;
    this.location.href = url;
    //alert(button + " r=" + request);
}

function onMouseIn(elementid) //мышь внутри элемента
{
    var t = document.getElementById(elementid);
    t.bgColor='#55AA00';
}

function onMouseOut(elementid) //мышь вышла за пределы элемента элемента
{
    var t = document.getElementById(elementid);
    if ( elementid == "buttonE" + selectedbutton )
	t.bgColor='#FF0000';
    else
	t.bgColor='#0000FF';
}

</SCRIPT>


</body>
</html>

