-- Add the statuses already offered by the admin without deleting existing requests.
ALTER TABLE used_device_quotes
  MODIFY status ENUM('pending','reviewed','contacted','accepted','rejected') NOT NULL DEFAULT 'pending';
