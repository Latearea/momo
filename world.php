
<?php
include 'koneksi.php';
$TOKEN      = "284965765:AAG9dBIEBQz02NvWQZSG4QeoIxXC_oOMe0I";
$usernamebot= "@wwjomblobot"; 
$debug = false;
 
$penghitung="0";
$admin = array(1 =>"295422432" ,2 =>"230573047" ); 

 
function request_url($method)
{
    global $TOKEN;
    return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}
 

function get_updates($offset) 
{
    $url = request_url("getUpdates")."?offset=".$offset;
        $resp = file_get_contents($url);
        $result = json_decode($resp, true);
        if ($result["ok"]==1)
            return $result["result"];
        return array();
}

function send_reply($chatid, $msgid, $text)
{
    global $debug;
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text,
        'reply_to_message_id' => $msgid   
    );
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options); 
    $result = file_get_contents(request_url('sendMessage'), false, $context);
    if ($debug) 
        print_r($result);
}
 

function create_response($text, $message)
{
    global $usernamebot;
    global $admin;
    global $penghitung;
    $hasil = '';  
    $fromid = $message["from"]["id"]; // variable penampung id user
    $chatid = $message["chat"]["id"]; // variable penampung id chat
    $pesanid= $message['message_id'];
    $pesanan= $message['text'];
    // variable penampung id message
    // variable penampung username nya user
    isset($message["from"]["username"])
        ? $chatuser = $message["from"]["username"]
        : $chatuser = '';
    
    // variable penampung nama user
    isset($message["from"]["last_name"]) 
        ? $namakedua = $message["from"]["last_name"] 
        : $namakedua = '';   
    $namauser = $message["from"]["first_name"]. ' ' .$namakedua;
    // ini saya pergunakan untuk menghapus kelebihan pesan spasi yang dikirim ke bot.
    $textur = preg_replace('/\s\s+/', ' ', $text); 
    // memecah pesan dalam 2 blok array, kita ambil yang array pertama saja
    $command = explode(' ',$textur,2); 
   
    switch ($command[0]) {
        // jika ada pesan /id, bot akan membalas dengan menyebutkan idnya user
        case '/start':
        case '/start'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Cek /aturan , /event , /role";
            

            break;

            case '/masukanevent':
            case '/masukanevent'.$usernamebot :
                $penghitung="0";
    if($penghitung<="2")
        
        while($penghitung<="2")
        {
        if($fromid=="$admin[$penghitung]")
        {
                
          if (isset($command[1])) {
              $pesanproses = $command[1];
              $tabel="grup";
              $kolom="Event";
              $r = updateRules(koneksi(), $tabel, $kolom, $pesanproses);
              $hasil = 'Event sudah diupdate';
          } else {
              $hasil = '⛔️ *ERROR:*!';
              $hasil .= "\n\nContoh: `/masukanevent blabla`";
          }
        
         }
         $penghitung=$penghitung+1;
     }
    
         else 
         	$hasil="Bukan admin bot !!!";
          break;
        case '!id':
            $hasil = " Hai $namauser! ID sanak adolah $fromid ";

            break;



        case '/id':
        case '/id'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "$namauser, ID kamu adalah $fromid ,ID grup adalah $chatid";
            break;
            
            case '/masukanaturan':
            case '/masukanaturan'.$usernamebot:
                $penghitung="0";
        if($penghitung<="2")
        
        while($penghitung<="2")
        {
        if($fromid=="$admin[$penghitung]")
        {
          if (isset($command[1])) {
              $pesanproses = $command[1];
              $tabel="grup";
              $kolom="game_rules";
              $r = updateRules(koneksi(), $tabel, $kolom, $pesanproses);
              $hasil = 'Aturan sudah diupdate!!!';
          } else {
              $hasil = '⛔️ *ERROR:*!';
              $hasil .= "\n\nContoh: `/masukanaturan blabla`";
          }
         }
         $penghitung=$penghitung+1;
     }
 
         else 
         	$hasil="Bukan admin bot !!!";
         

         

            break;
        
        case '/aturan':
        case '/aturan'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $kolom="game_rules";
          $konten=tampilRules(koneksi(), $kolom);
          $hasil = $konten;
       
            break;

            case '/event':
        	case '/event'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $kolom="Event";
          $konten=tampilRules(koneksi(), $kolom);
          $hasil = $konten;

            break;

            case '/Sorcerer':
        	case '/Sorcerer'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Sorcerer :
Seer untuk team wolf, begitu mulai langsung dikasitaw kamu itu sorcerer. Cuma bisa terawang seer dan wolf. Ga dikasitaw siapa saja wolfnya, jadi cari sendiri. Berdoa tidak dimakan wolf sebelum ketemu siapa aja mereka.
Team : Wolf
Kelebihan : Terawang-terawang khusus wolf dan seer saja
Kekurangan : Bisa dimakan ama wolf sebelum taw siapa wolfnya";

            break;

            case '/Alphawolf':
        	case '/Alphawolf'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Alpha Wolf :
Biang kerok terbaik, pemimpin masa depan bangsa werewolf dalam melawan Dracula. Jika menjadi Alphawolf, sebaiknya informasikan ke anggota team, agar semua mengikuti pilihan kamu. Punya 20% kemungkinan untuk menginfeksi yang tergigit. (note: yg terinfeksi akan jadi ww pada malam harinya. tetapi ww yg baru jadi tidak bisa makan pda malam tsb)
- Team : Werewolf
- Kelebihan : Infection (Mengubah yang tergigit menjadi WW)
- Kekurangan : Kalau diem2, sama aja boong...";

            break;

			case '/Wolf':
        	case '/Wolf'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Dasar kamu serigala! Tugas kamu adalah memakan semua warga desa bersama team kamu. Hati-hati, kamu bisa mati juga kalau memakan Hunter atau Serial Killer. Kalau jumlah werewolf bisa seimbang atau lebih banyak dari warga, maka kamu menang. Gunakan semua strategi yang ada untuk menghasut warga agar kamu menang.

- Team : Werewolf (jelas)
- Kelebihan : Nom Nom Nom!!!
- Kekurangan : Bisa mati kalau makan Hunter atau SK";

            break;

            case '/Wolfcub':
        	case '/Wolfcub'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Wolfcub :
Anak serigala yg unyu-unyu:heart_eyes_cat: terlalu cute untuk mati:sob:
- Team : Werewolf
- Kelebihan : CUTE TO DEATH (free 1 kill untuk team wolf jika kamu mati)
- Kekurangan : Tidak Ada";

            break;
            
			case '/Villager':
        	case '/Villager'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Villager :
SELAMAT, KAMU MENDAPATKAN PERAN PALING PENTING!
- Team : Villager
- Deskripsi : Mencangkul tanah (bukan kenangan) setiap hari⛏
- Kelebihan : Nggak ada.
- Kekurangan : Santapan para wuff dan SK, Bisa di convert jadi wuff/cultist";

            break;
            
			case '/Prince':
        	case '/Prince'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Prince:
Sama kaya VG biasa, tapi ga bisa dilynch. kalo misalnya wardes setuju lynch dia, botnya bakal tulis kalo dia itu prince, dan ga bisa dilynch. Kerjaannya kaya wardes, cuma ngevote doank ama nunggu dimakan atau ditembak, atau nunggu couplenya mati (kalo ga jones). Prince berguna di saat wardes tidak mau micin membunuh role berguna, karena prince tidak bisa mati, jadi bisa dilynch kapan saja dan di mana saja.
- Team : Villager
- Kelebihan : ga bisa dilynch
- Kekurangan : Hati hati dimalam hari akan di bunuh wuff/SK/hunter";

            break;

			case '/Harlot':
        	case '/Harlot'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Harlot:
Slut. Ketika malam hari, kunjungi setiap pemain. Pergi bersenang-senang dengan pemain lain. Jika Wuff ke Rumah-mu ketika kamu pergi kerumah lain. maka wuff tidak bisa memakan mu.
- Team : Villager
- Kelebihan : Bersenang senang ke rumah tiap pemain.
- Kekurangan : berhati-hati visit pemain lain bisa saja kamu visit SK/wuff, SK akan membunuh mu walaupun kamu visit pemain lain.";

            break;

			case '/Clumsy':
        	case '/Clumsy'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Clumsy Guys :
Langsung dikasitaw ama bot kalau kamu adalah clumsy guys. Waktu ngevote ada kemungkinan 50% botnya milih yang bukan kamu pilih
- Team : Villager
- Deskripsi : Maboknya ga ketolongan
- Kelebihan : Kelebihan alkohol
- Kekurangan : Random Lynch";

            break;
        
        	case '/Apprentice':
        	case '/Apprentice'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Apprentice Seer :
Bisa disebut juga sebagai appseer. Kalau seer asli sudah mati, kamu akan naik pangkat menjadi seer. Beholder maupun seer tidak akan tahu kalau kamu Appseer sampai dicek atau sebelum seer asli mati
Team : Villager
Kelebihan : Naik pangkat ketika Seer asli sudah mati (Reveal : Terawang)
Kekurangan : Seer dan BH ga taw siapa kamu sampai dicek atau seer asli mati.";

            break;

        	case '/Mason':
        	case '/Mason'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Mason :
Mason mengetahui rekannya ketika awal permainan. Mason ya bukan Remason.
- Team : Villager
- Kelebihan : Mengetahui siapa saja temen seper-Mason-anmu, dan mengetahui siapa teman mason mu yang sudah di convert jadi kutil.
- Kekurangan : Bisa di convert Wuff/Cultist.";

            break;

			case '/Detektif':
        	case '/Detektif'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Detektif :
Bisa disebut dete juga. Sama kaya seer, bisa ngeliat role orang apa. Tapi rolenya dikasitaw waktu pas lynch, jadi agak sempit waktunya buat ngasitaw orang2. Selain itu, ada kemungkinan 50% buat WW taw kalau kamu ngecek dia
- Team : VG
- Kelebihan : Reveal Identity (Terawang)
- Kekurangan : 50% WW taw waktu diterawang. Revealnya waktu lynch, kurang waktu untuk menjelaskan.";

            break;

			case '/Mayor':
        	case '/Mayor'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "
Mayor :
Pemimpin para micin. Awalnya cuma wardes biasa, itungan vote tetap 1. Tapi mendekati waktu lynch, ada tombol untuk untuk memberitahu ke semua orang kalau kamu mayor. Waktu tombol diklik, Vote diitung menjadi 2 suara. Lebih bagus dipakai di akhir2
Team : VG
Kelebihan : Double Vote
Kekurangan : Dipakai di awal sama saja bohong
";

            break;

			case '/Cultisthunter':
        	case '/Cultisthunter'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Cultist Hunter :
Dikenal sebagai CH. Tujuan hidupmu adalah membunuh semua #cultist atau dikenal juga sebagai kutil. Setiap malam, kamu memilih satu orang untuk dikunjungi. Jika orang tersebut kutil, maka dia akan mati.
- Team : VG
- Kelebihan : Jangan tawarin aku MLM! (membunuh kutil)
- Kekurangan : Kalau mati di awal, kutil berkuasa
";

            break;


			case '/Seer':
        	case '/Seer'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Seer:
Dukun, orang yang bisa mengetahui role setiap player pada malam hari. Tapi dia tidak tau siapa jodohnya. Tunggu Beholder PM biar tau kalau kamu itu Seer yg asli.
- Team : Villager
- Kelebihan : Mengetahui Role pemain
- Kekurangan : Kalau terlalu jujur cepat dimakan WW
";

            break;

			case '/Gunner':
        	case '/Gunner'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Gunner :
Billy The Kid. The mexicano yang punya 2 peluru. Bisa memilih untuk membunuh seseorang di siang hati. Cuma bisa 2 orang, jadi pilih dengan baik.
- Team : Villager
- Kelebihan : Double Shot! (2x menembak)
- Kekurangan : Nembak cwe ditolak terus, nembak cwo malah diterima";

            break;

			case '/Blacksmith':
        	case '/Blacksmith'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Blacksmith :
Taburkan bubuk Silver di siang hari untuk melindungi satu desa di malam berikutnya (HANYA berlaku untuk gigitan wolf, SK tetap bisa membunuh). Cocok digunakan setelah WolfCub mati.
- Team : Villager
- Deskripsi : Guru dari Guardian Angel, Master of Craftmanship
- Kelebihan : Perlindungan dari kematian untuk semua orang
- Kekurangan : Cuma untuk 1 malam saja";

            break;
        
			case '/Beholder':
        	case '/Beholder'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Beholder :
Biasa dibilang BH juga. Di awal permainan dia akan diberitahu siapa seer aslinya. Kalau seernya mati, maka seer lanjutannya akan diberitahu ke dia (kalau ada appseer). Biasanya Beholder bawel memberitahu warga siapa badrole setelah mendapat bisikan dari seer.
- Team : VG
- Kelebihan : Seer Reveal (tahu siapa seernya)
- Kekurangan : Tidak ada.";

            break;

			case '/Fool':
        	case '/Fool'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "The Fool :
Hanya aku manusia bodoh. Fool berharap menjadi seer. Tapi jalan mu salah. Fool bisa terawang juga, terawangan Fool tidak selalu salah, fool bisa benar tapi jarang banget. Jangan percaya sama Fool, dia bisa saja sesat.
- Team : Villager
- Kelebihan : Mengacaukan Permainan
- Kekurangan : Terawanganmu bisa saja salah dan menjerumuskan Villager ke jalan yg sesat.";

            break;



			case '/Drunk':
        	case '/Drunk'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Drunk :
Minuman keras, MIRAS. Apapun namamuu... Drunk adalah pemabuk didalam desa ini. Pemabuk sangat dibenci oleh Wuff!
- Team : Villager
- Kelebihan : Jika kamu dimakan wuff, maka wuff akan puasa.
- Kekurangan : Duitmu habis karena sering mabuk.";


            break;

			case '/Cupid':
        	case '/Cupid'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Cupid :
Makcomblang. Kerjaannya nyomblangin orang. Di awal permainan memilih 2 orang untuk menjadi pasangan. Kalau couple menang, kamu tidak ikut menang, kecuali kalau kamu jones yang mengcouple diri sendiri.
- Team : VG
- Kelebihan : Mak Comblang tingkat dewa
- Kekurangan : Ga ikut menang bareng Pasanganmu";

            break;

			case '/Hunter':
        	case '/Hunter'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Hunter :
Ketika Paranoid dan Insomnia menyerang, jadilah kamu hunter!
Ketika mati atau diserang kamu memiliki kemungkinan :
Dilynch (100% Pick)
Ditusuk SK (100% Pick)
Digigit (Banyak serigala : besar kemungkinan auto nembak, besar kemungkinan mati)
Digigit (Sedikit serigala : kecil kemungkinan nembak, ada kemungkinan bisa Pick)
- Team : Villager
- Kelebihan : Drag You TO HELL!!! (Menarik bersama menuju kematian)
- Kekurangan : Nembak siapa?";

            break;


			case '/Guardian':
        	case '/Guardian'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Guardian Angel :
Biasa disebut GA. Pelindung untuk 1 orang dari serangan WW atau SK. Ada kemungkinan mati 50% kalau melindungi WW, 100% kalau melindungi SK. Ga bisa melindungi diri sendiri.
- Team : VG
- Kelebihan : Guard Point (Safe)
- Kekurangan : Hati2 salah pilih...";

            break;            


			case '/Traitor':
        	case '/Traitor'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Traitor :
Kamu adalah pengkhianat para warga desa. Jika semua wolf terlah mati, maka kamu akan berubah menjadi wolf. #seer mempunyai kemungkinan untuk melihat kamu sebagai warga desa. Namun #dete pasti melihat kamu sebagai pengkhianat.
- Team : Villager jika belum menjadi Werewolf
- Kelebihan : 50% Disguise (dianggap sebagai warga desa)
- Kekurangan : Serigala tidak tahu kamu bisa menjadi team mereka, jadi bisa digigit.";

            break;   


			case '/Cursed':
        	case '/Cursed'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Cursed :
Kamu adalah yang terkutuk. Darahmu mengandung gen Werewolf, namun kamu belum menjadi werewolf sampai kamu digigit oleh WW.
- Team : Villager jika belum tergigit, Werewolf jika sudah tergigit
- Kelebihan : Second Chance from Bite (Berubah menjadi Werewolf)
- Kekurangan : Bisa kalah, bisa menang juga";

            break;   

			case '/Doppelganger':
        	case '/Doppelganger'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Doppelganger :
Pada malam hari disuruh buat pilih siapa yg akan digantikan. Bisa berganti peran ketika pemain yg kamu pilih itu telah mati.
- Team : Tergantung siapa yg ingin digantikan
- Kelebihan : Bisa berganti peran
- Kekurangan : Jika kamu mati sebelum pemain yg kamu pilih itu mati. Kamu akan kalah.";

            break;


			case '/Wildchild':
        	case '/Wildchild'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Wild Child:
Anak Liar yg mencari panutan. Pada malam pertama disuruh buat pilih siapa parent (orang tua).
- Team : Ketika belum transform masih team VG, kalau sudah transform jadi Team Wuff
- Kelebihan : Bisa nambah anggota wuff jika parent mu sudah mati!
- Kekurangan : Tidak ada";

            break;



			case '/Tanner':
        	case '/Tanner'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Tanner :
Tujuan utamanya adalah dilynch. Kalau berhasil, maka kamu menang. Kalau engga, ya pilih aja mau bantu Villager atau bantu Werewolf atau berubah menjadi #cultist.
- Team : Pembuat Onar Sendiri
- Kelebihan : Tidak ada
- Kekurangan : Susah mainin role ini kalau ga bisa nyetir";

            break;


			case '/Cultist':
        	case '/Cultist'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "Cultist :
Cultist jago MLM. Orang yang sangat suka membagi selembaran untuk warga desa nya, persentase yg bisa di rekrut #rekrutkutil.
- Team : Cultist
- Kelebihan : Banyak anak lebih baik
- Kekurangan : Hati-hati sama Cultist Hunter";
break;

			case '/TeamWW':
        	case '/TeamWW'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "
Team Werewolf:
/Sorcerer
/Alphawolf
/Wolf
/Wolfcub";
break;

			case '/TeamVG':
        	case '/TeamVG'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "
Team Villager:
/Villager
/Clumsy
/Apprentice
/Prince
/Harlot
/Mason
/Detektif
/Mayor
/Cultisthunter
/Seer
/Gunner
/Blacksmith
/Beholder
/Fool
/Drunk
/Cupid
/Hunter
/Guardian";
break;

			case '/Special':
        	case '/Special'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "
Role Special:
/Traitor
/Cursed
/Doppelganger
/Wildchild
/Tanner
/Cultist";
break;

			case '/role':
        	case '/role'.$usernamebot : //dipakai jika di grup yang haru ditambahkan @usernamebot
            $hasil = "
Role Role Di Game Werewolf:
/TeamVG
/TeamWW
/Special
";
break;





        default:
            $hasil = '';
            break;
        
   
    }
    
    return $hasil;
}
 
