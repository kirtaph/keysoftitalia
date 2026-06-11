-- Migration: Link telephony promotions to brand partners
-- Created At: 2026-06-11

-- Add logo_path to telephony_partners
ALTER TABLE telephony_partners 
ADD COLUMN logo_path VARCHAR(255) DEFAULT NULL AFTER icon_color;

-- Add partner_id to telephony_promotions
ALTER TABLE telephony_promotions 
ADD COLUMN partner_id INT UNSIGNED DEFAULT NULL AFTER id;

-- Add index and foreign key link
ALTER TABLE telephony_promotions 
ADD CONSTRAINT fk_telephony_promotions_partner 
FOREIGN KEY (partner_id) REFERENCES telephony_partners(id) 
ON DELETE SET NULL;
