DROP DATABASE IF EXISTS xfurik00;
CREATE DATABASE xfurik00 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE xfurik00;

-- Tabulka pro uživatele
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(75),
    role ENUM('admin', 'registered', 'guest') DEFAULT 'guest'
);

-- Tabulka pro konference
CREATE TABLE conferences (
    conference_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(1000),
    genre VARCHAR(100),
    location VARCHAR(200) NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    organizer_id INT,
    FOREIGN KEY (organizer_id) REFERENCES users(user_id)
);


-- Tabulka pro prezentace
CREATE TABLE presentations (
    presentation_id INT AUTO_INCREMENT PRIMARY KEY,
    conference_id INT,
    room_name VARCHAR(100),
    title VARCHAR(100) NOT NULL,
    speaker_id INT,
    description VARCHAR(1000),
    date DATE,
    start_time TIME,
    end_time TIME,
    status ENUM('approved', 'not_approved') DEFAULT 'not_approved',
    FOREIGN KEY (conference_id) REFERENCES conferences(conference_id),
    FOREIGN KEY (speaker_id) REFERENCES users(user_id)
);


-- Tabulka propojení uživatele a prezentace
-- pro vytvoření osobního rozvrhu
CREATE TABLE user_presentation (
    user_id INT,
    presentation_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (presentation_id) REFERENCES presentations(presentation_id)
);


-- Tabulka pro rezervace
CREATE TABLE reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    conference_id INT,
    tickets_count INT NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (conference_id) REFERENCES conferences(conference_id)
);


-- Vložení uživatelů
INSERT INTO users (name, lastname, email, username, password, role) VALUES
('Admin', 'Admin', 'admin@admin.cz', 'admin', '$2y$10$5.T57cVewLG6kT2ICQCjcOe4kxVUfHMnYQVJlMMti0VtANuK73I1G', 'admin'), -- Heslo: admin
('Jan', 'Novák', 'jan@novak.cz', 'jan', '$2y$10$xl3rRmku6xncjxDsqiHy2uA42uua.aT7QZNj1gP9cfH3rwegFu0j.', 'registered'), -- Heslo: jan
('Pavel', 'Novotný', 'pavel@novotny.cz', 'pavel', '$2y$10$Fhzl5TTccFEIK7.coFBagO2sxfSLmJ/3KbswE19qcTWeKahqopT6O', 'registered'); -- Heslo: pavel

-- Vložení konferencí
INSERT INTO conferences (name, description, genre, location, start_datetime, end_datetime, price, capacity, organizer_id) VALUES
('Inovace v technologii 2024', 'Konference zaměřená na budoucnost technologií, zahrnující témata jako AI, blockchain a IoT.', 'Technologie', 'VUT FIT', '2024-12-15 09:00:00', '2024-12-16 18:00:00', 199, 500, 1),
('Summit o zdraví a wellness', 'Summit zaměřený na duševní zdraví, fitness a holistickou pohodu, s workshopy a odbornými přednáškami.', 'Zdraví', 'VUT FEKT', '2024-12-10 10:00:00', '2024-12-11 16:00:00', 149, 300, 2),
('Globální byznys fórum 2024', 'Mezinárodní fórum, které se zaměřuje na nejnovější trendy v globálním byznysu, s průmyslovými lídry a inovátory.', 'Byznys', 'VUT FIT', '2024-12-20 08:30:00', '2024-12-22 17:00:00', 249, 800, 3);

-- Vložení prezentací
INSERT INTO presentations (conference_id, room_name, title, speaker_id, description, date, start_time, end_time, status) VALUES
(1, 'Místnost A', 'Budoucnost umělé inteligence', 1, 'Průzkum nejnovějších pokroků v oblasti AI a jejího potenciálního dopadu na různé průmyslové oblasti.', '2024-12-15', '09:00:00', '10:30:00', 'approved'),
(1, 'Místnost B', 'Podnikatelské strategie pro rok 2025', 2, 'Interaktivní sezení o efektivních podnikatelských strategiích, které je nutné přijmout pro nadcházející rok.', '2024-12-15', '11:00:00', '12:30:00', 'not_approved'),
(1, 'Místnost C', 'Kybernetická bezpečnost: Ochrana vašich dat', 3, 'Detailní prezentace o aktuálních hrozbách v oblasti kybernetické bezpečnosti a jak chránit citlivé informace.', '2024-12-16', '14:00:00', '15:30:00', 'approved'),
(3, 'Místnost B', 'Umělá inteligence a budoucnost práce', 2, 'Diskuse o vlivu umělé inteligence na pracovní trh, automatizaci a změnu kariérních cest.', '2024-12-20', '10:00:00', '11:30:00', 'approved');

-- Vložení user_presentation
INSERT INTO user_presentation (user_id, presentation_id) VALUES
(1, 1),
(2, 2);

-- Vložení rezervací
INSERT INTO reservations (user_id, conference_id, tickets_count, status) VALUES
(2, 1, 2, 'pending'),
(3, 1, 1, 'paid'),
(1, 2, 3, 'pending');