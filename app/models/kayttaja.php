<?php
class Kayttaja extends BaseModel {
    public $id = null, $kayttajatunnus, $salasana, $yllapitaja = false;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public static function kaikki() {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja');
        $kysely->execute();
        $rivit = $kysely->fetchAll();
        
        $kayttajat = array();
        foreach ($rivit as $rivi) {
            $kayttajat[] = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
        }
        
        return $kayttajat;
    }
    
    public static function hae($id) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $kysely->execute(array('id' => $id));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }
    
    public static function haeKayttajatunnuksella($kayttajatunnus) {
        $kysely = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE kayttajatunnus = :kayttajatunnus LIMIT 1');
        $kysely->execute(array('kayttajatunnus' => $kayttajatunnus));
        $rivi = $kysely->fetch();
        if ($rivi) {
            $kayttaja = new Kayttaja(array(
                'id' => $rivi['id'],
                'kayttajatunnus' => $rivi['kayttajatunnus'],
                'salasana' => $rivi['salasana'],
                'yllapitaja' => $rivi['yllapitaja']
            ));
            return $kayttaja;
        } else {
            return null;
        }
    }
    
    public function tallenna() {
        if ($this->id == null) {
            $kysely = DB::connection()->prepare('INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES(:kayttajatunnus, :salasana, :yllapitaja) RETURNING id');
            $kysely->bindValue(':kayttajatunnus', $this->kayttajatunnus);
            $kysely->bindValue(':salasana', $this->salasana);
            $kysely->bindValue(':yllapitaja', $this->yllapitaja, PDO::PARAM_BOOL);
            $kysely->execute();
            
            $row = $kysely->fetch();
            $this->id = $row['id'];
        } else {
            $kysely = DB::connection()->prepare('UPDATE Kayttaja SET kayttajatunnus = :kayttajatunnus, salasana = :salasana, yllapitaja = :yllapitaja WHERE id = :id');
            $kysely->bindValue(':id', $this->id);
            $kysely->bindValue(':kayttajatunnus', $this->kayttajatunnus);
            $kysely->bindValue(':salasana', $this->salasana);
            $kysely->bindValue(':yllapitaja', $this->yllapitaja, PDO::PARAM_BOOL);
            $kysely->execute();
        }
    }
    
    public function poista() {
        $kysely = DB::connection()->prepare('DELETE FROM Kayttaja WHERE id = :id');
        $kysely->execute(array('id' => $this->id));
    }
}
