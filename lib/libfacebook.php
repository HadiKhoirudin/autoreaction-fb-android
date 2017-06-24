 <?php
session_start();
if (isset($_SESSION['user'])) {
    $user		= $_SESSION['user'];
    $pass		= $_SESSION['pass'];
	$token		= $_SESSION['token'];
	$max_status = $_SESSION['max_status'];
ini_set('max_execution_time', 150);

class Reaction
{
	
	function __construct()
	{
		//
	}

	////////////// [alternatif file_get_contents] ///////////////
	private function url_get_contents ($Url)
	{
		if (!function_exists('curl_init')){
			die('CURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	/////////////////////////////////////////////////////////////
	
	private function fetch_value($str, $find_start, $find_end)
	{
		$start = strpos($str, $find_start);
		if ($start === false) {
				return "";
		}
		$length = strlen($find_start);
		$end = strpos(substr($str, $start + $length), $find_end);
		return trim(substr($str, $start + $length, $end));
	}
	
	private function curl($url = '', $var = '',$echo = '',$ref = '',$header = false)
	{
		global $config, $sock;
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, $header);
		curl_setopt($curl, CURLOPT_TIMEOUT, 150);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0');
		if ($var) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $var);
		}
		curl_setopt($curl, CURLOPT_COOKIEFILE, $config['cookie_file']);
		curl_setopt($curl, CURLOPT_COOKIEJAR, $config['cookie_file']);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	public function send_reaction($user, $pass, $token, $r_male, $r_female, $max_status)
	{
		$arrContextOptions=array(
		"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,),
		);  
		$get_post	= 'https://graph.facebook.com/me/home?fields=id,from&limit='.$max_status.'&access_token='.$token;
		$get_post 	= file_get_contents($get_post, false, stream_context_create($arrContextOptions));
		$get_post 	= json_decode($get_post, true);
		
		foreach($get_post['data'] as $data)
		{
			$this->curl("https://mobile.facebook.com/login.php?refsrc=https%3A%2F%2Fm.facebook.com%2F&login_try_number=1", "lsd=AVpI36s1&version=1&ajax=0&width=0&pxr=0&gps=0&dimensions=0&m_ts=1483804348&li=qg5xWAUZXopBIK0ABg1Dtlzt&email=$user&pass=$pass&login=Masuk");
			
			$stat_id	= $data['id'];
			$post_id	= explode("_",$stat_id);
			$r_start	= 'https://mobile.facebook.com/reactions/picker/?ft_id='.$post_id[1];			
			$html 		= $this->curl($r_start);
			$html 		= str_replace('&amp;','&',$html);
			
			$gen_url 	= 'https://graph.facebook.com/'.$post_id[0].'?access_token='.$token;
			$gen_data	= file_get_contents($gen_url, false, stream_context_create($arrContextOptions));
			$gen_data 	= json_decode($gen_data, true);
			$gender 	= $gen_data['gender'];
			
			if($gender == 'female')
			{
				$jk_p		= 'Cewe';
				$r_females 	= '/ufi/reaction/?ft_ent_identifier='.$post_id[1].'&reaction_type='.$r_female;
				$r_female_e	= $this->fetch_value($html,$r_females,'" style="display:block">');
				$r_female_l	= 'https://mobile.facebook.com/ufi/reaction/?ft_ent_identifier='.$post_id[1].'&reaction_type='.$r_female. $r_female_e;
				$this->curl($r_female_l);
				$reemotf	= $r_female;
				switch ($reemotf)
				{
				case 1 :
					$reemotf_f = 'Like';
				    break;
				case 2 :
				    $reemotf_f = 'Love';
				    break;
				case 3 :
				    $reemotf_f = 'Wow';
				    break;
				case 4 :
				    $reemotf_f = 'Haha';
				    break;
				default :
				    $reemotf_f = 'Angka diluar jangkauan';
 				   break;
				}
				echo "<br><center>Status ID $post_id[1] => $jk_p => $reemotf_f \n";
			}else{
				$jk_l		= 'Cowo';
				$r_males 	= '/ufi/reaction/?ft_ent_identifier='.$post_id[1].'&reaction_type='.$r_male;
				$r_male_e	= $this->fetch_value($html,$r_males,'" style="display:block">');
				$r_male_l	= 'https://mobile.facebook.com/ufi/reaction/?ft_ent_identifier='.$post_id[1].'&reaction_type='.$r_male. $r_male_e;
				$this->curl($r_male_l);
				$reemotm	= $r_male;
				switch ($reemotm)
				{
				case 1 :
					$reemotm_m = 'Like';
				    break;
				case 2 :
				    $reemotm_m = 'Love';
				    break;
				case 3 :
				    $reemotm_m = 'Wow';
				    break;
				case 4 :
				    $reemotm_m = 'Haha';
				    break;
				default :
				    $reemotm_m = 'Angka diluar jangkauan';
 				   break;
				}
				echo "<br><center>Status ID $post_id[1] => $jk_l => $reemotm_m \n";
			}
			
			$this->curl('https://mobile.facebook.com/logout.php');
			sleep(2);
		}
		

		
	}
	
	
}

require_once('../config/emot.php');

$config['cookie_file'] = 'cookie.txt';
if (!file_exists($config['cookie_file'])) {
    $fp = @fopen($config['cookie_file'], 'w');
    @fclose($fp);
}

$reaction = new Reaction();
$reaction->send_reaction($user, $pass, $token, $r_male, $r_female, $max_status);
}
else
{
echo
"
<script>
		window.alert('Maaf anda belum login !');
</script>
";	
}