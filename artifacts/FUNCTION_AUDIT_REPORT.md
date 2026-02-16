# Laporan Audit Fungsi

Tanggal audit: 2026-02-16
Cakupan: app/, scripts/, tests/ (aset pihak ketiga dikecualikan).

## Ringkasan

- Total fungsi terdeteksi: 201
- Fungsi pada app/: 137
- TERREFERENSI: 121
- LIFECYCLE_FRAMEWORK: 18
- UTILITAS_FRAMEWORK: 3
- FUNGSI_TEST: 25
- TIDAK_DITEMUKAN_REFERENSI: 34

## Cek Antarmuka vs Route

| Menu | Route | Target Controller::method | Status |
|---|---|---|---|
| Dashboard | / | Dashboard::index | OK |
| Tanda Terima | tanda-terima | TandaTerima::index | OK |
| Logistik | logistik | Logistik::index | OK |
| Dokumen | dokumen | Dokumen::index | OK |
| Presensi | presensi | Presensi::index | OK |
| Kartu Kendali | kartu-kendali | KartuKendali::index | OK |
| Uji Petik | uji-petik | UjiPetik::index | OK |
| Kegiatan | kegiatan | Kegiatan::index | OK |
| Laporan | laporan | Laporan::index | OK |
| Monitoring | monitoring | Monitoring::index | OK |
| Logout | logout | Auth::logout | OK |

## Fungsi Tanpa Referensi (Perlu Tindakan)

| Fungsi | Lokasi | Status | Catatan |
|---|---|---|---|
| Home::index | app\Controllers\Home.php:7 | TIDAK_DITEMUKAN_REFERENSI | Controller tidak terdaftar di Routes. |
| LoginAttemptModel::getFailedAttemptsCountByUsername | app\Models\LoginAttemptModel.php:95 | TIDAK_DITEMUKAN_REFERENSI | Metode ada, belum dipakai alur login. |
| LoginAttemptModel::clearOldAttempts | app\Models\LoginAttemptModel.php:116 | TIDAK_DITEMUKAN_REFERENSI | Metode housekeeping, belum dijadwalkan/panggil. |

## Inventaris Lengkap Semua Fungsi

