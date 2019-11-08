<?php

require_once("utente.php");

class Pianeta {

    private $nome_pianeta;
    private $tipo_pianeta;
    private $livello_pianeta;
    private $id_pianeta;
    private $massa;
    private $temperatura;
    private $spazio_pianeta;
    private $detriti;
    private $id_proprietario;
    protected $mySql;

    public function __construct($id_proprietario, $nome_pianeta = 'Pianeta senza nome', $tipo_pianeta = '', $livello_pianeta = 1, $massa = 0, $temperatura = "", $spazio = 0, $detriti = 0, $id_pianeta = 0) {
        $this->id_pianeta = rand(100000,999999);
        $this->id_proprietario = $id_proprietario;
        $this->nome_pianeta = $nome_pianeta;
        $this->livello_pianeta = $livello_pianeta;
        $this->tipo_pianeta = $this->assegnaTipo();
        $this->massa = $this->assegnaMassa();
        $this->temperatura = $this->assegnaTemperatura();
        $this->spazio_pianeta = $this->assegnaSpazio();
        $this->detriti = $detriti;
        $this->mySql = new mysql_db();
    }

    public function creaPianeta() {
        if($this->puoColonizzare()) {
                while($this->esisteIdPianeta()) {
                    $this->id_pianeta = rand(100000,999999);
                }
                $sql = "INSERT INTO pianeti 
                (nome_pianeta, id_proprietario, tipo_pianeta, massa, temperatura, spazio_pianeta, detriti, livello_pianeta, id_pianeta)
                VALUES
                ('$this->nome_pianeta', '$this->id_proprietario', '$this->tipo_pianeta', '$this->massa', '$this->temperatura', '$this->spazio_pianeta', '$this->detriti', '$this->livello_pianeta', '$this->id_pianeta')  
                ";
                $this->mySql->query($sql);
                $sql = "INSERT INTO strutture (id_pianeta) VALUES ('$this->id_pianeta')";
                $this->mySql->query($sql);
                $this->incrementaNumeroPianeti();
                return "Pianeta ".$this->nome_pianeta." colonizzato con successo.";
            }
        else return "Limite di pianeti raggiunto, sali di livello per colonizzarne altri.";    
    }

    public function rinominaPianeta($nuovo_nome_pianeta) {
        $sql = "UPDATE pianeti SET nome_pianeta = '$nuovo_nome_pianeta' WHERE id_pianeta = $this->id_pianeta'";
        $this->mySql->query($sql);
        return "Pianeta rinominato con successo in ".$this->nome_pianeta.".";
    }

    public function esisteIdPianeta() {
        $sql = "SELECT * FROM pianeti WHERE id_pianeta = $this->id_pianeta";
        $row = $this->mySql->fetch_array_by_id($sql);
        if($row == 0)
            return false;
        else 
            return true;
    }

    public function puoColonizzare() {
        $risultato = false;
        $sql = "SELECT * FROM utenti WHERE id_utente = $this->id_proprietario";
        $row = $this->mySql->fetch_array_by_id($sql);
        if($row["livello"] / 10 > $row["numero_pianeti"] && $row["numero_pianeti"] < 11)
            $risultato = true;
        return $risultato;    
    }

    public function incrementaNumeroPianeti() {
        $risultato = 0;
        $sql = "SELECT * FROM utenti WHERE id_utente = $this->id_proprietario";
        $row = $this->mySql->fetch_array_by_id($sql);
        $risultato = $row["numero_pianeti"] + 1;
        $sql = "UPDATE `utenti` SET 
       `numero_pianeti` = '$risultato'
        WHERE id_utente = '$this->id_proprietario'";
        $this->mySql->query($sql);
        return;
    }

    public function assegnaTipo() {
        $tipi = array('Roccioso','Gassoso','Oceanico','Radioattivo','Vulcanico','Ghiacciato', 'Desertico');
        return $tipi[rand(0,sizeof($tipi)-1)];
    }

    public function assegnaMassa() {
        return rand(75456, 250294);
    }

    public function assegnaSpazio() {
        return round($this->massa/1500);
    }

    public function assegnaTemperatura() {
        $fattore = rand(7, 51);
        $temperatura = 0;
        switch($this->tipo_pianeta) {
            case 'Roccioso':
                $temperatura = 15;
                break;             

            case 'Gassoso':
                $temperatura = 45;
                break;

            case 'Oceanico':
                $temperatura = 55;
                $fattore = rand(14, 41);
                break;

            case 'Radioattivo':
                $temperatura = 110;
                $fattore = rand(21, 71);
                break;

            case 'Vulcanico':
                $temperatura = 240;
                $fattore = rand(47, 121);
                break;
                
            case 'Ghiacciato':
                $temperatura = -175;
                $fattore = rand(17, 93);
                break;    

            case 'Desertico':
                $temperatura = 68;
                $fattore = rand(3, 21);
                break;   

            default:
                $temperatura = 10;
                break;
        }
        return ($temperatura - $fattore)." C / ".($temperatura + $fattore)." C";
    }


}

?>
