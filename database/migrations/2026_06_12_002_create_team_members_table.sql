-- Migration: Create team_members table
-- Created At: 2026-06-12

CREATE TABLE IF NOT EXISTS team_members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    bio TEXT,
    skills VARCHAR(255) DEFAULT NULL,
    aos_animation VARCHAR(50) DEFAULT 'fade-up',
    sort_order INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO team_members (name, role, photo_path, bio, skills, aos_animation, sort_order, status) VALUES
('Patrizio Cuscito', 'Founder & CEO', 'img/team/patrizio.png', 'Tecnico e sviluppatore con oltre 20 anni di esperienza. Esperto in riparazioni elettroniche avanzate e sviluppo software, guida Key Soft Italia unendo competenza e visione innovativa.', 'Diagnostica, Sviluppo, Leadership', 'fade-right', 10, 1),
('Vito Moro', 'Founder & Marketing Expert', 'img/team/vito.png', 'Co-fondatore di Key Soft Italia, è il motore creativo del gruppo. Si occupa di marketing e comunicazione con un approccio strategico e sempre orientato al cliente.', 'Marketing, Strategia, Branding', 'fade-up', 20, 1),
('Giulio Ricciardi', 'Founder & IT Expert', 'img/team/giulio.png', 'Specialista in assistenza tecnica informatica e reti aziendali. Coordina gli interventi e garantisce supporto costante ai clienti business e privati.', 'Networking, Assistenza IT, Gestione Clienti', 'fade-left', 30, 1),
('Francesca Angelillo', 'Customer Care', 'img/team/francesca.png', 'Accogliente e precisa, è il primo sorriso che i clienti incontrano in negozio. Gestisce preventivi, comunicazioni e relazioni con grande professionalità.', 'Relazioni, Organizzazione, Comunicazione', 'fade-right', 40, 1),
('Niccolò Colafemmina', 'Tecnico Informatico', 'img/team/niccolo.png', 'Tecnico giovane e appassionato, si occupa di assistenza hardware e software, aggiornamenti e installazioni con rapidità e precisione.', 'Hardware, Software, Supporto Tecnico', 'fade-up', 50, 1);
