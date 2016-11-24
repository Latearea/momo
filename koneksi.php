<?php
$servername = "sql6.freemysqlhosting.net";
$username = "sql6146068";
$password = "ubrLtzGvpf";
$database = "sql6146068";



// Create connection
$conn = new mysqli($servername, $username, $password, $database);
function koneksi(){
 $servername = "sql6.freemysqlhosting.net";
$username = "sql6146068";
$password = "ubrLtzGvpf";
$database = "sql6146068";

  return $conn = new mysqli($servername, $username, $password, $database);
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

  function insertOrUpdate($kon, $sql){
    if ($kon->query($sql) === TRUE) {
      return "sukses bray";
    } else {
      return "Error: " . $sql . "<br>" . $kon->error;
    }
  }

  function read($kon, $sql){
    $result = $kon->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return null;
    }
    $conn->close();
  }

  function ambilAkhir($kon, $tabel){
    $sql="select * from $tabel where id in (select max(id) from masukan)";
    $data = read($kon, $sql);
    return $data;
  }

  function updateRules($kon, $tabel, $kolom, $isi){
    $sql = "update $tabel set $kolom = '$isi'";
    insertOrUpdate($kon, $sql);
  }

  function tampilRules($kon, $kolom){
    $sql="select * from grup limit 1";
    $data = read($kon, $sql);
    return $data["$kolom"];
  }
 ?>
