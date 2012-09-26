<?php
    $CSSPATH="..";
    require '../headerhtml.inc';
    require '../globalfunc.inc';

    define ("BGCOLOR", "#FFFFAA");
    //global $request;
    $request = $_SERVER['QUERY_STRING'];
    $selectedbutton = GetIntFromRequest("selectedbutton");
?>

<table class="a" width="100%" border="0" cellspacing="0" cellpadding="0"  bordercolor="#0000FF">
<tr>
<?php
if ( $selectedbutton == 1)
    $bgcolor = "#FF0000";
else
    $bgcolor = "#0000FF";
$name="button1";
echo "<td id=\"".$name."\" onClick=\"onMouseClick('".$name."');\"  onMouseOver=\"onMouseIn('".$name."')\" onMouseOut=\"onMouseOut('".$name."')\" align = \"center\" width=\"100px\" bgcolor=\"".$bgcolor."\">";
?>
<font color="#FFFFFF">
<b>Standart View</b>
</font>
</td>
<td width="5px"></td>
<?php
if ( $selectedbutton == 2)
    $bgcolor = "#FF0000";
else
    $bgcolor = "#0000FF";
$name="button2";
echo "<td id=\"".$name."\" onClick=\"onMouseClick('".$name."');\"  onMouseOver=\"onMouseIn('".$name."')\" onMouseOut=\"onMouseOut('".$name."')\" align = \"center\" width=\"100px\" bgcolor=\"".$bgcolor."\">";
?>
<font color="#FFFFFF">
<b>Detail View</b>
</font>
</td>
<td width="5px"></td>
<td  align = "center"  bgcolor="#0000FF">
</td>
</tr>
</table>

<SCRIPT language="JavaScript">
<?php
echo "var request = \"$request\";\n";
echo "var selectedcontroller = " . GetIntFromRequest("selectedcontroller") . ";\n";
echo "var selectedbutton = " . GetIntFromRequest("selectedbutton") . ";\n";

?>

function onMouseClick(button) //клик на кнопке
{
    // обновляем правую панель
    var url = "pdview.php?selectedcontroller=" + selectedcontroller;
    if ( button == 'button2' )
    {
    	url = url + "&detailview=1";
    	selectedbutton = 2;
    }
    if ( button == 'button1' )
    {
    	selectedbutton = 1;
    }
    parent.rightpanel.location.href = url;
    //перезагрузить фрейм с кнопками
    url = "pdviewtopmenu.php?selectedcontroller=" + selectedcontroller + "&selectedbutton=" + selectedbutton;
    this.location.href = url;
//	alert(button + " r=" + request);
}

function onMouseIn(elementid) //мышь внутри элемента
{
    var t = document.getElementById(elementid);
    t.bgColor='#55AA00';
}

function onMouseOut(elementid) //мышь вышла за пределы элемента элемента
{
    var t = document.getElementById(elementid);
    if ( elementid == "button" + selectedbutton )
	t.bgColor='#FF0000';
    else
	t.bgColor='#0000FF';
}

</SCRIPT>


</body>
</html>

