<?php
$m = new mysqli('localhost', 'root', '', '', 3306);
if ($m->connect_error) { die('MySQL Error: ' . $m->connect_error); }
echo "MySQL OK
";
$r = $m->query("SHOW DATABASES LIKE 'kuliner_review'");
if ($r && $r->num_rows > 0) {
    echo "DB kuliner_review EXISTS
";
    $m->select_db("kuliner_review");
    $t = $m->query("SHOW TABLES");
    echo "Table count: " . $t->numrows . "
";
} else {
    echo "DB kuliner_review NOT FOUND
";
}
$m->close();
