<?php
error_reporting(0);
ini_set('display_errors', '0');
date_default_timezone_set('Asia/Jakarta');
$data_tanggal	 = date("Y-m-d");
$data_jam 		 = date("h:i:s a");
$detik 			 = date('s');
$menit 			 = date('i');
$jam   			 = date('H');
$val_form 		 = '';

    	if(isset($_POST['submit'])) {
			session_start();
			$val_form = 'hidden="hidden"';
			$user =$_POST['username'];
			$pass =$_POST['password'];
			$token =$_POST['token'];
			$auto_timer =$_POST['auto_timer'];
			$max_status =$_POST['max_status'];

			$_SESSION['user'] = $_POST['username'];
			$_SESSION['pass'] = $_POST['password'];
			$_SESSION['token'] = $_POST['token'];
				switch($max_status)
				{
				case '1 Status': $max_s='1'; break;
				case '2 Status': $max_s='2'; break;
				case '3 Status': $max_s='3'; break;
				case '4 Status': $max_s='4'; break;
				case '5 Status': $max_s='5'; break;
				case '10 Status': $max_s='10'; break;
				case '15 Status': $max_s='15'; break;
				case '20 Status': $max_s='20'; break;
				case '25 Status': $max_s='25'; break;
				case '30 Status': $max_s='30'; break;
				case '40 Status': $max_s='40'; break;
				}
			$_SESSION['max_status'] = $max_s;
				switch($auto_timer)
				{
				case '5 Menit': $auto_t='300000'; break;
				case '6 Menit': $auto_t='360000'; break;
				case '7 Menit': $auto_t='420000'; break;
				case '8 Menit': $auto_t='480000'; break;
				case '9 Menit': $auto_t='540000'; break;
				case '10 Menit': $auto_t='600000'; break;
				}
/////////////////////////////////////////////////////////////////////////////
// Ambil nama dan foto
////////////////////////////////////////////////////////////////////////////
		$arrContextOptions=array(
		"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,),
		); 
		$get_my_picture		= 'https://graph.facebook.com/me?fields=picture,name&access_token='.$token;
		$get_my_picture 	= file_get_contents($get_my_picture, false, stream_context_create($arrContextOptions));
		$get_my_picture 	= json_decode($get_my_picture, true);
		$idsaya				= $get_my_picture['id'];
		$namasaya			= $get_my_picture['name'];
		$url_image			= 'https://graph.facebook.com/'.$idsaya.'/picture?type=normal';
		
		echo "
<center>
<script type='text/javascript'>
    var detik = $detik;
    var menit = $menit;
    var jam   = $jam;
    function clock()
    {
        if (detik!=0 && detik%60==0) {
            menit++;
            detik=0;
        }
        second = detik;
        
        if (menit!=0 && menit%60==0) {
            jam++;
            menit=0;
        }
        minute = menit;
        
        if (jam!=0 && jam%24==0) {
            jam=0;
        }
        hour = jam;
        
        if (detik<10){
            second='0'+detik;
        }
        if (menit<10){
            minute='0'+menit;
        }
        
        if (jam<10){
            hour='0'+jam;
        }
        waktu = hour+':'+minute+':'+second;
        
        document.getElementById('clock').innerHTML = waktu;
        detik++;
    }
    setInterval(clock,1000);
</script>
<pre>
<img src='$url_image'> \n
$namasaya \n
<label id='clock'></label> \n
:--- Configuration Info ---: \n
Running from $data_tanggal $data_jam \n
Max $max_status \n
Timer $auto_timer \n
<a href='index.php'> Logout </a>
</pre>
<script type='text/javascript'>
var auto_refresh = setInterval(
function () {
$('#load_content').load('lib/libfacebook.php').fadeIn('slow');
}, $auto_t); // refresh setiap milliseconds
</script>
";
}
else
{
			session_start();
		if (isset($_SESSION['user'])) {
			session_destroy();
			echo"
			<script>
				window.alert('Silahkan Login dulu !');
			</script>
			";
	}
}
?>
<style>
#input_form {
    background: #transparent;
    border: 1px solid #ccc;
    margin: auto;
    width: 330px;
    padding: 6px;
    border-radius: 3px;
    border-bottom: 4px solid #444;
}

.texbox {
    height: 30px;
    border: 1px solid #ccc;
}
</style>
<br>
<div id="input_form" <?php echo $val_form;?>>
<h2 align="center">Silahkan Isi Data Facebook</h2>
<form action="" method="POST">
<table>
 <tr><td>Username</td><td><input type="text" name="username" id="username" class="texbox" required="required" size="28px" value="" <?php echo $val_form;?>></td></tr>
 <tr><td>Password</td><td><input type="password" name="password" id="password" class="texbox" required="required" size="28px" value="" <?php echo $val_form;?>></td></tr>
<tr><td></tr></td>
 <tr><td>Token </td><td><textarea cols="30" rows="3" name="token" class="texarea" id="token" required="required" size="15px" placeholder="Masukkan Token Facebook Anda" <?php echo $val_form;?>></textarea></td></tr>
 <tr><td>Max Stat<td><select name="max_status" id="max_status" class="texbox" required="required" <?php echo $val_form;?>>
 <option>1 Status</option>
 <option>2 Status</option>
 <option>3 Status</option>
 <option>4 Status</option>
 <option>5 Status</option>
 <option>10 Status</option>
 <option>15 Status</option>
 <option>20 Status</option>
 <option>25 Status</option>
 <option>30 Status</option>
 <option>40 Status</option>
 <tr><td>Timer<td><select name="auto_timer" id="auto_timer" class="texbox" required="required" <?php echo $val_form;?>>
 <option>5 Menit</option>
 <option>6 Menit</option>
 <option>7 Menit</option>
 <option>8 Menit</option>
 <option>9 Menit</option>
 <option>10 Menit</option>
<tr><td></tr></td>
<tr><td></tr></td>
 <tr><td colspan="2" align="center"><input type="submit" name="submit" value="Mulai"<?php echo $val_form;?>><input type="reset" name="reset" value="Bersihkan"<?php echo $val_form;?>></td></tr>
<tr><td></tr></td>
<tr><td></tr></td>
<tr><td colspan="2" align="center"><a href='https://goo.gl/SYKZOg'>Generate token</a></tr></td>
 </table>
</form>
</div>