| Fungsi | Lokasi | Tipe | Visibilitas | Status |
|---|---|---|---|---|
| DiagnoseLogin::run | app\Commands\DiagnoseLogin.php:15 | php_method | public | TERREFERENSI |
| DiagnoseLogin::checkEnvironment | app\Commands\DiagnoseLogin.php:31 | php_method | private | TERREFERENSI |
| DiagnoseLogin::checkDatabase | app\Commands\DiagnoseLogin.php:44 | php_method | private | TERREFERENSI |
| DiagnoseLogin::checkSession | app\Commands\DiagnoseLogin.php:88 | php_method | private | TERREFERENSI |
| DiagnoseLogin::checkPermissions | app\Commands\DiagnoseLogin.php:116 | php_method | private | TERREFERENSI |
| DiagnoseLogin::checkEncryption | app\Commands\DiagnoseLogin.php:143 | php_method | private | TERREFERENSI |
| DiagnoseLogin::checkUsers | app\Commands\DiagnoseLogin.php:164 | php_method | private | TERREFERENSI |
| Database::__construct | app\Config\Database.php:193 | php_method | public | TERREFERENSI |
| Exceptions::handler | app\Config\Exceptions.php:102 | php_method | public | UTILITAS_FRAMEWORK |
| Mimes::guessTypeFromExtension | app\Config\Mimes.php:491 | php_method | public | UTILITAS_FRAMEWORK |
| Mimes::guessExtensionFromType | app\Config\Mimes.php:509 | php_method | public | UTILITAS_FRAMEWORK |
| Auth::__construct | app\Controllers\Auth.php:19 | php_method | public | TERREFERENSI |
| Auth::index | app\Controllers\Auth.php:24 | php_method | public | TERREFERENSI |
| Auth::login | app\Controllers\Auth.php:41 | php_method | public | TERREFERENSI |
| Auth::registerForm | app\Controllers\Auth.php:211 | php_method | public | TERREFERENSI |
| Auth::register | app\Controllers\Auth.php:222 | php_method | public | TERREFERENSI |
| Auth::logout | app\Controllers\Auth.php:344 | php_method | public | TERREFERENSI |
| Auth::attemptRememberLogin | app\Controllers\Auth.php:367 | php_method | private | TERREFERENSI |
| Auth::setSessionUser | app\Controllers\Auth.php:400 | php_method | private | TERREFERENSI |
| Auth::findUserByIdentity | app\Controllers\Auth.php:427 | php_method | private | TERREFERENSI |
| Auth::findUserById | app\Controllers\Auth.php:439 | php_method | private | TERREFERENSI |
| Auth::extractUserId | app\Controllers\Auth.php:446 | php_method | private | TERREFERENSI |
| Auth::extractRole | app\Controllers\Auth.php:455 | php_method | private | TERREFERENSI |
| Auth::extractName | app\Controllers\Auth.php:464 | php_method | private | TERREFERENSI |
| Auth::setRememberCookie | app\Controllers\Auth.php:477 | php_method | private | TERREFERENSI |
| Auth::clearRememberCookie | app\Controllers\Auth.php:499 | php_method | private | TERREFERENSI |
| Auth::encryptRememberToken | app\Controllers\Auth.php:504 | php_method | private | TERREFERENSI |
| Auth::decryptRememberToken | app\Controllers\Auth.php:520 | php_method | private | TERREFERENSI |
| Auth::rememberKey | app\Controllers\Auth.php:543 | php_method | private | TERREFERENSI |
| Auth::base64UrlEncode | app\Controllers\Auth.php:553 | php_method | private | TERREFERENSI |
| Auth::base64UrlDecode | app\Controllers\Auth.php:558 | php_method | private | TERREFERENSI |
| Auth::hasUserColumn | app\Controllers\Auth.php:568 | php_method | private | TERREFERENSI |
| Auth::mapLegacyRole | app\Controllers\Auth.php:577 | php_method | private | TERREFERENSI |
| Auth::ensureSessionDirectory | app\Controllers\Auth.php:590 | php_method | private | TERREFERENSI |
| Auth::logError | app\Controllers\Auth.php:606 | php_method | private | TERREFERENSI |
| Auth::logInfo | app\Controllers\Auth.php:614 | php_method | private | TERREFERENSI |
| initController | app\Controllers\BaseController.php:33 | php_method | public | TERREFERENSI |
| Dashboard::index | app\Controllers\Dashboard.php:12 | php_method | public | TERREFERENSI |
| Dokumen::__construct | app\Controllers\Dokumen.php:13 | php_method | public | TERREFERENSI |
| Dokumen::index | app\Controllers\Dokumen.php:19 | php_method | public | TERREFERENSI |
| Dokumen::create | app\Controllers\Dokumen.php:33 | php_method | public | TERREFERENSI |
| Dokumen::store | app\Controllers\Dokumen.php:47 | php_method | public | TERREFERENSI |
| Dokumen::markEntry | app\Controllers\Dokumen.php:75 | php_method | public | TERREFERENSI |
| Dokumen::reportError | app\Controllers\Dokumen.php:90 | php_method | public | TERREFERENSI |
| Home::index | app\Controllers\Home.php:7 | php_method | public | TIDAK_DITEMUKAN_REFERENSI |
| KartuKendali::__construct | app\Controllers\KartuKendali.php:15 | php_method | public | TERREFERENSI |
| KartuKendali::index | app\Controllers\KartuKendali.php:25 | php_method | public | TERREFERENSI |
| KartuKendali::detail | app\Controllers\KartuKendali.php:38 | php_method | public | TERREFERENSI |
| KartuKendali::store | app\Controllers\KartuKendali.php:120 | php_method | public | TERREFERENSI |
| KartuKendali::delete | app\Controllers\KartuKendali.php:191 | php_method | public | TERREFERENSI |
| Kegiatan::__construct | app\Controllers\Kegiatan.php:11 | php_method | public | TERREFERENSI |
| Kegiatan::index | app\Controllers\Kegiatan.php:16 | php_method | public | TERREFERENSI |
| Kegiatan::store | app\Controllers\Kegiatan.php:30 | php_method | public | TERREFERENSI |
| Kegiatan::updateStatus | app\Controllers\Kegiatan.php:62 | php_method | public | TERREFERENSI |
| Kegiatan::delete | app\Controllers\Kegiatan.php:74 | php_method | public | TERREFERENSI |
| Laporan::__construct | app\Controllers\Laporan.php:20 | php_method | public | TERREFERENSI |
| Laporan::getFilters | app\Controllers\Laporan.php:29 | php_method | private | TERREFERENSI |
| Laporan::index | app\Controllers\Laporan.php:41 | php_method | public | TERREFERENSI |
| Laporan::exportExcel | app\Controllers\Laporan.php:96 | php_method | public | TERREFERENSI |
| Laporan::exportPdf | app\Controllers\Laporan.php:184 | php_method | public | TERREFERENSI |
| Laporan::pcl | app\Controllers\Laporan.php:225 | php_method | public | TERREFERENSI |
| Laporan::pengolahan | app\Controllers\Laporan.php:247 | php_method | public | TERREFERENSI |
| Logistik::__construct | app\Controllers\Logistik.php:11 | php_method | public | TERREFERENSI |
| Logistik::index | app\Controllers\Logistik.php:16 | php_method | public | TERREFERENSI |
| Monitoring::__construct | app\Controllers\Monitoring.php:14 | php_method | public | TERREFERENSI |
| Monitoring::index | app\Controllers\Monitoring.php:21 | php_method | public | TERREFERENSI |
| Presensi::__construct | app\Controllers\Presensi.php:17 | php_method | public | TERREFERENSI |
| Presensi::index | app\Controllers\Presensi.php:22 | php_method | public | TERREFERENSI |
| Presensi::submit | app\Controllers\Presensi.php:50 | php_method | public | TERREFERENSI |
| Presensi::decodeBase64Image | app\Controllers\Presensi.php:204 | php_method | private | TERREFERENSI |
| Presensi::distanceInMeter | app\Controllers\Presensi.php:221 | php_method | private | TERREFERENSI |
| Presensi::nowInJakarta | app\Controllers\Presensi.php:235 | php_method | private | TERREFERENSI |
| Presensi::todayDate | app\Controllers\Presensi.php:240 | php_method | private | TERREFERENSI |
| Presensi::jsonResponse | app\Controllers\Presensi.php:245 | php_method | private | TERREFERENSI |
| TandaTerima::__construct | app\Controllers\TandaTerima.php:13 | php_method | public | TERREFERENSI |
| TandaTerima::index | app\Controllers\TandaTerima.php:19 | php_method | public | TERREFERENSI |
| TandaTerima::new | app\Controllers\TandaTerima.php:37 | php_method | public | TERREFERENSI |
| TandaTerima::store | app\Controllers\TandaTerima.php:48 | php_method | public | TERREFERENSI |
| TandaTerima::edit | app\Controllers\TandaTerima.php:71 | php_method | public | TERREFERENSI |
| TandaTerima::update | app\Controllers\TandaTerima.php:89 | php_method | public | TERREFERENSI |
| TandaTerima::delete | app\Controllers\TandaTerima.php:115 | php_method | public | TERREFERENSI |
| UjiPetik::__construct | app\Controllers\UjiPetik.php:13 | php_method | public | TERREFERENSI |
| UjiPetik::index | app\Controllers\UjiPetik.php:22 | php_method | public | TERREFERENSI |
| UjiPetik::new | app\Controllers\UjiPetik.php:35 | php_method | public | TERREFERENSI |
| UjiPetik::edit | app\Controllers\UjiPetik.php:49 | php_method | public | TERREFERENSI |
| UjiPetik::store | app\Controllers\UjiPetik.php:69 | php_method | public | TERREFERENSI |
| UjiPetik::update | app\Controllers\UjiPetik.php:104 | php_method | public | TERREFERENSI |
| UjiPetik::delete | app\Controllers\UjiPetik.php:144 | php_method | public | TERREFERENSI |
| CreatePresensiTable::up | app\Database\Migrations\2026-02-15-151500_CreatePresensiTable.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreatePresensiTable::down | app\Database\Migrations\2026-02-15-151500_CreatePresensiTable.php:86 | php_method | public | LIFECYCLE_FRAMEWORK |
| EnsureUsersAuthColumns::up | app\Database\Migrations\2026-02-15-161000_EnsureUsersAuthColumns.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| EnsureUsersAuthColumns::down | app\Database\Migrations\2026-02-15-161000_EnsureUsersAuthColumns.php:98 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateKartuKendaliTable::up | app\Database\Migrations\2026-02-15-164058_CreateKartuKendaliTable.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateKartuKendaliTable::down | app\Database\Migrations\2026-02-15-164058_CreateKartuKendaliTable.php:66 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateUjiPetikTable::up | app\Database\Migrations\2026-02-15-170528_CreateUjiPetikTable.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateUjiPetikTable::down | app\Database\Migrations\2026-02-15-170528_CreateUjiPetikTable.php:63 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateLogistikTable::up | app\Database\Migrations\2026-02-15-173500_CreateLogistikTable.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateLogistikTable::down | app\Database\Migrations\2026-02-15-173500_CreateLogistikTable.php:70 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateLoginAttemptsTable::up | app\Database\Migrations\2026-02-16-000000_CreateLoginAttemptsTable.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| CreateLoginAttemptsTable::down | app\Database\Migrations\2026-02-16-000000_CreateLoginAttemptsTable.php:52 | php_method | public | LIFECYCLE_FRAMEWORK |
| AlignKartuKendaliNksCollation::up | app\Database\Migrations\2026-02-16-152200_AlignKartuKendaliNksCollation.php:9 | php_method | public | LIFECYCLE_FRAMEWORK |
| AlignKartuKendaliNksCollation::down | app\Database\Migrations\2026-02-16-152200_AlignKartuKendaliNksCollation.php:24 | php_method | public | LIFECYCLE_FRAMEWORK |
| AdminSeeder::run | app\Database\Seeds\AdminSeeder.php:9 | php_method | public | TERREFERENSI |
| KartuKendaliTestSeeder::run | app\Database\Seeds\KartuKendaliTestSeeder.php:9 | php_method | public | TERREFERENSI |
| UserDummySeeder::run | app\Database\Seeds\UserDummySeeder.php:32 | php_method | public | TERREFERENSI |
| UserDummySeeder::writeLog | app\Database\Seeds\UserDummySeeder.php:329 | php_method | private | TERREFERENSI |
| UserSeeder::run | app\Database\Seeds\UserSeeder.php:9 | php_method | public | TERREFERENSI |
| AuthFilter::before | app\Filters\AuthFilter.php:11 | php_method | public | LIFECYCLE_FRAMEWORK |
| AuthFilter::after | app\Filters\AuthFilter.php:20 | php_method | public | LIFECYCLE_FRAMEWORK |
| DokumenModel::getDokumenWithRelations | app\Models\DokumenModel.php:35 | php_method | public | TERREFERENSI |
| DokumenModel::getPclPerformance | app\Models\DokumenModel.php:51 | php_method | public | TERREFERENSI |
| DokumenModel::getProcessorPerformance | app\Models\DokumenModel.php:70 | php_method | public | TERREFERENSI |
| KartuKendaliModel::getProgressByNks | app\Models\KartuKendaliModel.php:50 | php_method | public | TERREFERENSI |
| KartuKendaliModel::getEntriesByNks | app\Models\KartuKendaliModel.php:89 | php_method | public | TERREFERENSI |
| KartuKendaliModel::isRutaTaken | app\Models\KartuKendaliModel.php:106 | php_method | public | TERREFERENSI |
| LaporanModel::getStatusSummary | app\Models\LaporanModel.php:16 | php_method | public | TERREFERENSI |
| LaporanModel::getTargetVsRealisasi | app\Models\LaporanModel.php:40 | php_method | public | TERREFERENSI |
| LaporanModel::getDokumenList | app\Models\LaporanModel.php:81 | php_method | public | TERREFERENSI |
| LaporanModel::getSummaryStats | app\Models\LaporanModel.php:116 | php_method | public | TERREFERENSI |
| LoginAttemptModel::isTableAvailable | app\Models\LoginAttemptModel.php:33 | php_method | private | TERREFERENSI |
| LoginAttemptModel::logAttempt | app\Models\LoginAttemptModel.php:51 | php_method | public | TERREFERENSI |
| LoginAttemptModel::getFailedAttemptsCount | app\Models\LoginAttemptModel.php:74 | php_method | public | TERREFERENSI |
| LoginAttemptModel::getFailedAttemptsCountByUsername | app\Models\LoginAttemptModel.php:95 | php_method | public | TIDAK_DITEMUKAN_REFERENSI |
| LoginAttemptModel::clearOldAttempts | app\Models\LoginAttemptModel.php:116 | php_method | public | TIDAK_DITEMUKAN_REFERENSI |
| UjiPetikModel::getAllWithNks | app\Models\UjiPetikModel.php:51 | php_method | public | TERREFERENSI |
| updatePasswordVisibility | app\Views\auth\login.php:246 | php_method | public | TERREFERENSI |
| setLoading | app\Views\auth\login.php:304 | php_method | public | TERREFERENSI |
| init | app\Views\errors\html\debug.js:4 | js_function | n/a | TERREFERENSI |
| showTab | app\Views\errors\html\debug.js:51 | js_function | n/a | TERREFERENSI |
| getFirstChildWithTagName | app\Views\errors\html\debug.js:75 | js_function | n/a | TERREFERENSI |
| getHash | app\Views\errors\html\debug.js:86 | js_function | n/a | TERREFERENSI |
| toggle | app\Views\errors\html\debug.js:92 | js_function | n/a | TERREFERENSI |
| setButtonLoading | app\Views\presensi\index.php:131 | php_method | public | TERREFERENSI |
| initLocation | app\Views\presensi\index.php:160 | php_method | public | TERREFERENSI |
| capturePhoto | app\Views\presensi\index.php:182 | php_method | public | TERREFERENSI |
| refreshCsrf | app\Views\presensi\index.php:201 | php_method | public | TERREFERENSI |
| toggleMenu | app\Views\welcome_message.php:319 | php_method | public | TERREFERENSI |
| Get-MigrationFiles | scripts\check-migrations.ps1:19 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Check-MigrationStatus | scripts\check-migrations.ps1:49 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Display-MigrationList | scripts\check-migrations.ps1:77 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Show-MigrationCommands | scripts\check-migrations.ps1:100 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Analyze-Migrations | scripts\check-migrations.ps1:138 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-ControllerMethods | scripts\generate-api-docs.ps1:22 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-HttpMethod | scripts\generate-api-docs.ps1:53 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Generate-ApiDocs | scripts\generate-api-docs.ps1:74 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| New-GitHook | scripts\git-automation\setup.ps1:34 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-Log | scripts\git-automation\utils.ps1:14 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-LogDebug | scripts\git-automation\utils.ps1:63 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-LogInfo | scripts\git-automation\utils.ps1:70 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-LogWarning | scripts\git-automation\utils.ps1:75 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-LogError | scripts\git-automation\utils.ps1:80 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Write-LogSuccess | scripts\git-automation\utils.ps1:85 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Send-Notification | scripts\git-automation\utils.ps1:94 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-GitStatus | scripts\git-automation\utils.ps1:177 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Test-GitRepo | scripts\git-automation\utils.ps1:210 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-ModifiedFiles | scripts\git-automation\utils.ps1:223 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-UnboundFiles | scripts\git-automation\utils.ps1:236 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Invoke-RetryableCommand | scripts\git-automation\utils.ps1:249 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| New-Checkpoint | scripts\git-automation\utils.ps1:309 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Invoke-Rollback | scripts\git-automation\utils.ps1:329 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Test-CodeQuality | scripts\git-automation\utils.ps1:358 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Test-UnitTests | scripts\git-automation\utils.ps1:403 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Test-ShouldExcludeFile | scripts\git-automation\utils.ps1:443 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-FilteredFiles | scripts\git-automation\utils.ps1:462 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| runCapture | scripts\tmp-sidebar-compare.mjs:12 | js_function | n/a | TERREFERENSI |
| Update-Changelog | scripts\update-docs.ps1:27 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Get-ProjectStructure | scripts\update-docs.ps1:77 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Update-StructureDoc | scripts\update-docs.ps1:131 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| Update-FeaturesDoc | scripts\update-docs.ps1:259 | ps_function | n/a | TIDAK_DITEMUKAN_REFERENSI |
| ExampleMigration::up | tests\_support\Database\Migrations\2020-02-22-222222_example_migration.php:11 | php_method | public | LIFECYCLE_FRAMEWORK |
| ExampleMigration::down | tests\_support\Database\Migrations\2020-02-22-222222_example_migration.php:33 | php_method | public | LIFECYCLE_FRAMEWORK |
| ExampleSeeder::run | tests\_support\Database\Seeds\ExampleSeeder.php:9 | php_method | public | TERREFERENSI |
| ConfigReader::__construct | tests\_support\Libraries\ConfigReader.php:16 | php_method | public | TERREFERENSI |
| testModelFindAll | tests\database\ExampleDatabaseTest.php:17 | php_method | public | FUNGSI_TEST |
| testSoftDeleteLeavesRow | tests\database\ExampleDatabaseTest.php:28 | php_method | public | FUNGSI_TEST |
| setUp | tests\feature\AuthTest.php:14 | php_method | protected | TERREFERENSI |
| testLoginSuccess | tests\feature\AuthTest.php:72 | php_method | public | FUNGSI_TEST |
| testLoginWrongPassword | tests\feature\AuthTest.php:87 | php_method | public | FUNGSI_TEST |
| testDashboardWithoutLoginRedirectToLogin | tests\feature\AuthTest.php:101 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::setUp | tests\integration\LoginFlowTest.php:19 | php_method | protected | TERREFERENSI |
| LoginFlowTest::testCompleteLoginFlow | tests\integration\LoginFlowTest.php:36 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testLoginWithRememberMe | tests\integration\LoginFlowTest.php:72 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testAuthFilterBlocksUnauthenticatedAccess | tests\integration\LoginFlowTest.php:88 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testLoginAttemptsAreLogged | tests\integration\LoginFlowTest.php:100 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testFailedLoginAttemptsAreLogged | tests\integration\LoginFlowTest.php:120 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testSessionRegenerationOnLogin | tests\integration\LoginFlowTest.php:141 | php_method | public | FUNGSI_TEST |
| LoginFlowTest::testDatabaseConnectionErrorHandling | tests\integration\LoginFlowTest.php:159 | php_method | public | FUNGSI_TEST |
| testSessionSimple | tests\session\ExampleSessionTest.php:10 | php_method | public | FUNGSI_TEST |
| AuthTest::setUp | tests\unit\AuthTest.php:19 | php_method | protected | TERREFERENSI |
| AuthTest::testLoginPageLoads | tests\unit\AuthTest.php:36 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithValidCredentials | tests\unit\AuthTest.php:46 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithInvalidPassword | tests\unit\AuthTest.php:58 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithNonExistentUser | tests\unit\AuthTest.php:70 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithEmptyCredentials | tests\unit\AuthTest.php:81 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithInactiveAccount | tests\unit\AuthTest.php:93 | php_method | public | FUNGSI_TEST |
| AuthTest::testLoginWithEmail | tests\unit\AuthTest.php:117 | php_method | public | FUNGSI_TEST |
| AuthTest::testRateLimitingAfterMultipleFailedAttempts | tests\unit\AuthTest.php:128 | php_method | public | FUNGSI_TEST |
| AuthTest::testLogoutClearsSession | tests\unit\AuthTest.php:149 | php_method | public | FUNGSI_TEST |
| AuthTest::testSessionPersistsAfterLogin | tests\unit\AuthTest.php:170 | php_method | public | FUNGSI_TEST |
| testIsDefinedAppPath | tests\unit\HealthTest.php:12 | php_method | public | FUNGSI_TEST |
| testBaseUrlHasBeenSet | tests\unit\HealthTest.php:17 | php_method | public | FUNGSI_TEST |
