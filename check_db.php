<?php
$m = new mysqli('localhost', 'root', '', '', 3306);
if ($m->connect_error) { echo 'MySQL Error: '.$m->connect_error; exit; }
echo "MySQL OK
";
$r = $m->query("SHOW DATABASES LIKE 'kuliner_review'");
if ($r && $r->num_rows > 0) {
    echo "DB kuliner_review exists
";
    $m->select_db('kuliner_review');
    $t = $m->query('SHOW TABLES');
    echo "Tables: .$t->num_rows."
";
} else {
    echo "DB kuliner_review NOT found
";
}
$m->close();
