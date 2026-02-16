# Testing Guide: Password Toggle Visibility Feature

## Test Environment Setup
- **Browser**: Chrome/Firefox/Safari/Edge (latest version)
- **OS**: Windows/macOS/Linux
- **Mobile**: iOS/Android
- **Accessibility Tools**: WAVE, Axe DevTools, Lighthouse

---

## Functional Testing

### Test 1: Basic Click Toggle
**Steps:**
1. Buka halaman login
2. Arahkan mouse ke icon mata (eye icon)
3. Klik icon tersebut

**Expected Result:**
- Icon berubah dari `fa-eye` menjadi `fa-eye-slash`
- Password field berubah dari `type="password"` menjadi `type="text"`
- Karakter password menjadi terlihat
- Warna icon berubah menjadi biru (#007bff)
- Class "show" ditambahkan ke button

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

### Test 2: Toggle Back to Hidden
**Steps:**
1. Setelah password terlihat (dari Test 1)
2. Klik icon mata lagi

**Expected Result:**
- Icon kembali ke `fa-eye`
- Password field kembali ke `type="password"`
- Karakter password menjadi tersembunyi
- Warna icon kembali ke abu-abu (#6c757d)
- Class "show" dihapus dari button

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

### Test 3: Multiple Toggle
**Steps:**
1. Klik toggle icon 5-10 kali secara cepat
2. Amati perubahan state

**Expected Result:**
- State berubah konsisten setiap kali klik
- Tidak ada visual glitch atau lag
- Perubahan smooth dengan transition

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

## Keyboard Navigation Testing

### Test 4: Tab Navigation to Toggle Button
**Browser**: Chrome  
**Steps:**
1. Buka halaman login
2. Tekan Tab key sampai focus pada password field
3. Tekan Tab lagi untuk focus pada toggle button

**Expected Result:**
- Toggle button memiliki focus outline (2px solid #007bff)
- Outline visible dan clear
- Button dapat di-identify dalam page order

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

### Test 5: Enter Key to Toggle
**Browser**: Firefox  
**Steps:**
1. Tab ke toggle button (dari Test 4)
2. Tekan Enter key

**Expected Result:**
- Password visibility toggle
- Focus tetap pada toggle button
- Behavior sama dengan mouse click

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

### Test 6: Space Key to Toggle
**Browser**: Safari  
**Steps:**
1. Tab ke toggle button
2. Tekan Space bar

**Expected Result:**
- Password visibility toggle
- Focus tetap pada toggle button
- Behavior sama dengan mouse click

**Browser Support:** ✅ Chrome, ✅ Firefox, ✅ Safari, ✅ Edge

---

### Test 7: Keyboard Shortcut (Alt+P - Windows/Linux)
**Browser**: Chrome  
**Platform**: Windows/Linux  
**Steps:**
1. Buka halaman login
2. Tekan Alt+P
3. Amati perubahan

**Expected Result:**
- Password visibility toggle
- Focus dipindahkan ke toggle button
- Keyboard shortcut berhasil

**Notes:** Mungkin conflict dengan browser shortcut internal

---

### Test 8: Keyboard Shortcut (Ctrl+Shift+P - macOS)
**Browser**: Safari  
**Platform**: macOS  
**Steps:**
1. Buka halaman login
2. Tekan Cmd+Shift+P (atau Ctrl+Shift+P)
3. Amati perubahan

**Expected Result:**
- Password visibility toggle
- Focus dipindahkan ke toggle button
- Keyboard shortcut berhasil

**Notes:** Check conflict dengan system shortcuts

---

## Accessibility Testing

### Test 9: Screen Reader - VoiceOver (macOS/iOS)
**Browser**: Safari  
**Platform**: macOS  
**Steps:**
1. Enable VoiceOver (Cmd+F5)
2. Navigate ke password field
3. Navigate ke toggle button
4. Check aria-label
5. Toggle password visibility
6. Check aria-label setelah toggle

**Expected Result:**
- VoiceOver membaca "Tampilkan password" (awal)
- Button dapat di-announce sebagai button
- VoiceOver membaca "Sembunyikan password" (setelah toggle)
- Icon berubah sesuai state

---

### Test 10: Screen Reader - NVDA (Windows)
**Browser**: Firefox  
**Platform**: Windows  
**Steps:**
1. Download dan jalankan NVDA
2. Navigate ke password field
3. Navigate ke toggle button (Shift+Tab atau Tab)
4. Check aria-label
5. Toggle dengan Enter key
6. Check aria-label setelah toggle

**Expected Result:**
- NVDA membaca "Tampilkan password button"
- Button terdapat dalam tab order
- NVDA membaca update aria-label setelah toggle

---

### Test 11: Screen Reader - TalkBack (Android)
**Browser**: Chrome Mobile  
**Platform**: Android  
**Steps:**
1. Enable TalkBack
2. Swipe ke password field
3. Swipe ke toggle button
4. Double-tap untuk toggle
5. Check announcement

**Expected Result:**
- TalkBack announce "Tampilkan password button"
- Double-tap toggle password visibility
- TalkBack announce aria-label update

---

### Test 12: High Contrast Mode (Windows)
**Platform**: Windows  
**Steps:**
1. Enable High Contrast Mode (Settings > Ease of Access > High Contrast)
2. Select high contrast theme
3. Refresh halaman login
4. Amati toggle button

**Expected Result:**
- Toggle button memiliki border yang terlihat
- Icon tetap terlihat dengan jelas
- Focus outline lebih tebal (3px)
- Background contrast increased

---

### Test 13: WAVE Accessibility Audit
**Tool**: WAVE Browser Extension  
**Steps:**
1. Install WAVE extension
2. Buka halaman login
3. Jalankan WAVE scan
4. Periksa errors dan warnings

**Expected Result:**
- No errors related to password toggle
- aria-label terdeteksi dengan benar
- Button properly announced

---

### Test 14: Axe DevTools Scan
**Tool**: Axe DevTools  
**Steps:**
1. Install Axe DevTools
2. Buka halaman login
3. Jalankan full page scan
4. Filter untuk password toggle area

**Expected Result:**
- No accessibility violations
- Proper contrast ratio (WCAG AA)
- Keyboard navigation working

---

## Responsive & Mobile Testing

### Test 15: Responsive Desktop (1920x1080)
**Browser**: Chrome  
**Steps:**
1. Buka halaman login di desktop
2. Amati ukuran dan positioning toggle button
3. Test mouse click dan keyboard navigation

**Expected Result:**
- Toggle button: 40x40px
- Icon: 18px
- Padding-right: 42px
- All interactions smooth

---

### Test 16: Tablet (iPad/Android Tablet - 768px)
**Browser**: Chrome Mobile / Safari  
**Steps:**
1. Buka halaman login di tablet
2. Tap toggle button
3. Test keyboard jika available
4. Check visibility dan sizing

**Expected Result:**
- Toggle button properly sized
- Tap target sufficient for touch
- Responsive layout maintained

---

### Test 17: Mobile (iPhone/Android - 375px)
**Browser**: Safari / Chrome Mobile  
**Steps:**
1. Buka halaman login di mobile
2. Amati ukuran toggle button
3. Tap toggle button beberapa kali
4. Check CSS @media (max-width: 576px) applied

**Expected Result:**
- Toggle button: 36x36px (smaller size)
- Icon: 16px
- Padding-right: 38px
- Touch target tetap sufficient (min 44x44px ideal)
- No horizontal scroll

---

### Test 18: Landscape Orientation (Mobile)
**Device**: iPhone/Android  
**Steps:**
1. Buka halaman login
2. Rotate ke landscape
3. Test toggle functionality
4. Check layout adaptation

**Expected Result:**
- Layout tetap responsive
- Toggle button tetap accessible
- No overflow atau layout shift

---

## Visual & UX Testing

### Test 19: Hover State (Desktop)
**Browser**: Chrome  
**Steps:**
1. Move mouse ke toggle button
2. Observe styling changes

**Expected Result:**
- Background color berubah ke rgba(0,0,0,0.03)
- Text color berubah ke #495057
- Cursor shows as pointer
- Smooth transition (0.2s)

---

### Test 20: Focus State (Keyboard)
**Browser**: Firefox  
**Steps:**
1. Tab ke toggle button
2. Observe focus styling

**Expected Result:**
- Outline: 2px solid #007bff
- Outline-offset: 2px (visible spacing)
- Background: rgba(0,0,0,0.03)
- Clear visibility

---

### Test 21: Active/Click State
**Browser**: Safari  
**Steps:**
1. Click toggle button
2. Observe pada click moment

**Expected Result:**
- Icon scale down ke 0.95
- Visual feedback untuk tactile response
- Smooth animation

---

### Test 22: Icon Transition
**Browser**: Edge  
**Steps:**
1. Toggle password visibility beberapa kali
2. Observe icon change

**Expected Result:**
- Icon change smooth (opacity transition 0.2s)
- No flickering atau glitch
- Clear visual feedback

---

### Test 23: Show State Styling
**Steps:**
1. Click toggle untuk show password
2. Check class "show" pada button

**Expected Result:**
- Icon color berubah ke #007bff
- Button memiliki class "show"
- Visual indication password sedang visible

---

## Form Integration Testing

### Test 24: Form Submission While Password Visible
**Steps:**
1. Enter username
2. Enter password
3. Toggle ke show (text visible)
4. Submit form

**Expected Result:**
- Form submit berhasil dengan password correct
- Toggle state tidak mempengaruhi form data
- Password value tetap intact

---

### Test 25: Form Reset
**Steps:**
1. Enter username dan password
2. Toggle password visibility
3. Refresh halaman atau reset form
4. Check toggle state

**Expected Result:**
- Toggle state reset ke hidden (default)
- Form fields kosong
- Password field kembali ke type="password"

---

### Test 26: Remember Me Interaction
**Steps:**
1. Enter credentials
2. Toggle password visibility
3. Check Remember Me checkbox
4. Submit form
5. Visit login page again

**Expected Result:**
- Remember Me functionality independent dari toggle
- Username remembered jika di-check
- Password field back to type="password"

---

## Browser-Specific Testing

### Test 27: Chrome Dev Tools Inspection
**Browser**: Chrome  
**Steps:**
1. Open DevTools (F12)
2. Inspect password field
3. Check attributes dan listeners

**Expected Result:**
```
<input type="password" id="passwordInput" ...>
<button class="password-toggle-btn" id="passwordToggle" ...>
```
- Event listeners terlihat di DevTools
- CSS rules applicable terlihat

---

### Test 28: Firefox Developer Tools
**Browser**: Firefox  
**Steps:**
1. Open Developer Tools (F12)
2. Inspect accessibility tree
3. Check aria attributes

**Expected Result:**
- aria-label muncul di accessibility tree
- Button role teridentifikasi
- ARIA attributes update on toggle

---

### Test 29: Safari Web Inspector
**Browser**: Safari  
**Steps:**
1. Enable Developer Tools
2. Inspect password toggle elements
3. Check responsive design mode

**Expected Result:**
- All elements properly inspector
- Responsive styles applied correctly
- No console warnings

---

### Test 30: Edge DevTools (Chromium-based)
**Browser**: Edge  
**Steps:**
1. Open DevTools
2. Run Lighthouse audit
3. Check accessibility score

**Expected Result:**
- Accessibility score 90+
- No critical issues
- Proper keyboard navigation

---

## Performance Testing

### Test 31: Loading Time
**Steps:**
1. Clear cache
2. Open halaman login
3. Check page load time
4. Monitor network tab

**Expected Result:**
- No additional HTTP requests
- Inline CSS tidak cause render delay
- Toggle functionality ready on page load

---

### Test 32: Memory Leak Test
**Browser**: Chrome  
**Steps:**
1. Open DevTools > Memory tab
2. Toggle password 100+ times
3. Take heap snapshot
4. Check memory growth

**Expected Result:**
- No significant memory increase
- No event listener leaks
- Heap snapshot stable

---

## Cross-Browser Compatibility Matrix

| Feature | Chrome | Firefox | Safari | Edge | Mobile |
|---------|--------|---------|--------|------|--------|
| Click Toggle | ✅ | ✅ | ✅ | ✅ | ✅ |
| Tab Navigation | ✅ | ✅ | ✅ | ✅ | ⚠️ |
| Enter Key | ✅ | ✅ | ✅ | ✅ | ✅ |
| Space Key | ✅ | ✅ | ✅ | ✅ | ⚠️ |
| Keyboard Shortcut | ✅ | ⚠️ | ⚠️ | ⚠️ | ❌ |
| aria-label | ✅ | ✅ | ✅ | ✅ | ✅ |
| Screen Reader | ✅ | ✅ | ✅ | ✅ | ✅ |
| High Contrast | ✅ | ✅ | ✅ | ✅ | ⚠️ |
| Reduced Motion | ✅ | ✅ | ✅ | ✅ | ✅ |
| Responsive | ✅ | ✅ | ✅ | ✅ | ✅ |

**Legend:**
- ✅ Full support
- ⚠️ Partial support (depends on device/browser settings)
- ❌ Not supported

---

## Test Report Template

### Session Information
- Date: ___________
- Tester: ___________
- Browser: ___________
- OS: ___________
- Device: ___________

### Test Results
- [ ] Test 1: Basic Click Toggle - **PASS / FAIL**
- [ ] Test 2: Toggle Back to Hidden - **PASS / FAIL**
- [ ] Test 3: Multiple Toggle - **PASS / FAIL**
- [ ] Test 4: Tab Navigation - **PASS / FAIL**
- [ ] Test 5: Enter Key - **PASS / FAIL**
- [ ] Test 6: Space Key - **PASS / FAIL**
- [ ] Test 7: Keyboard Shortcut Alt+P - **PASS / FAIL**
- [ ] Test 8: Keyboard Shortcut Ctrl+Shift+P - **PASS / FAIL**
- [ ] Test 9: VoiceOver - **PASS / FAIL**
- [ ] Test 10: NVDA - **PASS / FAIL**
- [ ] Test 11: TalkBack - **PASS / FAIL**
- [ ] Test 12: High Contrast Mode - **PASS / FAIL**
- ...and more

### Issues Found
1. Issue: _________________ | Severity: Critical / High / Medium / Low
2. Issue: _________________ | Severity: Critical / High / Medium / Low

### Notes
_______________________________________________________

### Recommendation
- [x] Ready for Production
- [ ] Need Fixes
- [ ] Pending Further Testing

---

## Automation Testing (Optional)

### Cypress E2E Test Example
```javascript
describe('Password Toggle Feature', () => {
  it('should toggle password visibility on click', () => {
    cy.visit('/auth/login');
    cy.get('#passwordInput').should('have.attr', 'type', 'password');
    cy.get('#passwordToggle').click();
    cy.get('#passwordInput').should('have.attr', 'type', 'text');
    cy.get('#passwordToggle').should('have.class', 'show');
  });

  it('should support keyboard navigation', () => {
    cy.visit('/auth/login');
    cy.get('#passwordToggle').focus();
    cy.get('#passwordToggle').should('have.focus');
    cy.get('#passwordToggle').type('{enter}');
    cy.get('#passwordInput').should('have.attr', 'type', 'text');
  });

  it('should have correct aria-label', () => {
    cy.visit('/auth/login');
    cy.get('#passwordToggle').should('have.attr', 'aria-label', 'Tampilkan password');
    cy.get('#passwordToggle').click();
    cy.get('#passwordToggle').should('have.attr', 'aria-label', 'Sembunyikan password');
  });
});
```

---

## Pass/Fail Criteria

### PASS Criteria
- ✅ All 30 functional tests pass
- ✅ Mouse, keyboard, dan touch interactions work
- ✅ Accessibility compliance (WCAG 2.1 AA)
- ✅ Cross-browser compatibility confirmed
- ✅ Mobile responsive working
- ✅ No console errors atau warnings
- ✅ Performance acceptable (<100ms toggle response)

### FAIL Criteria
- ❌ Any critical feature doesn't work
- ❌ Accessibility violations found
- ❌ Browser-specific issues
- ❌ Memory leaks detected
- ❌ Security vulnerabilities

---

## Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| QA Lead | __________ | __________ | __________ |
| Developer | __________ | __________ | __________ |
| Reviewer | __________ | __________ | __________ |

