<?php

require_once("utente.php");
require_once("pianeta.php");

$content = file_get_contents("php://input");
echo $content;
echo "---------------";
$update = json_decode($content, true);
echo "$update";
if(!$update)
{
  exit;
}
echo "prova2";
$utente = new Utente($username, $chatId);
$message = isset($update['message']) ? $update['message'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$text = trim($text);
$text = strtolower($text);

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
    $response = 'Malvenuto in Zonia, una brutta copia di OGame creata da @TeamBallo! Usa /registrami per registrarti! (BETA)';
}

if(strpos($text, "/registrami") === 0)
{
    global $utente;
    $response = $utente->creaUtente();
    sendMessage($response);
}

if(strpos($text, "/status") === 0)
{
    global $utente;
    sendMessage($utente);
}

/*$pianeta = new Pianeta("107280272", "Betelgeuse");
echo $pianeta->creaPianeta();
echo $pianeta->creaPianeta();
//echo $pianeta->creaPianeta();
echo $utente->incrementaXP(10);
*/

$parameters = array('chat_id' => $chatId, "text" => "mammt");
$parameters["method"] = "sendMessage";
echo json_encode($parameters);


?>
