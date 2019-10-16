<?php  
    if(isset($_POST['submit'])){
        
   
    $img = $_POST['image'];
    $folderPath = "upload/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.jpg';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);
  
   # print_r(gettype($fileName));
    $p="activate tensorflow1 & cd C:\\tensorflow1\\models\\research\\object_detection & python Object_detection_image.py C:\\xampp\\htdocs\\product\\upload\\".$fileName;
	$out=shell_exec($p);
	echo $out;
    $nout = explode("],",$out);
    
    $pnames = explode(",",trim($nout[0],"[]()"));
    $pareas = explode(",",trim($nout[1],"[]()"));
    //print_r($pnames);
    $pareas[0]=substr($pareas[0], 2);
    $l=sizeof($pareas)-1;
    $pareas[$l]=substr($pareas[$l], 0,-3);
    $pnamef=array();
        
    
    
        //echo (explode(",",$nout[0]))[0];
    $i=0;
    $pnames[0]=substr($pnames[0], 1, -1);
    for($i=1;$i<=$l;$i++){
        $pnames[$i]= substr($pnames[$i], 2, -1);
    }
    for($i=0;$i<=$l;$i++){
        if ($pnames[$i]=="dettol"){
            if((float)$pareas[$i]<0.099){
                $pnamef[$i]='Dettol_small';
            }else{$pnamef[$i]='Dettol_large';}
        }
        else if ($pnames[$i]=="rin"){
            if((float)$pareas[$i]<0.13){
                $pnamef[$i]='Rin_small';
            }else{$pnamef[$i]='Rin_large';}
        }
        else if ($pnames[$i]=="hideandseek"){
            if((float)$pareas[$i]<0.219){
                $pnamef[$i]='Hideandseek_small';
            }else{$pnamef[$i]='Hideandseek_large';}
        }
        else if ($pnames[$i]=="colgate"){
            if((float)$pareas[$i]<0.1){
                $pnamef[$i]='Colgate_small';
            }else{$pnamef[$i]='Colgate_large';}
        }
        else $pnamef[$i]= $pnames[$i];
        
    }
    //print_r($pnamef);
    $puc = array_count_values($pnamef);
    $pu = array_unique($pnamef);
    $costs=array();
    $fcosts=array();
    $conn = new mysqli('localhost', 'root', '', 'test');
    $sql = "SELECT pid,pname,cost FROM products";
    /*
    
    $c=0;
    foreach($pu as $value){
        echo "<br>";
        $result = $conn->query($sql." WHERE pname=".'"'.$value.'"');
        //echo ($sql." WHERE pname=".'"'.$value.'"');
        $row = $result->fetch_assoc();
        $fcosts[$c]=$puc[$value]*$row["cost"];
        echo "pid ".$row["pid"]." name: $value ".$puc[$value]." ".$row["cost"]." final cost".$fcosts[$c]."<br>";
        
        $c++;
    }*/
        
   }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Capture webcam image with php and jquery</title>
    <script src="jquery.min.js"></script>
    <script src="webcam.min.js"></script>
    <link rel="stylesheet" href="bootstrap.min.css"/>
    <style type="text/css">
        #results { padding:20px; border:1px solid; background:#ccc; }
        video{ max-width: 500px;max-height: 450px;}
        img{ max-width: 500px;max-height: 450px;}
    </style>
</head>
<body>
  
<div class="container">
    <h1 class="text-center">Product Classification</h1>
   
    <form method="POST" action="index.php">
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera" style="max-height:370px;z-index:99"></div>
                <br/>
                
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6">
                <div id="results">Your captured image will appear here...</div>
            </div>
            <div class="col-md-12 text-center">
                <br/>
                <input type=button value="Validate" onClick="take_snapshot()">
                <input type=submit class="btn btn-success" name="submit" value="Submit">
            </div>
        </div>
    </form>
</div>
  
<!-- Configure a few settings and attach camera -->
<script language="JavaScript">
   Webcam.set({
      width: 1280,
     height: 720,
     dest_width: 1280,
     dest_height: 720,
     image_format: 'jpeg',
     jpeg_quality: 100,
     force_flash: false
    });
    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            
        } );
    }
</script>
 <table border = '2'>
<tr>
<th>Sr.No</th>
<th>Product id</th>
<th>Name of Product</th>
<th>Quantity</th>
<th>Cost of Product</th>
<th>Final Cost of Product</th>
    
</tr>

<?php
$c=0;
    foreach($pu as $value){
        echo "<tr>";
        echo "<td>" .((int)($c)+1)."</td>";
        $result = $conn->query($sql." WHERE pname=".'"'.$value.'"');
        //echo ($sql." WHERE pname=".'"'.$value.'"');
        $row = $result->fetch_assoc();
        $fcosts[$c]=$puc[$value]*$row["cost"];
        echo "<td>".$row["pid"]."</td><td>";
        echo $value;
        echo "</td><td>".$puc[$value]."</td><td>".$row["cost"]."</td><td>".$fcosts[$c]."</td>";
        
        $c++;
    
    echo "</tr>";
}
?>

</table>
</body>
</html>