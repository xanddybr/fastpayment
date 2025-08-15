CREATE DATABASE IF NOT EXISTS u617177303_fastpay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE u617177303_fastpay;

-- Tabela: Person (admin ou cliente)
CREATE TABLE Person (
    id_person INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    type_person ENUM('admin', 'client') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela: Person_details
CREATE TABLE Person_details (
    id_PersonDetails INT AUTO_INCREMENT PRIMARY KEY,
    id_person INT NOT NULL,
    activity_professional VARCHAR(100),
    phone VARCHAR(20),
    street VARCHAR(100),
    number VARCHAR(10),
    neighborhood VARCHAR(100),
    city VARCHAR(100),
    obs_motived TEXT,
    first_time BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_person) REFERENCES Person(id_person)
        ON DELETE CASCADE
);

-- Tabela: TypeEvent
CREATE TABLE TypeEvent (
    id_tpEvent INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL
);

-- Tabela: Event
CREATE TABLE myEvent (
    id_myevent INT AUTO_INCREMENT PRIMARY KEY,
    myevent VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

-- Tabela: Schedule (agenda de eventos)
CREATE TABLE Schedule (
    id_schedule INT AUTO_INCREMENT PRIMARY KEY,
    id_myevent INT NOT NULL,
    id_tpEvent INT NOT NULL,
    time TIME NOT NULL,
    date DATE NOT NULL,
    vacancies INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_myevent) REFERENCES myEvent(id_myevent),
    FOREIGN KEY (id_tpEvent) REFERENCES TypeEvent(id_tpEvent)
);

-- Tabela: Historic (histórico de compras)
CREATE TABLE Historic (
    id_historic INT AUTO_INCREMENT PRIMARY KEY,
    id_person INT NOT NULL,
    id_schedule INT NOT NULL,
    status_payment ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    payment_url TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_person) REFERENCES Person(id_person),
    FOREIGN KEY (id_schedule) REFERENCES Schedule(id_schedule)
);
