<?php

$servername = "localhost:3306";
$username = "root";

$password = "eudalius";   //posar ontrasenya


     //conexió al servidor MySQL
     $link = mysqli_connect($servername, $username, $password);
     if($link === false){
         //die("ERROR: Could not connect. " . mysqli_connect_error());
         echo "failed connection";
     }
     mysqli_select_db($link, atenea);
 
     $usrq=$_SERVER['QUERY_STRING'];
 
     //$usrq="marks?mark[gt]=5&student_id=2DE8AAF6";
     $tableiq=explode("?", $usrq);
     $table=$tableiq[0];
     //echo $table;
     parse_str($usrq, $array);
     if(sizeof($tableiq)==1&&sizeof($array)==2){
         //echo "entra\n";
         parse_str($usrq, $array);
         $tableiq=$array;
         $table = "marks";
     }
 
     //echo $usrq . "\n";
     //parse_str($tableiq[1],$query);
     //$finalq.="SELECT * FROM ". $table . " WHERE " . array_keys($query)[0] . " " . array_keys($query[array_keys($query)[0]])[0] . " " . $query[array_keys($query)[0]][array_keys($query[array_keys($query)[0]])[0]];
     $finalq ="SELECT * FROM " . $table ;
     $cadena="";
     $limit="";
     $orderby="";
 
     if(sizeof($tableiq)>1){
         parse_str($tableiq[1], $query);
         $i=1;
         if(array_key_exists('limit', $query)){      //Si hi ha limit a la query ho afegeix apart del where
             $limit.=" LIMIT " . $query['limit'];    
             unset($query["limit"]); 
           }
         /*elseif(array_key_exists('order_by', $query)){
             $orderby=$query['order_by'];            //Si hi ha order_by a la query ho afegeix apart
             $orderby.=" ORDER BY " . $query['order_by'];
             unset($query["order_by"]);
         }*/
         if(array_key_exists('hour', $query)){
             if(is_array($query['hour'])){
                 $clau = array_keys($query['hour'])[0];
                 $hora=explode(":", $query['hour'][$clau]);
                 if(sizeof($hora)!=3){
                     $query['hour'][$clau] .= ":00";
                 }
                 //$query['hour'][$clau] = "'" . $query['hour'][$clau] . "'";
             }else{
                 $hora=explode(":", $query['hour']);
                 if(sizeof($hora)!=3){
                     $query['hour'] .= ":00";
                 }
                 //$query['hour'] = "'" . $query['hour'] . ;
             }
             echo $hora . "\n";
             
             
 
            
         }
         if(sizeof($query)>0){
         foreach($query as $x => $val){
             $cadena .= strtoupper($x);
             if(is_array($val)){
                 $clau = array_keys($val)[0];
                 $cadena .= " " . $clau . " '" . $val[$clau] ."'"; 
             }
             else{
                 $cadena .= "='";
                 $cadena .= $val;
                 $cadena .= "'";
             }
             if($i<sizeof($query)){
                 $cadena .= " AND ";
                 $i++;
             }
         }
         $finalq .= " WHERE ";
         $finalq .= $cadena;
     }
         
     }
     
     if($orderby==""){                   //Afegeix l'ordre per defecte de cada taula si no n'hem posat cap mes
         switch($table){
             case 'timetable' :
                  //echo $dia = date('D');
                  $hora = date('G:i:s');
                  $orderby .= " order by field(day, 
 if(day<>DATE_FORMAT(CURRENT_DATE(), '%a') OR hour>CURRENT_TIME(), DATE_FORMAT(CURRENT_DATE(), '%a'), NULL), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), '%a'), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), '%a'), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), '%a'), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), '%a'), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), '%a'), 
 DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY), '%a'),
 if(day=DATE_FORMAT(CURRENT_DATE(), '%a') AND hour<CURRENT_TIME(), DATE_FORMAT(CURRENT_DATE(), '%a') ,NULL)), hour";
                  break;
 
             case "tasks" :
                 $orderby .= " ORDER BY date";
                 break;
             case 'marks' :
                 $orderby .= " ORDER BY subject";
                 break;      
         }
     }
     $finalq .= $orderby;
     $finalq .= $limit;
     $finalq .= ";";
     //echo $finalq . "\n";
     
     $finalq=str_replace("lte", " <= ", $finalq);
     $finalq=str_replace("lt", " < ", $finalq);
     $finalq=str_replace("gte", " >= ", $finalq);
     $finalq=str_replace("gt", " > ", $finalq);
     $finalq=str_replace("'now'", " CURRENT_DATE() ", $finalq);
 
 
     
     //echo "\n" . $finalq . "\n";
     $resultado= mysqli_query($link ,$finalq);
     if (!$resultado) {
         echo mysqli_error($link);
     }
     $i=0;
     while ($fila = mysqli_fetch_assoc($resultado)) {
         /*echo $fila["mark"] . "\n";
         echo $fila["student_id"] . "\n";
         echo $fila["subject"] . "\n";*/
         $i++;
         echo json_encode($fila) . "\n";
     }
     if($i==0){
         echo "Consulta no trobada";
     }
     mysql_free_result($resultado);
     // Close connection
     
     mysqli_close($link);
 ?>