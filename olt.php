<?php
include 'vars.php';
$extra = 'index.php';
$table = $_GET["olt"];
$search = $_GET["search"];
$ip = str_replace ("_", ".", $table);
$sql_ip = sprintf('%u', ip2long($ip));
$conn = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
mysql_query("SET NAMES utf8");
mysql_select_db($mysql_db);
$sql = "select * from olts where ip='$sql_ip'";
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}


?>

<h2>
<?php
if ($search == NULL) {
echo "Карточка OLT";
} else {
echo "Поиск по OLT";
}
?>
</h2>
<br/>
<br/>
<font size=4>
<table border="0"  cellpadding="8" cellspacing="13">


<?php

  while ($row=mysql_fetch_array($retval)) {


$numsfp = $row['numsfp'];
$place = $row['place'];
$ro = $row['ro'];
$last_act = $row['last_act'];
echo "$ip";

echo "<br/>";
echo $place;
echo "<br/><br/>";
include 'set_sfp.php';
}

include 'ping.php';
//Count offline ONU
$sql = "select * from onus WHERE olt=\"$sql_ip\" AND pwr=\"Offline\" OR pwr=\"0\"";
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
$num_offline = mysql_num_rows($retval);

//Count online ONU
$sql = "select * from onus WHERE olt=\"$sql_ip\" AND pwr<>\"Offline\"";
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
$num_online = mysql_num_rows($retval);

//Count all ONU
$sql = "select * from onus WHERE olt=\"$sql_ip\"";
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
$num_all = mysql_num_rows($retval);





echo "<br/>";
echo "<span style=\"font-size: 14px;\">";

if ($ping == 0) {
echo "<font color=\"#FF0000\"><b>$num_all ONU</b></font>";
} else {
echo "<font color=\"#00AA00\"><b>$num_online</b> Online</font>/<font color=\"#FF0000\"><b>$num_offline</b> Offline</font>";
}

echo "</span>";



mysql_close($conn); ?> </table> </font>
<div align="center">
<div class="top_container">
  <div class="top_box">
<div><a href="index.php?page=map_onu&olt=<?php echo $table; ?>"><img width="64" src="images/map.png"><br/>Карта Onu</a></div>
<div><a href="?page=get_snmp&olt=<?php echo $table;?>" onclick="return confirm('Опросить OLT? Время выполнения может быть более минуты')"><img width="64" src="images/radar.png"><br/>Опросить OLT</a></div>
<div><a href="?page=modolt&olt=<?php echo $table;?>"><img width="64" src="images/edit.png"><br/>Редактировать OLT</a></div>
<div><a href="?page=delolt&olt=<?php echo $table;?>" onclick="return confirm('Удалить OLT?')"><img width="64" src="images/delete.png"><br/>Удалить OLT</a></div>
  </div>
</div>
</div>

<br/>
<br/>
<div align=center>
<?php
include 'onu_sql.php';
?>
</div>
