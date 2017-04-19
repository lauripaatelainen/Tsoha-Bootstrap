<?php

class Kayttaja extends BaseModel {

    public $id = null, $kayttajatunnus, $salasana1, $salasana2, $yllapitaja = false;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('tarkista_kayttajatunnus', 'tarkista_salasana');
    }
    
    public static function lue_rivi($rivi) {
        return new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana1' => $rivi['salasana'],
                'salasana2' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
        ));
    }

    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT id, kayttajatunnus, salasana, yllapitaja FROM Kayttaja');
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $kayttajat = array();
        foreach ($rivit as $rivi) {
            $kayttajat[] = self::lue_rivi($rivi);
        }

        return $kayttajat;
    }

    public static function hae($id) {
        $kysely = DB::connection()->prepare('SELECT id, kayttajatunnus, salasana, yllapitaja FROM Kayttaja WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = self::lue_rivi($rivi);
            return $kayttaja;
        } else {
            return null;
        }
    }

    public static function haeKayttajatunnuksella($kayttajatunnus) {
        $kysely = DB::connection()->prepare('SELECT id, kayttajatunnus, salasana, yllapitaja FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus LIMIT 1');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = self::lue_rivi($rivi);;
            return $kayttaja;
        } else {
            return null;
        }
    }
    
    public static function haeRyhmaJasenyydella($ryhma) {
        $kysely = DB::connection()->prepare('SELECT id, kayttajatunnus, salasana, yllapitaja FROM Kayttaja INNER JOIN RyhmanJasenyys ON RyhmanJasenyys.kayttaja = Kayttaja.id WHERE RyhmanJasenyys.ryhma = :ryhma UNION SELECT Kayttaja.id, Kayttaja.kayttajatunnus, Kayttaja.salasana, Kayttaja.yllapitaja FROM Kayttaja INNER JOIN Ryhma ON Kayttaja.id = Ryhma.yllapitaja WHERE Ryhma.id = :ryhma');
        $kysely->bindValue(':ryhma', $ryhma->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        $kayttajat = array();
        foreach ($rivit as $rivi) {
            $kayttajat[] = self::lue_rivi($rivi);
        }
        return $kayttajat;
    }

    public static function autentikoi($kayttajatunnus, $salasana) {
        $kayttaja = Kayttaja::haeKayttajatunnuksella($kayttajatunnus);
        if ($kayttaja != null && $kayttaja->salasana1 == $salasana) {
            return $kayttaja;
        }

        return null;
    }

    public function tallenna_uusi() {
        $kysely = DB::connection()->prepare('INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES(:kayttajatunnus, :salasana, :yllapitaja) RETURNING id');
        $kysely->bindValue(':kayttajatunnus', $this->kayttajatunnus);
        $kysely->bindValue(':salasana', $this->salasana1);
        $kysely->bindValue(':yllapitaja', $this->yllapitaja, PDO::PARAM_BOOL);
        $kysely->execute();

        $row = $kysely->fetch();
        $this->id = $row['id'];
    }

    public function tallenna_vanha() {
        $kysely = DB::connection()->prepare('UPDATE Kayttaja SET kayttajatunnus = :kayttajatunnus, salasana = :salasana, yllapitaja = :yllapitaja WHERE id = :id');
        $kysely->bindValue(':id', $this->id);
        $kysely->bindValue(':kayttajatunnus', $this->kayttajatunnus);
        $kysely->bindValue(':salasana', $this->salasana1);
        $kysely->bindValue(':yllapitaja', $this->yllapitaja, PDO::PARAM_BOOL);
        $kysely->execute();
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Kayttaja WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }
    
    public function haeRyhmat() {
        return Ryhma::haeJasenella($this);
    }

    public function tarkista_kayttajatunnus() {
        $errors = array();

        if ($this->kayttajatunnus == '') {
            array_push($errors, 'Käyttäjätunnus ei saa olla tyhjä');
        }

        // Tarkistetaan onko käyttäjätunnus käytössä toisella käyttäjällä
        $toinen = Kayttaja::haeKayttajatunnuksella($this->kayttajatunnus);
        if ($toinen != null && $toinen->id != $this->id) {
            array_push($errors, 'Käyttäjätunnus on käytössä toisella käyttäjällä');
        }

        return $errors;
    }

    public function tarkista_salasana() {
        $errors = array();

        if ($this->salasana1 == '') {
            array_push($errors, 'Salasana ei saa olla tyhjä');
        }

        if ($this->salasana1 != $this->salasana2) {
            array_push($errors, 'Salasanat ei täsmää');
        }

        return $errors;
    }

}
