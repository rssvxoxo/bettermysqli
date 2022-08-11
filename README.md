# bettermysqli
Inspired from Invision Community's Database Class. Developed this when I started to coding PHP, still useful tho.
<br>
Usage <br>
<br>
<?php<br>
require_once 'Db.php';<br>
$db = new Db;<br>

$variable = $db->select('column||*||array()', 'tableName', array('whereClause=?', 'where'));<br>
$db->insert('tableName', array('column1' => 'aaa', 'column2' => 'bbb'));<br>
$db->update('tableName', array('column1' => 'aaa', 'column2' => 'bbb'), array('whereClause=?', 'where'));<br>
<br>
?><br>
