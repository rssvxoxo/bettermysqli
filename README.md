# bettermysqli
Inspired from Invision Community's Database Class. Developed this when I started to coding PHP, still useful tho.

Usage 

<?php
require_once 'Db.php';
$db = new Db;

$variable = $db->select('column||*||array()', 'tableName', array('whereClause=?', 'where'));
$db->insert('tableName', array('column1' => 'aaa', 'column2' => 'bbb'));
$db->update('tableName', array('column1' => 'aaa', 'column2' => 'bbb'), array('whereClause=?', 'where'));

?>
