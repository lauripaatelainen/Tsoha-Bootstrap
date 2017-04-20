INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Kalle', 'Kalle123!', true);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Pekka', 'Pekka123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Matti', 'Matti123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Mikko', 'Mikko123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Antti', 'Antti123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Hannu', 'Hannu123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Seppo', 'Seppo123!', false);
INSERT INTO Kayttaja(kayttajatunnus, salasana, yllapitaja) VALUES('Jukka', 'Jukka123!', false);

INSERT INTO Ryhma(nimi, kuvaus, suljettu, yllapitaja) VALUES('Julkinen testiryhmä', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ut placerat nibh. Sed quis lorem in dui viverra convallis vel quis urna. Vivamus pulvinar at enim id bibendum. Nunc in semper sapien. Nulla ut elit bibendum, malesuada orci eget, ornare sem. Proin ut lorem neque. Morbi accumsan nisl nec nibh imperdiet hendrerit. Nunc egestas eleifend dolor, nec aliquam est consectetur sit amet.', false, 1); 
INSERT INTO Ryhma(nimi, kuvaus, suljettu, yllapitaja) VALUES('Suljettu testiryhmä', 'Tämä ryhmä on suljettu ja sitä ylläpitää Pekka', true, 2); 

INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(1, 2);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(2, 1);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(3, 1);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(4, 2);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(5, 1);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(6, 2);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(6, 1);
INSERT INTO RyhmanJasenyys(kayttaja, ryhma) VALUES(7, 1);

INSERT INTO Kutsu(ryhma, kutsuttu, viesti) VALUES (1, 4, 'Haluatko liittyä tähän ryhmään?');
INSERT INTO Kutsu(ryhma, kutsuttu, viesti) VALUES (2, 3, 'Tervetuloa');
INSERT INTO Kutsu(ryhma, kutsuttu, viesti) VALUES (1, 8, 'Haluatko liittyä tähän ryhmään?');
INSERT INTO Kutsu(ryhma, kutsuttu, viesti) VALUES (2, 8, 'Tervetuloa');

INSERT INTO Liittymispyynto(kayttaja, ryhma, viesti) VALUES (5, 2, 'Hei, haluaisin liittyä tähän ryhmään');
INSERT INTO Liittymispyynto(kayttaja, ryhma, viesti) VALUES (7, 2, 'Pääsenkö mukaan?');

INSERT INTO Julkaisu(kayttaja, ryhma, teksti, aika) VALUES (1, NULL, 'Hyvää huomenta kaikille!', '2017-03-20 8:07');
INSERT INTO Julkaisu(kayttaja, ryhma, teksti, aika) VALUES (2, NULL, 'Terve vaan!', '2017-03-24 15:11');
INSERT INTO Julkaisu(kayttaja, ryhma, teksti, aika) VALUES (1, 1, 'Hyvää huomenta lorem ipsumille!', '2017-03-18 05:59');
INSERT INTO Julkaisu(kayttaja, ryhma, teksti, aika) VALUES (3, 2, 'Miten menee?', '2017-03-24 15:19');

INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES (2, 1, 'Kiitos vaan ja samoin', '2017-03-20 8:09');
INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES (1, 1, 'Kiitos itsellesi', '2017-03-20 8:11');
INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES (3, 1, 'Olkaa hiljaa', '2017-03-20 8:15');
INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES (5, 1, 'Ihan huono aamu', '2017-03-20 8:19');
INSERT INTO Kommentti(kayttaja, julkaisu, teksti, aika) VALUES (4, 2, 'moi', '2017-03-24 17:55');

INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (1, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (2, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (3, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (5, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (7, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (8, 1);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (1, 2);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (5, 2);
INSERT INTO Tykkays(kayttaja, julkaisu) VALUES (6, 3);