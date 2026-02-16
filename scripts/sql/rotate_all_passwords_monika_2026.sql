-- MONIKA Password Rotation Script
-- Target password (plain text): Monika@2026!
-- Generated hash (bcrypt): $2y$10$2NQx.WubMuShjmGCzJBd8OqJMME7kTCIPe2STz4uhoVQd1V7ZY/1q
--
-- Jalankan script ini di masing-masing environment:
-- 1) Development
-- 2) Staging
-- 3) Production
--
-- Catatan:
-- - Script ini merotasi seluruh akun user aplikasi pada tabel `users`.
-- - Service account database (mis. root/monika_app) perlu dirotasi terpisah via ALTER USER.

START TRANSACTION;

UPDATE `users`
SET `password` = '$2y$10$2NQx.WubMuShjmGCzJBd8OqJMME7kTCIPe2STz4uhoVQd1V7ZY/1q';

-- Verifikasi jumlah akun yang sudah memakai hash password baru
SELECT
    COUNT(*) AS total_users,
    SUM(`password` = '$2y$10$2NQx.WubMuShjmGCzJBd8OqJMME7kTCIPe2STz4uhoVQd1V7ZY/1q') AS users_with_new_password
FROM `users`;

COMMIT;
