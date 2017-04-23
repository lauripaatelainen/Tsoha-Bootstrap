<?php
class Kutsu extends BaseModel {

    public $kayttaja, $ryhma, $viesti;
    private $tallennettu = false;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    private static function lue_rivi($rivi) {
        $kayttaja = Kayttaja::hae($rivi['kayttaja']);
        $ryhma = Ryhma::hae($rivi['ryhma']);
        $kutsu = new Kutsu(array(
            'kayttaja' => $kayttaja,
            'ryhma' => $ryhma,
            'viesti' => $rivi['viesti']
        ));
        
        $kutsu->tallennettu = true;
        
        return $kutsu;
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Kutsu');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $kutsut = array();
        foreach ($rivit as $rivi) {
            $kutsut[] = self::lue_rivi($rivi);
        }

        return $kutsut;
    }
    
    public static function hae($ryhma, $kayttaja) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Kutsu WHERE ryhma = :ryhma AND kayttaja = :kayttaja LIMIT 1');
        $kysely->bindValue(':ryhma', $ryhma->id);
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        
        $rivi = $kysely->fetch();
        if ($rivi) {
            return self::lue_rivi($rivi);
        } else {
            return null;
        }
    }

    public function tallenna() {
        if ($this->tallennettu) {
            $kysely = DB::connection()->prepare('UPDATE Kutsu SET viesti = :viesti WHERE kayttaja = :kayttaja AND ryhma = :ryhma');
            $kysely->bindValue(':ryhma', $this->ryhma->id);
            $kysely->bindValue(':kayttaja', $this->kayttaja->id);
            $kysely->bindValue(':viesti', $this->viesti);
            $kysely->execute();
        } else {
            $kysely = DB::connection()->prepare('INSERT INTO Kutsu(ryhma, kayttaja, viesti) VALUES(:ryhma, :kayttaja, :viesti)');
            $kysely->bindValue(':ryhma', $this->ryhma->id);
            $kysely->bindValue(':kayttaja', $this->kayttaja->id);
            $kysely->bindValue(':viesti', $this->viesti);
            $kysely->execute();
            
            $this->tallennettu = true;
        }
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Kutsu WHERE ryhma = :ryhma AND kayttaja = :kayttaja');
        $kysely->bindValue(':ryhma', $this->ryhma->id);
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        $kysely->execute();
    }
    
    public static function haeKayttajalla($kayttaja) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Kutsu WHERE kayttaja = :kayttaja');
        $kysely->bindValue(':kayttaja', $kayttaja->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $kutsut = array();
        foreach ($rivit as $rivi) {
            $kutsut[] = self::lue_rivi($rivi);
        }

        return $kutsut;
    }
    
    public static function haeRyhmalla($ryhma) {
        $kysely = DB::connection()->prepare('SELECT kayttaja, ryhma, viesti FROM Kutsu WHERE ryhma = :ryhma');
        $kysely->bindValue(':ryhma', $ryhma->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $kutsut = array();
        foreach ($rivit as $rivi) {
            $kutsut[] = self::lue_rivi($rivi);
        }

        return $kutsut;
    }
}