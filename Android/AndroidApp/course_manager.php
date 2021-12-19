<?php

$servername = "localhost:3306";
$username = "root";
$password = "eudalius";   //posar contrasenya


     //conexió al servidor MySQL
     $link = mysqli_connect($servername, $username, $password);
     if($link === false){
         echo "failed connection";
     }

     mysqli_select_db($link, "atenea"); 
     $usrq=$_SERVER['QUERY_STRING'];            //Rep la query
     //$usrq = "timetable?subjeccct=pbe";
 

     $tableiq=explode("?", $usrq);              //Separa la query amb la taula i les restriccions
     $table=$tableiq[0];                        

     parse_str($usrq, $array);                      //
     if(sizeof($tableiq)==1&&sizeof($array)==2){
         parse_str($usrq, $array);
         $tableiq=$array;
         $table = "marks";
     }
 
     $finalq ="SELECT * FROM " . $table ;         //Inicialitzem la query que passarem a la base de dades  
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

         if(array_key_exists('hour', $query)){
             if(is_array($query['hour'])){
                 $clau = array_keys($query['hour'])[0];
                 $hora=explode(":", $query['hour'][$clau]);
                 if(sizeof($hora)!=3){
                     $query['hour'][$clau] .= ":00";
                 }

             }else{
                 $hora=explode(":", $query['hour']);
                 if(sizeof($hora)!=3){
                     $query['hour'] .= ":00";
                 }

             }
             
            
         }
         if(sizeof($query)>0){
         foreach($query as $x => $val){             //Afegim les restriccions a la query
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
     
     $finalq=str_replace("lte", " <= ", $finalq);                   
     $finalq=str_replace("lt", " < ", $finalq);
     $finalq=str_replace("gte", " >= ", $finalq);
     $finalq=str_replace("gt", " > ", $finalq);
     $finalq=str_replace("'now'", " CURRENT_DATE() ", $finalq);
 
 
     
     $resultado= mysqli_query($link ,$finalq);              //Enviem la query
     $error = array();
     $error[0] = "Not found in database";
   
     $i=0;
     $a = array();
     if($resultado!=null){
     while ($fila = mysqli_fetch_assoc($resultado)) {
        $a[$i] = $fila; 
        $i++;
     }
     echo json_encode($a);
     mysqli_free_result($resultado);
    }

     // Close connection
     
     mysqli_close($link);
 ?>