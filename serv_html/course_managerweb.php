<?php
    function course_manager($usrq){
        $servername = "localhost";
        $username = "root";
        $password = "Mmccmmcc1965";

        //conexió al servidor MySQL
        $link = mysqli_connect($servername, $username, $password);
        if($link === false){
            //die("ERROR: Could not connect. " . mysqli_connect_error());
            echo "failed connection";
        }
        mysqli_select_db($link, "course_manager");
        
        //$usrq="marks?mark[gt]=5&student_id=2DE8AAF6";
        //$userq="marks?name=Jofre Bonillo Mesegué&";
        $tableiq=explode("?", $usrq);
        $table=$tableiq[0];
        //echo $table;
        parse_str($usrq, $array);

    
        //cas $usrq="marks?&student_id=2DE8AAF6";
        if(sizeof($tableiq)==1&&sizeof($array)==2){
            //echo "entra\n";
            //parse_str($usrq, $array);
            $tableiq=$array;
            $table = "marks";
        }
        
        //echo $usrq . "\n";
        //parse_str($tableiq[1],$query);
        //$finalq.="SELECT * FROM ". $table . " WHERE " . array_keys($query)[0] . " " . array_keys($query[array_keys($query)[0]])[0] . " " . $query[array_keys($query)[0]][array_keys($query[array_keys($query)[0]])[0]];
        if($table=="marks"){

            $finalq ="SELECT subject, name, mark FROM " . $table ;
        }else{
            $finalq ="SELECT * FROM " . $table ;
        }
        
        $cadena="";
        $limit="";
        $orderby="";
       

        if(sizeof($tableiq)>1){
            parse_str($tableiq[1], $query);
            $i=1;
            if($table=="marks" && array_key_exists('name', $query)){
                $q="SELECT uid FROM students WHERE name = '" .$query['name'] ."'";
                
                $return= mysqli_query($link ,$q);
                if (!$return) {
                    echo mysqli_error($link);
                }
                
                $pswd= mysqli_fetch_assoc($return)['uid'];
               
                mysqli_free_result($return);
                unset($query["name"]);
                $cadena .= " student_id = '" .$pswd ."' ";
                

            
            }
            
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
                
                
            }
           if($cadena!=""){
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
    if(day <> DATE_FORMAT(CURRENT_DATE(), '%a') 
    OR hour > CURRENT_TIME(), DATE_FORMAT(CURRENT_DATE(), '%a'), NULL), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY), '%a'), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), '%a'), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 3 DAY), '%a'), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 4 DAY), '%a'), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 5 DAY), '%a'), 
    DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY), '%a'),
    if(day=DATE_FORMAT(CURRENT_DATE(), '%a') AND hour < CURRENT_TIME(), DATE_FORMAT(CURRENT_DATE(), '%a') ,NULL)), hour";
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
        //echo $orderby;
        //return $finalq;
        //echo $finalq . "\n";
        
        $finalq=str_replace("lte", " <= ", $finalq);
        $finalq=str_replace("lt", " < ", $finalq);
        $finalq=str_replace("gte", " >= ", $finalq);
        $finalq=str_replace("gt", " > ", $finalq);
        $finalq=str_replace("'now'", " CURRENT_DATE() ", $finalq);
        
        //return $finalq;

        $resultado= mysqli_query($link ,$finalq);
        if (!$resultado) {
            return "Error";
        }
        $i=0;
        if($table=="students"){
            
            if(!mysqli_fetch_assoc($resultado)){
                return 1;
            }elseif(sizeof($query)>1){
                return 2;
            }
        }else{
            $retstr="";
            while ($fila = mysqli_fetch_assoc($resultado)) {
                /*echo $fila["mark"] . "\n";
                echo $fila["student_id"] . "\n";
                echo $fila["subject"] . "\n";*/
                $i++;
                $retstr.=  json_encode($fila) . "\n";
                
            }
            
            return $retstr;
        }
           
    
        
        
        
        
        mysqli_free_result($resultado);
        // Close connection
        
        mysqli_close($link);

        }
    
?>