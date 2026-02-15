import { test } from '@playwright/test';

test('capture dokumen and tanda-terima', async ({ page }) => {
  await page.goto('http://localhost/monika/login');
  await page.fill('input[name="username"]', 'admin_nanang');
  await page.fill('input[name="password"]', 'Monika@2026!');
  await page.click('#btnLogin');
  await page.waitForTimeout(2500);

  await page.goto('http://localhost/monika/dokumen');
  await page.waitForTimeout(1200);
  await page.screenshot({ path: 'artifacts/sidebar-compare/chromium-dokumen.png', fullPage: true });

  await page.goto('http://localhost/monika/tanda-terima');
  await page.waitForTimeout(1200);
  await page.screenshot({ path: 'artifacts/sidebar-compare/chromium-tanda-terima.png', fullPage: true });
});
