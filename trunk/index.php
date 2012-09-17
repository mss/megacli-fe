<?php
/*
 **************************************************************************
 *  This file is part of megecli-fe project.
 *
 *  megacli-fe is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  megacli-fe is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **************************************************************************
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>megacli frontend</title>
<meta http-equiv="content-type" content="text/html; charset=koi8-r">
<style type="text/css">
    @import url("style.css");
</style>
</head>

<body>


<?php
    include ("globalfunc.inc");
    global $selectedmode;
    global $selectedcontroller;

    main();
?>


<SCRIPT language="JavaScript">

function onControllerSelect() 
{
    var val;
    val = document.getElementById('ControllerSelect').value;
    //alert("onControllerSelect()" + val);
    var  s = "index.php?selectedcontroller=" + val;
    this.location.href= s;
}

</SCRIPT>


<table class="a" border="0" cellspacing="5" cellpadding="5" bordercolor="#00FF00">

    <tr>
    <td width="200px" height="100%" valign="top" align="center">
    <!-- таблица кнопок режимов -->
    <table class="a" width="100%" border="0" cellspacing="5" cellpadding="5"  bgcolor="#F5FFF5" bordercolor="#00FF00">
    <tr>
    <td align = "center" bgcolor="#0000FF">
    <!-- комбобокс выбора контроллера -->
    <FORM ACTION="111.cgi">
    <font color="#FFFF00"><b>Controller:</b> </font>
    <SELECT ID="ControllerSelect" NAME="ControllerSelect" onChange="javascript:onControllerSelect();">
    <?php
    for ($i = 0; $i < $controllercount + 0; $i++) //+10 потом убрать !!!!!!!!!!!!!!!!!!!!!!11
    {
        if ($i==$selectedcontroller) 
	    $selected="SELECTED";
	else
	    $selected="";
	echo "	<OPTION VALUE=\"$i\" $selected>".$i."\n";
    }
    ?>
    </SELECT>
    </FORM>
    </td>
    </tr>

    <tr><td><hr></td></tr>

    <tr>
    <td>
    </td>
    </tr>

    <tr>
    <?php
    echo genmodetd(0,"Controller Info");
    ?>
    </tr>

    <tr><td></td></tr>

    <tr>
    <?php
    echo genmodetd(1,"Event Log");
    ?>
    </tr>

    <tr><td></td></tr>

    <tr>
    <?php
    echo genmodetd(2,"Controller Settings");
    ?>
    </tr>

    <tr><td></td></tr>

    <tr>
    <?php
    echo genmodetd(5,"Configuration");
    ?>
    </tr>

    <tr><td></td></tr>

    <tr>
    <?php
    echo genmodetd(3,"Logical Drives");
    ?>
    </tr>
    
    <tr><td></td></tr>
    
    <tr>
    <?php
    echo genmodetd(4,"Phisical Drives");
    ?>
    </tr>

    <tr><td></td></tr>

    <tr>
    <td height="100%">
    </td>
    </tr>
    </table>

    </td>
    <td>
    <table class="a" width="100%" border="0" cellspacing="5" cellpadding="0"  bordercolor="#00FF00">
    <tr>
    <td align = "center" height="28px">
    <?php
    $url2 = ""; //для топменю
    switch($selectedmode)
    {
	case 1:
	    $url="eventlog/eventlog.php?selectedcontroller=$selectedcontroller";
	break;
	case 2:
	    $url="settings.php?selectedcontroller=$selectedcontroller";
	break;
	case 3:
	    $url="ldview/ldview.php?selectedcontroller=$selectedcontroller";
	break;
	case 4:
	    $url="pdview/pdview.php?selectedcontroller=$selectedcontroller";
	    $url2="pdview/pdviewtopmenu.php?selectedcontroller=$selectedcontroller&selectedbutton=1";
	break;
	case 5:
	    $url="config/config.php";
	break;
	case 0:
	default:
	    $url="adpinfo.php?selectedcontroller=$selectedcontroller";
    }
    if ( $url2 != "" )
	echo "    <iframe src=\"$url2\" name=\"rightpaneltopmenu\" height=\"28px\" width=\"100%\" frameborder=\"0\"> </iframe>";
    else
	echo "<div style=\"background:#0000FF;height:28px\"></div>";
	
    ?>


    </td>
    </tr>
    <tr>
    <td height="100%">
    <?php
    echo "    <iframe src=\"$url\" name=\"rightpanel\" height=\"100%\" width=\"100%\" frameborder=\"0\"> </iframe>";
    ?>
    </td>
    </tr>
    </table>

    </td>
    </tr>
    
    <tr valign="middle">
    <td colspan="2">
<hr>
    <font class="smallbold">
    <?php
/*
    exec("id", $outerray, $errcode);
    if ($errcode > 0)
	MegaCliError($outarray);
    printstringarray($outerray);
*/
    echo "<iframe src=\"statusline.php?selectedcontroller=$selectedcontroller\" scrolling=\"no\" frameborder=\"0\" width=\"100%\" height=\"20px\" name=\"statuslineframe\"></iframe>\n";
    ?>
    </font>
    </td>
    </tr>
</table>

</body>
</html>


<?php





function genmodetd($modenumber, $text) //для подсветки ячеек таблицы
{
    global $selectedcontroller;
    global $selectedmode;
    $result="";
    if ($modenumber!=$selectedmode) 
    { //подсвечивать не нужно
	$fontcolor="#000000";
	$bgcolor="#DDDDDD";
	$hrefteg1 = "<a href=\"index.php?selectedcontroller=".$selectedcontroller."&selectedmode=$modenumber\" style=\"color:$fontcolor;text-decoration: none;\" >";
	$result="<td align=\"center\"  bgcolor=\"$bgcolor\"><b><font color=\"$fontcolor\">".$hrefteg1.$text."</a></font></b></td>";
    }
    else
    { //подсвечиваем ячейку
	$fontcolor="#FFFFFF";
	$bgcolor="#FF0000";
	$result="<td align=\"center\" bgcolor=\"$bgcolor\"><b><font color=\"$fontcolor\">".$text."</font></b></td>";
    }
    return($result);
}


function main()
{
    global $controllercount;
    global $selectedcontroller;
    global $selectedmode;
    global $MEGACLI;
    
    //===== получение параметров запроса 
    $selectedcontroller=GetIntFromRequest("selectedcontroller");
    $err=False;
    $selectedmode=GetIntFromRequest("selectedmode",$err);
/*
    if ($err==True)
    {
	$selectedmode=3; //по умолчанию активируем режим "Logical Drives"
    }
*/
    $cmd = $MEGACLI." -adpCount -NoLog";
    //echo $cmd;
    exec($cmd, $outarray, $errcode);

    if ($errcode != 1)
    {
	MegaCliError($outarray);
	$selectedmode=5; //обнаружить контроллер не удалось активируем режим "Configuration"
	return;
    }

    $result = GetIntValue($outarray, "Controller Count");
    $controllercount = GetIntValue($outarray, "Controller Count");
    return;
}
?>

