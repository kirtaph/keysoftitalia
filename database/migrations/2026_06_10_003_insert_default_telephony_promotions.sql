-- Migration: Insert default telephony promotions
-- Created At: 2026-06-10

INSERT INTO telephony_promotions (operator_name, logo_path, plan_name, price, price_detail, features, is_featured, status) VALUES
('TIM Business', NULL, 'Basic Business', 19.00, '/mese', '1 Linea telefonica\n500 minuti nazionali\nNumero fisso incluso\nSegreteria telefonica\nApp mobile', 0, 1),
('Vodafone Business', NULL, 'Professional', 39.00, '/mese', '3 Linee telefoniche\nMinuti ILLIMITATI nazionali\n500 minuti internazionali\nCentralino virtuale\nIVR personalizzato\nRegistrazione chiamate', 1, 1),
('WindTre Business', NULL, 'Enterprise', 99.00, '/mese', 'Linee ILLIMITATE\nChiamate ILLIMITATE ovunque\nCentralino avanzato\nCRM integrato\nVideoconferenze HD\nSupporto dedicato 24/7', 0, 1);
