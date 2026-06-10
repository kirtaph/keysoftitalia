-- Migration: Update to consumer telephony promotions
-- Created At: 2026-06-10

TRUNCATE TABLE telephony_promotions;

INSERT INTO telephony_promotions (operator_name, logo_path, plan_name, price, price_detail, features, is_featured, status) VALUES
('Kena Mobile', NULL, 'Kena 5G 5.99', 5.99, '/mese', '300 GB in 5G (Rete TIM)\nMinuti illimitati nazionali\n200 SMS inclusi\nNessun vincolo contrattuale\nRicarica Automatica supportata', 1, 1),
('Lycamobile', NULL, 'Lyca 5G Portin 599', 5.99, '/mese', '150 GB in 5G (Rete Vodafone)\nMinuti e SMS illimitati\n2 Mesi di rinnovo INCLUSI\nSupporto eSIM gratuito\nSenza vincoli di durata', 0, 1),
('Fastweb Mobile', NULL, 'Fastweb Mobile 5G', 9.95, '/mese', '150 GB in 5G (Rete Fastweb)\nMinuti illimitati nazionali\n100 SMS inclusi\nNessun costo nascosto\nAttivazione online o negozio', 0, 1),
('Fastweb Casa', NULL, 'Fastweb Casa Light Fibra', 27.95, '/mese', 'Connessione Fibra ultraveloce FTTH\nVelocità fino a 2.5 Gbps\nModem Internet Box Wi-Fi 6 incluso\nCosto di attivazione compreso\nChiamate a consumo verso fissi e mobili', 0, 1);
