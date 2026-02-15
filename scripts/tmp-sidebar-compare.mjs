import fs from 'fs';
import path from 'path';
import { chromium, firefox } from 'playwright';

const baseUrl = 'http://localhost/monika';
const username = 'admin_nanang';
const password = 'Monika@2026!';

const outDir = path.resolve('artifacts/sidebar-compare');
fs.mkdirSync(outDir, { recursive: true });

async function runCapture(label, browser) {
  const context = await browser.newContext({ viewport: { width: 1920, height: 1080 } });
  const page = await context.newPage();

  await page.goto(`${baseUrl}/login`, { waitUntil: 'domcontentloaded' });
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);

  await page.click('#btnLogin');
  await page.waitForURL(/\/dashboard|\/$/, { timeout: 20000 }).catch(() => {});
  await page.waitForTimeout(2000);

  await page.goto(`${baseUrl}/dokumen`, { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);
  await page.screenshot({ path: path.join(outDir, `${label}-dokumen.png`), fullPage: true });

  await page.goto(`${baseUrl}/tanda-terima`, { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);
  await page.screenshot({ path: path.join(outDir, `${label}-tanda-terima.png`), fullPage: true });

  await context.close();
}

const results = [];

// Chrome channel attempt
try {
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  await runCapture('chrome', browser);
  await browser.close();
  results.push('chrome:ok');
} catch (err) {
  results.push(`chrome:fail:${err.message.split('\n')[0]}`);
}

// Firefox
try {
  const browser = await firefox.launch({ headless: true });
  await runCapture('firefox', browser);
  await browser.close();
  results.push('firefox:ok');
} catch (err) {
  results.push(`firefox:fail:${err.message.split('\n')[0]}`);
}

// Edge channel attempt
try {
  const browser = await chromium.launch({ headless: true, channel: 'msedge' });
  await runCapture('edge', browser);
  await browser.close();
  results.push('edge:ok');
} catch (err) {
  results.push(`edge:fail:${err.message.split('\n')[0]}`);
}

console.log(results.join('\n'));
