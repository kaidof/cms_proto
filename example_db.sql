-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.27-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table cms.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table cms.news: ~21 rows (approximately)
DELETE FROM `news`;
INSERT INTO `news` (`id`, `title`, `content`, `is_active`, `created_at`) VALUES
	(1, 'Lehtme: kõik kogutud raha on jõudnud Ukraina abivajajateni', 'MTÜ Slava Ukraini kandis 1,5 miljonit eurot Eestist kogutud annetusi oma Ukraina koostööpartneri juhtidega seotud erafirmale IC Construction, kuid mis selle eest vastu saadi, pole teada, kirjutas laupäeval Eesti Päevaleht.', 1, '2023-04-16 00:26:54'),
	(2, 'Keskerakonna volikogus jäi üks hääl puudu erakorralise kongressi kokku kutsumisest', 'Keskerakonna volikogu otsustas laupäeval ülinapi häälteenamusega, et ei kutsu kokku erakorralist kongressi, kus oleks olnud päevakorras uue esimehe valimine. Vahetult enne oli Keskerakonna esimees Jüri Ratas teatanud, et ei kandideeriks erakorralisel kongressil uuesti erakonna juhiks.', 0, '2023-04-16 00:27:13'),
	(3, 'Lavly Perling: korruptsioon ei ole Eestist kuhugi kadunud', 'Eesti kohus võiks olla avatum – otseülekanneteni välja, Parempoolsete erakond ja selle juht saavad hakkama järgmiste valimisteni jäänud nelja aasta jooksul ning Eesti on uue valitsusega pööranud vasakule. Sellest rääkis Vikerraadio pikas "Reedeses intervjuus" ajakirjanik Anvar Samostile Parempoolsete juht ja endine riigi peaprokurör Lavly Perling.', 1, '2023-04-16 00:28:49'),
	(4, 'Kõlvart: volikogu otsus oli mulle üllatus', 'Keskerakonna esimeheks kandideerida soovinud Mihhail Kõlvartile oli üllatuseks, et erakond ei soovinud erakorralise kongressi kokkukutsumist. Üllatunud oli ka juhatuse liige Yana Toom, kes tunneb, et tal pole enam piisavalt mandaati tegutsemiseks.', 1, '2023-04-16 00:29:10'),
	(5, 'Sudaani poolsõjaväeline rühmitus väidab, et võttis presidendipalees võimu', 'Sudaani pealinnas Hartumis vallandusid täna ägedad lahingud. Kõikjalt kostub tulistamist ja plahvatusi, linna kohal heljub põlengute suits. Televisioonisaade katkes poolelt sõnalt.', 1, '2023-04-16 00:29:32'),
	(6, 'Vene õhurünnakus Slovjanskile hukkus üksteist elanikku', 'Vene relvajõud korraldasid Ukraina Donetski oblastis õhurünnaku Slovjanski linnale, kus hukkus vähemalt üksteist ning sai viga 21 elanikku. Kokku tegid Vene väed ööpäeva jooksul 56 rünnakut Ukraina positsioonidele ning lisaks mitukümmend õhurünnakut rinde eri lõikudes.', 1, '2023-04-16 00:29:56'),
	(7, 'Lõppenud jahiaastal kütiti ligi 75 000 ulukit', 'Keskkonnaagentuuri avaldatud 2022/2023. jahiaasta küttimisandmete kohaselt lasti lõppenud hooajal 74 285 jahiulukit, mida on ligi 9000 võrra vähem, kui eelneval jahihooajal.', 1, '2023-04-16 00:30:25'),
	(8, 'Eesti Gaas ostab Latvijas Gāze jaotusvõrgu', 'Eesti Gaas sõlmis reedel 120 miljoni eurose lepingu Läti gaasiettevõtte Latvijas Gaze tütarfirma Gaso omandamiseks, millele kuulub Läti maagaasi jaotusvõrk.', 0, '2023-04-16 00:30:45'),
	(9, 'Kontaveit kaotas kahes setis ja Eesti jäi Kreekale play-off\'is alla', 'Eesti naiste tennisekoondis kaotas Billie Jean Kingi karikaturniiri Euroopa-Aafrika tsooni II grupi play-off\'is Kreekale, kui üksikmängus pidid vastase paremust tunnistama nii Elena Malõgina kui Anett Kontaveit.', 1, '2023-04-16 00:31:13'),
	(10, 'Särjepüügiks võrguga on vaja taotleda luba', 'Harrastus-kalastajatel on kätte jõudnud kevadise püügihooaja tippsündmus, milleks on särjepüük. Lisaks õngele saavad kalastajad kevadel särge püüda ka võrguga, kuid selleks tuleb soetada luba.', 1, '2023-04-16 00:31:39'),
	(11, 'Olkiluoto tuumajaama lisandumine mõjutab elektri hinda ka Eestis', 'Soomes esmaspäeval täisvõimsusel tööle hakkav Olkiluoto tuumajaam parandab märkimisväärselt Eesti varustuskindlust ja toob hindu allapoole, kuid Soome ja Eesti vahelise ülekandevõimsuse puudujäägi tõttu jäävad hinnaerinevused põhjanaabriga püsima, kuniks Estlink 3 kaablit pole ehitatud, rääkisid energeetikaasjatundjad.', 1, '2023-04-16 00:32:13'),
	(12, 'Eesti Energia: Auveres hakkepuidu keelamine võib elektri hinda mõjutada', 'Uus koalitsioon keelab puidu põletamise Eesti Energia Auvere elektrijaamas. Eesti Energia kinnitusel tähendab see, et puidutööstuse jäätmed eksporditakse Eestist välja. Samuti võib see ettevõtte hinnangul mõjutada elektri hinda Eestis.', 1, '2023-04-16 00:32:58'),
	(13, 'Kallas: alampalga ja maksuküüru vaidlus valitsuses ei ole pingeallikas', 'Eesti 200 aseesimehe Kristina Kallase sõnul ei ole koalitsioonis hõõrumisi põhjustava alampalga tõusu ja maksuküüru kaotamise sidumise puhul tegemist pingeallikaga, vaid selgeks vaieldud kompromissiga.', 1, '2023-04-16 00:33:39'),
	(14, 'Valitsus korraldab uueks aastaks ümber viie ministeeriumi töö', 'Koalitsioonilepe korraldab ümber ministeerimite tööd ning see puudutab otseselt viit praegust ministeeriumit. Kuna uute ministeeriumite moodustamine nõuab seaduste muutmist, siis peaks kogu protsess jõudma lõpule uue aasta alguseks', 1, '2023-04-16 00:34:38'),
	(15, 'ESA saatis teele sondi Jupiteri kuudelt elumärkide otsimiseks', 'Euroopa Kosmoseagentuur (ESA) saatis reede pärastlõunal oma Kourou kosmodroomilt teele sondi Juice, mille eesmärkide hulka kuulub Jupiteri kuudelt elumärkide otsimine.', 1, '2023-04-16 00:35:36'),
	(16, 'Eesti Energia tõi juhatusse ka Enefit Poweri juhi', 'Eesti Energia nõukogu ettepanekul laiendas üldkoosolek ettevõtte juhatust kuueliikmeliseks ning nõukogu määras täiendavaks juhatuse liikmeks Andres Vainola, kes jätkab ka Enefit Poweri juhatuse esimehena.', 0, '2023-04-16 00:37:38'),
	(17, 'Konstitutsioonikohus andis Macroni pensionireformile rohelise tule', 'Prantsusmaa konstitutsioonikohus otsustas reedel, et president Emmanuel Macroni käivitatud pensionisüsteemi reform on kooskõlas põhiseadusega.', 1, '2023-04-16 00:38:27'),
	(18, 'Norra rahvusringhääling NRK keelas Tiktoki oma töötajatele', 'Norra rahvusringhääling NRK keelustab oma töötajatele Tiktoki äpi.', 1, '2023-04-16 00:39:02'),
	(19, 'Uus valitsus keelab hakkepuidu põletamise Auvere elektrijaamas', 'Puidugraanuli eksporti uus valitsus keelata ei plaani. Küll aga nähakse vaeva, et Euroopas oleks meie graanulitele väiksem nõudlus. Eesti Energiale tahetakse otse metsast tuleva puidu põletamine keelata Auvere elektrijaamas ning Narva soojatootja leidmiseks korraldatakse konkurss.', 1, '2023-04-16 00:39:28'),
	(20, 'Lugeja küsib: miks tindikala lõhnab nagu värske kurk?', 'Hiljuti alanud meritindi hooaeg tõestab, et see sale kala on oma senist alahinnatud staatust minetamas. Paarist-kolmest kuni üheksa euroni kõikuvad turuhinnad näitavad, et tarbijad on asunud enda jaoks tinditoite avastama. Kes on värske tindiga tegelenud, see aga teab, et traditsioonilist kalalõhna temaga toimetades ei tunne, pigem täidab õhku tugev kurgiaroom. Miks see nii on?', 1, '2023-04-16 00:40:23'),
	(21, 'Kauges pisigalaktikas käib ülikiire täheteke', 'Pisikeses galaktikas, mis asub meist ülimalt kaugel nii ajas kui ka ruumis, tekib uusi tähti üllatavalt kärmel robinal.', 1, '2023-04-16 00:41:28');

-- Dumping structure for table cms.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table cms.users: ~1 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_active`, `created_at`) VALUES
	(1, 'Test User', 'test@test.com', '$2y$10$k5g5ynqvcUAKt28k6pMJkuiNid.W6sw93g82KYhvhPUsBijcHwzem', 1, '2023-04-16 00:00:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
