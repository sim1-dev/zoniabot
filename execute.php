<?php

require_once("utente.php");
require_once("pianeta.php");

//$utente = new Utente("TeamBallo", "107280272");

$content = file_get_contents("php://input");
$update = json_decode($content, true);
if(!$update)
{
  exit;
}

$message = isset($update['message']) ? $update['message'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$text = trim($text);
$text = strtolower($text);
$utente = new Utente($username, $chatId);
$utente->getUtente();

function sendMessage($response) {
    global $chatId;
    $parameters = array('chat_id' => $chatId, "text" => $response);
    $parameters["method"] = "sendMessage";
    echo json_encode($parameters);
}

header("Content-Type: application/json");
$response = '';

if(strpos($text, "/start") === 0)
{
    $response = 'Malvenuto in Zonia, una brutta copia di OGame ideata da @TeamBallo! Usa /registrami per registrarti! (BETA)';
    sendMessage($response);
}

else if(strpos($text, "/registrami") === 0)
{
    $response = $utente->creaUtente();
    sendMessage($response);
}

else if(strpos($text, "/status") === 0)
{
    $invitatoda = "Invitato da: ";
    $invitatoda .= $utente->getInvitato_da()." \u{2709}";
    if($utente->getInvitato_da() == "")
    {
        $invitatoda = "";
    }
    $response = "Utente ".$utente->getNome_utente()."

    Attualmente su ".$utente->getPianeta_corrente()."

    Livello ".$utente->getLivello()." \u{1F9D1}
    Esperienza: ".$utente->getEsperienza()." \u{2B50}
    Onore: ".$utente->getOnore()." \u{1F396}
    Metallo: ".$utente->getMetallo()." \u{2699}
    Cristallo: ".$utente->getCristallo()."\u{2744}
    Deuterio: ".$utente->getDeuterio()." \u{1F9CA}
    Energia: ".$utente->getEnergia()." \u{26A1}

    Data registrazione: ".$utente->getData_iscrizione()

    .$invitatoda;
    sendMessage($response);
}

else if(strpos($text, "/viaggio") === 0)
{
    $destinazione = ucfirst(substr($text, 9));
    $idp = selectIdPianeta($destinazione);
    $pianeta = new Pianeta($idp);
    $pianeta->getPianeta();
    if($pianeta->getId_proprietario() == $utente->getId_utente())
    {
        $utente->trasferisciSu($pianeta->getNome_pianeta());
        $response = "Trasferimento su ".$destinazione." completato con successo.";
    }
    else if($pianeta->getId_pianeta() == $utente->getPianeta_corrente())
    {
        $response = "Ti trovi già su ".$destinazione."!";
    }
    else $response = "Il pianeta su cui stai cercando di trasferirti non è tuo!";
    
    sendMessage($response);
}

/*$pianeta = new Pianeta("107280272", "Betelgeuse");
echo $pianeta->creaPianeta();
echo $pianeta->creaPianeta();
//echo $pianeta->creaPianeta();
echo $utente->incrementaXP(10);*/



?>