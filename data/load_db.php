<?php
/**
 * Created by PhpStorm.
 * User: bartek
 * Date: 25.08.16
 * Time: 14:49
 */
phpinfo();
exit;
$db = new PDO('sqlite3:' . realpath(__DIR__) . '/zftutorial.db');
$fh = fopen(__DIR__ . '/schema.sql', 'r');
while ($line = fread($fh, 4096)) {
    $db->exec($line);
}
fclose($fh);