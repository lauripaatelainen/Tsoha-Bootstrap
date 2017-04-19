<?php

class Kommentti extends BaseModel {

    public $id = null, $kayttaja, $julkaisu, $teksti, $aika;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('tarkista_teksti');
    }
    
    private static function lue_rivi($rivi) {
        $kayttaja = Kayttaja::hae($rivi['kayttaja']);
        $julkaisu = Kayttaja::hae($rivi['julkaisu']);
        return new Kommentti(array(
            'id' => $rivi['id'],
            'kayttaja' => $kayttaja,
            'julkaisu' => $julkaisu,
            'teksti' => $rivi['teksti'],
            'aika' => $rivi['aika']
        ));
    }

    public static function haeJulkaisulla($julkaisu) {
        $kysely = DB::connection()->prepare('SELECT id, kayttaja, julkaisu, teksti, aika FROM Kommentti WHERE julkaisu = :julkaisu ORDER BY aika ASC');
        $kysely->bindValue(':julkaisu', $julkaisu->id);
        $kysely->execute();
        $rivit = $kysely->fetchAll();

        $kommentit = array();
        foreach ($rivit as $rivi) {
            $kommentit[] = self::lue_rivi($rivi);
        }

        return $kommentit;
    }

    public static function hae($id) {
        $kysely = DB::connection()->prepare('SELECT id, kayttaja, julkaisu, teksti, aika FROM Kommentti WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            return lue_rivi($rivi);
        } else {
            return null;
        }
    }

    public function tallenna_uusi() {
        $kysely = DB::connection()->prepare('INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES(:kayttaja, :julkaisu, :teksti, :aika) RETURNING id');
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        $kysely->bindValue(':julkaisu', $this->julkaisu->id);
        $kysely->bindValue(':teksti', $this->teksti);
        $kysely->bindValue(':aika', $this->aika);
        $kysely->execute();

        $row = $kysely->fetch();
        $this->id = $row['id'];
    }

    public function tallenna_vanha() {
        $kysely = DB::connection()->prepare('UPDATE Kommentti SET kayttaja = :kayttaja, julkaisu = :julkaisu, teksti = :teksti, aika = :aika WHERE id = :id');
        $kysely->bindValue(':id', $this->id);
        $kysely->bindValue(':teksti', $this->teksti);
        $kysely->bindValue(':aika', $this->aika);
        $kysely->bindValue(':kayttaja', $this->kayttaja->id);
        $kysely->bindValue(':julkaisu', $this->julkaisu->id);
        $kysely->execute();
    }

    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Kommentti WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }
    
    public function tarkista_teksti() {
        $errors = array();
        if (trim($this->teksti) == '') {
            array_push($errors, "Teksti on tyhjä");
        }
        return $errors;
    }
}
