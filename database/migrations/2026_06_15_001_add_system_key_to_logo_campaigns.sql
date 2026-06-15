-- Migration: Add system_key column to logo_campaigns
-- Created At: 2026-06-15

ALTER TABLE logo_campaigns 
ADD COLUMN system_key VARCHAR(50) DEFAULT NULL UNIQUE AFTER status;

-- Insert default row for active flyer logo campaign
INSERT INTO logo_campaigns (name, start_date, end_date, logo_path, effect_class, status, system_key) 
VALUES ('Volantino Attivo', '2000-01-01', '2000-12-31', 'img/logo.png', '', 0, 'flyer_active')
ON DUPLICATE KEY UPDATE system_key = 'flyer_active';
