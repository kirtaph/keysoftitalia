ALTER TABLE products
  ADD COLUMN title VARCHAR(255) DEFAULT NULL AFTER sku,
  ADD COLUMN api_status ENUM('publish','draft','sold','hidden') NOT NULL DEFAULT 'publish' AFTER full_desc,
  ADD COLUMN imei VARCHAR(32) DEFAULT NULL AFTER api_status,
  ADD COLUMN serial_number VARCHAR(100) DEFAULT NULL AFTER imei,
  ADD COLUMN specs_json LONGTEXT DEFAULT NULL AFTER serial_number,
  ADD COLUMN battery_pct TINYINT UNSIGNED DEFAULT NULL AFTER specs_json,
  ADD COLUMN condition_notes TEXT DEFAULT NULL AFTER battery_pct,
  ADD COLUMN accessories_json LONGTEXT DEFAULT NULL AFTER condition_notes,
  ADD COLUMN warranty_months SMALLINT UNSIGNED DEFAULT NULL AFTER accessories_json,
  ADD COLUMN source_system VARCHAR(40) DEFAULT NULL AFTER warranty_months,
  ADD COLUMN source_device_id BIGINT UNSIGNED DEFAULT NULL AFTER source_system,
  ADD COLUMN source_pushed_at DATETIME DEFAULT NULL AFTER source_device_id,
  ADD KEY ix_api_status (api_status),
  ADD KEY ix_imei (imei),
  ADD KEY ix_serial_number (serial_number);

UPDATE products
SET api_status = IF(is_available = 1, 'publish', 'hidden')
WHERE api_status = 'publish';
