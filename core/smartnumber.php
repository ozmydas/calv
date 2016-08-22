<?
class SmartNumber{

private static $instance;


/** get instance **/

private static function get_instance(){
if( ! isset(self::$instance)):
	self::$instance = New SmartNumber();
endif;
}


/** main **/

public static function say_intostr($number=0){
self::get_instance();

if( ! is_numeric($number)):
return "NAN (not a number)";
elseif($number == 0):
return "nol";
endif;

$result = [];

//pecah jadi array
$number_array = str_split($number);
//ukur size array
$size = count($number_array);

if($size > 14):
return "OOL (out of limit)";
endif;

//proses masing-masing angka
foreach($number_array as $value):

//ubah 'satu' menjadi 'se' di beberapa bagian
$value = ($value == "1" AND $size > 1 AND in_array($size, [2,3,5,6,8,9,11,12,14])) ? "se" : $value;

//masukkan ejaan ke array hasil
array_push($result, self::$instance->saynumber($value));

// tambahkan satuan (puluh, ratus, ribu, dll)
if( ! $value == 0):
	array_push($result, self::$instance->satuan($size));
endif;

//prefix ribu, juta, milyar, trilyun di kondisi tertentu
if($value == 0 AND in_array($size, [4,7,10,13]) ):
array_push($result, self::$instance->satuan($size));
endif;

$size--;
endforeach;

//tahap akhir menampilkan hasil
return self::$instance->finishing($result);
} //end func


/** misc **/

public function saynumber($number){
switch($number):
	case 1;
		return"satu";
	break;
	case 2;
		return"dua";
	break;
	case 3;
		return"tiga";
	break;
	case 4;
		return"empat";
	break;
	case 5;
		return"lima";
	break;
	case 6;
		return"enam";
	break;
	case 7;
		return"tujuh";
	break;
	case 8;
		return"delapan";
	break;
	case 9;
		return"sembilan";
	break;
	case "se";
		return"se";
	break;
	default:
		return "";
	endswitch;
}


private function satuan($count){
switch($count):
	case 2:
		return "puluh";
	break;
	case 3:
		return "ratus";
	break;
	case 4:
		return "ribu";
	break;
	case 5:
		return "puluh";
	break;
	case 6:
		return "ratus";
	break;
	case 7:
		return "juta";
	break;
	case 8:
		return "puluh";
	break;
	case 9:
		return "ratus";
	break;
	case 10:
		return "milyar";
	break;
	case 11:
		return "puluh";
	break;
	case 12:
		return "ratus";
	break;
	case 13:
		return "trilyun";
	break;
	case 14:
		return "puluh";
	break;
	default:
		return "";
endswitch;

} //end func


private function finishing($result){
//list perubahan
$word_change = array(
"se-" => "se",
"satu-ribu" => "seribu",
"puluh-seribu" => "puluh satu ribu",
"ratus--seribu" => "ratus satu ribu",
"juta----ribu" => "juta",
"milyar----juta" => "milyar",
"trilyun----milyar" => "trilyun",
"-" => " ",
"sepuluh satu" => "sebelas",
"sepuluh dua" => "dua belas",
"sepuluh tiga" => "tiga belas",
"sepuluh empat" => "empat belas",
"sepuluh lima" => "lima belas",
"sepuluh enam" => "enam belas",
"sepuluh tujuh" => "tujuh belas",
"sepuluh delapan" => "delapan belas",
"sepuluh sembilan" => "sembilan belas"
);

//gabung array jadi kalimat utuh dan ubah beberapa pengucapan
return str_replace(array_keys($word_change), array_values($word_change), implode("-",$result));
} // end func


} //end class


/**
taufiqhisyam@2016-08-10
smartnumber / just a little part of calv mini framework
**/
