<html lang="en" >
  <?php
  $str="";?>

<head>
  <meta charset="UTF-8">
  <title>COURSE_MANAGER</title>
  
  
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans:600'>

      <link rel="stylesheet" href="./assets/css/style.css">

  
</head>

<body>
  <?php 
      include_once('./course_managerweb.php');
    
      $return="";
      $meth=@$_SERVER['REQUEST_METHOD'];
      if($meth=="POST"){
          $iniciat=false;
          $query = "students?name=" .$_POST['name'] ."&uid=" .$_POST['uid'];
          $return=course_manager($query);
          $queryfeta="";
      }else{
        $query=@$_SERVER['QUERY_STRING'];
        $error = false; 
        $error_espai=false;
        if(strpos($query, 'query') !=0){
          $iniciat=true;
          $rebut = explode("query=",$query);
          $user=urldecode($rebut[0]);
          $query=urldecode($rebut[1]);
          $queryfeta=$query;
          if(strpos($query, ' ') !=0){
              $error_espai=true;
              $error=true;
          }else{
            
            $tableiq=explode("?", $query);
            
            if($tableiq[0]=="marks"){
              $query= "marks?name=" .$user ."&" .$tableiq[1];
              
            }
            $return=course_manager($query);
          }
        
        }
        
        
      }
      if($return==1){
          $iniciat=false;
          $error=true;
        
      }elseif($return==2){
        $iniciat=true;
        $error=false;
        if($meth=="POST"){
          $user=$_POST['name'];
        }else{
          $user=$_GET['name'];
        }
          
      }elseif($return=="Error"){
        $error=true;
      } 
      elseif(!$error_espai){
        $error=false;
      }
  
  ?>
  <div class="login-wrap" <?php if($iniciat){echo "hidden";}?>>
  <div class="login-html">
    <div class="login-form">
      <form class="sign-in-htm" autocomplete="off" action="" method = "POST">
      <div class="group">
          <label for="name" class="label">Username</label>
          
          <input id="name" name="name" type="text" class="input">
        </div>
        <div class="group">
          <label for="uid" class="label">Password</label>
          <input id="uid" name="uid" type="password" class="input" data-type="password">
        </div>
        <div class="group">
          <input type="submit" class="button" value= "Sign In">
        </div>
        <label hidden <?php if(!$error){echo "hidden";}else{ echo "class=\"label_error\"";}?> >Usuari inexistent, torni-ho a provar</label>
        
        
        <div class="hr"></div>
         
      </form >
      
    </div>
  </div>
</div>
<div class="consulta" <?php if(!$iniciat){echo "hidden";}?>>
        <div class="quadre">
          <form class="logout" action="" method = "GET">
            <div>

                <label for="name" class="label_w">Welcome: </label>
                <label for="name" class="label_w2"><?php echo $user; ?></label>
            
                <input type="submit" class="button2" value= "Logout">
            </div>
            
          
          </form >
          <form class="query" action="" autocomplete="off" method = "GET">
            <div>
                <input id="query" name="<?php echo $user?>query" type="search" value="<?php echo $queryfeta;?>" class="input2">
                <input type="submit" class="buttonsend" value= "Send">
            </div>
            <label <?php if(!$error){echo "hidden";}?>  for="name" class="err_q" >Error en la query. No utilitzi el caràcter espai </label>  
          </form >
       </div>
      <?php
          $return = substr($return,0,-1);
        $data = explode("\n",$return);
        $temp = '<table>';
        $i=0;
        $temp2="";
        $temp.="<tr>";
        foreach($data as $null => $sof){
            $decod = json_decode($sof);
            $temp2.="<tr>";
            foreach($decod as $key => $value){
                if($i==0){
                    $temp .= "<th>". strtoupper($key) ."</th>";
                }
                
                $temp2 .='<td>' . $value . "</td>" ;
            }
            
            $i++;
            $temp2 .= "</tr>";
        }
        $temp.="</tr>";
        $temp .=$temp2;
        echo($temp); ?>

</div>


</body>

</html>
