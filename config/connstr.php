<?
function getConnection() {
	$dbhost="72.47.237.215";
    $dbuser="koko_climate";
    $dbpass="2Inb48#x";
    $dbname="koko_climatecrisis";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
?>
