<?php

$servername = "localhost:3306";
$username = "root";
$archivo = fopen("pw.txt", "r");   //comentar
$password = fgets($archivo);   //posar contrasny
fclose($archivo);    //comentar

//conexió al servidor MySQL
$link = mysqli_connect($servername, $username, $password);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


//creació database course_manager
$sql = "CREATE DATABASE atenea";
if(mysqli_query($link, $sql)){
    echo "Database created successfully";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


//llista de Databases
echo "Llista de Databases\n";
$resultado = mysqli_query($link,"SHOW DATABASES");
while($fila = mysqli_fetch_assoc($resultado)){
    echo $fila['Database']."\n";
}


//selecció de la database
$hola = mysqli_select_db($link, "atenea");

/*
//eliminació de la taula 


 if ($a = mysqli_query($link, "Drop Table marks")) {
    printf("Table students marks successfully.\n");
 }
 if ($link->errno) {
    printf("Could not drop table: %s<br />", $link->error);
 }

 if ($a = mysqli_query($link, "Drop Table students")) {
    printf("Table students dropped successfully.\n");
 }
 if ($link->errno) {
    printf("Could not drop table: %s<br />", $link->error);
 }
 */
//creació d'una taula

$sql = "CREATE TABLE students(
    uid  VARCHAR(20) NOT NULL PRIMARY KEY,
    name VARCHAR(30) NOT NULL
)";
if(mysqli_query($link, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


$sql = "CREATE TABLE tasks(
    date DATE NOT NULL,
    subject VARCHAR(10) NOT NULL,
    name VARCHAR(30) NOT NULL
)";
if(mysqli_query($link, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


$sql = "CREATE TABLE timetable(
    day VARCHAR(5) NOT NULL,
    hour TIME NOT NULL,
    subject VARCHAR(10) NOT NULL,
    room VARCHAR(6) NOT NULL
)";
if(mysqli_query($link, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


$sql = "CREATE TABLE marks(
    subject VARCHAR(10) NOT NULL,
    name VARCHAR(30) NOT NULL,
    mark float NOT NULL,
    student_id VARCHAR(20) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(uid)
)";
if(mysqli_query($link, $sql)){
    echo "Table created successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}


//afegim dades a la taula

$students = "INSERT INTO students (uid, name) VALUES
('73B15E04', 'Test1'),
('D23734EC', 'Eudald Brils Creus'),
('2DE8AAF6', 'Alex Olivé Pellicer'),
('76C99A14', 'Jofre Bonillo Mesegué'),
('6634A814', 'Arnau Fite Cluet')";
if(mysqli_query($link, $students)){
    echo "\n Students filled\n.";
} else{
    echo "ERROR: Could not able to execute $students. " . mysqli_error($link);
}



$timetable = "INSERT INTO timetable (day, hour, subject, room) VALUES
/*('Tue', '08:00:00', 'TD', 'A4-105'),*/
('Tue', '10:00:00', 'PSAVC', 'A4-105'),
('Tue', '11:00:00', 'DSBM', 'A4-105'),
('Tue', '12:00:00', 'RP', 'A4-105'),
('Wed', '08:00:00', 'Lab PBE', 'C4-S10'),
('Thu', '08:00:00', 'PBE', 'A4-105'),
('Thu', '10:00:00', 'TD', 'A4-105'),
('Thu', '12:00:00', 'PSAVC', 'A4-105'),
('Fri', '08:00:00', 'RP', 'A4-105'),
('Fri', '10:00:00', 'TD', 'A4-105'),
('Fri', '11:00:00', 'DSBM', 'A4-105'),
('Mon', '08:00:00', 'Lab RP', 'D3-006'),
('Mon', '10:00:00', 'PSAVC', 'A4-105'),
('Mon', '12:00:00', 'Lab DSBM', 'D3-006')";
if(mysqli_query($link, $timetable)){
    echo "\n Students filled\n.";
} else{
    echo "ERROR: Could not able to execute $timetable. " . mysqli_error($link);
}

$marks="INSERT INTO marks (subject, name, mark, student_id) VALUES
('AST', 'Control Teoria', '0.5', 'D23734EC')";
if(mysqli_query($link, $marks)){
    echo "\n Students filled\n.";
} else{
    echo "ERROR: Could not able to execute $marks. " . mysqli_error($link);
}

$tasks = "INSERT INTO tasks (date, subject, name) VALUES
('2021-11-28', 'AST', 'PR1')
('2021-10-8', 'PBE', 'Project Plan'),
('2021-10-10', 'AST', 'PR2'),
('2021-10-22', 'PBE', 'Requirement Specifications'),
('2021-11-25', 'PBE', 'Critical Design Report'),
('2021-12-05', 'AST', 'PR3'),
('2021-12-08', 'AST', 'PR4'),
('2021-12-13', 'PBE', 'Final Report')";
mysqli_query($link, $tasks);


// Close connection
mysqli_close($link);
?>