// fungsi pesan yang sekaligus mengupdate offset 
// biar tidak berulang-ulang pesan yang di dapat 
function process_message($message)
{
    $updateid = $message["update_id"];
    $message_data = $message["message"];
    if (isset($message_data["text"])) {
    $chatid = $message_data["chat"]["id"];
        $message_id = $message_data["message_id"];
        $text = $message_data["text"];
        $response = create_response($text, $message_data);
        if (!empty($response))
          send_reply($chatid, $message_id, $response);
    }
    return $updateid;
}
 
function process_one()
{
    global $debug;
    $update_id  = 0;
    echo "-";
 
    if (file_exists("last_update_id")) 
        $update_id = (int)file_get_contents("last_update_id");
 
    $updates = get_updates($update_id);
    // jika debug=0 atau debug=false, pesan ini tidak akan dimunculkan
    if ((!empty($updates)) and ($debug) )  {
        echo "\r\n===== isi diterima \r\n";
        print_r($updates);
    }
 
    foreach ($updates as $message)
    {
        echo '+';
        $update_id = process_message($message);
    }
    
    // update file id, biar pesan yang diterima tidak berulang
    file_put_contents("last_update_id", $update_id + 1);
}
// metode poll
// proses berulang-ulang
// sampai di break secara paksa
// tekan CTRL+C jika ingin berhenti 
while (true) {
    process_one();
    sleep(1);
}
// metode webhook
// secara normal, hanya bisa digunakan secara bergantian dengan polling
// aktifkan ini jika menggunakan metode webhook
/*
$entityBody = file_get_contents('php://input');
$pesanditerima = json_decode($entityBody, true);
process_message($pesanditerima);
*/
/*
 * -----------------------
 * Grup @botphp
 * Jika ada pertanyaan jangan via PM
 * langsung ke grup saja.
 * ----------------------
 
* Just ask, not asks for ask..
Sekian.
*/
    
?>
