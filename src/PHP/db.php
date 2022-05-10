
<?php 
/*
try {
  $db = new PDO('mysql:host=localhost;dbname=winterhold;charset=utf8mb4', 'winterholdweb', '716arp91LOL');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
} catch (PDOException $e) {
  echo "Connection failed : ". $e->getMessage();
}
*/

try {
  $db = new PDO('mysql:host=localhost;dbname=winterhold_university;charset=utf8mb4', 'whweb', '716arp91LOL');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
} catch (PDOException $e) {
  echo "Connection failed : ". $e->getMessage();
}
// try {
//   $db = new PDO('mysql:host=sql102.epizy.com;dbname=epiz_31663763_winterholduniversity;charset=utf8mb4', 'epiz_31663763', '8qmYES0UIKpR7Xc');
//   $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  
// } catch (PDOException $e) {
//   echo "Connection failed : ". $e->getMessage();
// }


?>

