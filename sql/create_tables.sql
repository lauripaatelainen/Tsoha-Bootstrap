CREATE TABLE Kayttaja(
    id              serial          PRIMARY KEY,
    kayttajatunnus  varchar(50)     NOT NULL,
    salasana        varchar(50)     NOT NULL,
    yllapitaja      boolean         DEFAULT FALSE NOT NULL
);


CREATE TABLE Ryhma(
    id              serial          PRIMARY KEY,
    nimi            varchar(50)     NOT NULL,
    kuvaus          text            NOT NULL DEFAULT '',
    suljettu        boolean         NOT NULL DEFAULT FALSE,
    yllapitaja      integer         REFERENCES Kayttaja(id) ON DELETE CASCADE
);

CREATE TABLE RyhmanJasenyys(
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE,
    ryhma           integer         REFERENCES Ryhma(id) ON DELETE CASCADE,
    PRIMARY KEY(kayttaja, ryhma)
);

CREATE TABLE Liittymispyynto(
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE,
    ryhma           integer         REFERENCES Ryhma(id) ON DELETE CASCADE,
    viesti          text            NOT NULL,
    PRIMARY KEY(kayttaja, ryhma)
);

CREATE TABLE Kutsu(
    ryhma           integer         REFERENCES Ryhma(id) ON DELETE CASCADE,
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE,
    viesti          text            NOT NULL,
    PRIMARY KEY(ryhma, kayttaja)
);

CREATE TABLE Julkaisu(
    id              serial          PRIMARY KEY,
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE NOT NULL,
    ryhma           integer         REFERENCES Ryhma(id) ON DELETE CASCADE,
    teksti          text            NOT NULL,
    aika            timestamp       NOT NULL
);

CREATE TABLE Tykkays(
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE,
    julkaisu        integer         REFERENCES Julkaisu(id) ON DELETE CASCADE,
    PRIMARY KEY(kayttaja, julkaisu)
);

CREATE TABLE Kommentti(
    id              serial          PRIMARY KEY,
    kayttaja        integer         REFERENCES Kayttaja(id) ON DELETE CASCADE,
    julkaisu        integer         REFERENCES Julkaisu(id) ON DELETE CASCADE,
    teksti          text            NOT NULL,
    aika            timestamp       NOT NULL
);