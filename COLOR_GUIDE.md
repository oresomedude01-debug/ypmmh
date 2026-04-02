# 🎨 Color Customization Guide

## Quick Color Change

To change the primary color of your dashboard, edit the CSS variables in `resources/views/layouts/dashboard.blade.php`:

```css
:root {
    /* CHANGE THESE THREE VALUES */
    --primary-hue: 260;        /* 0-360 (color wheel position) */
    --primary-sat: 80%;        /* 0-100% (color intensity) */
    --primary-light: 60%;      /* 0-100% (brightness) */
}
```

## 🎨 Pre-made Color Schemes

### 1. Purple (Default - YPMMH Brand)
```css
--primary-hue: 260;
--primary-sat: 80%;
--primary-light: 60%;
```
**Result:** Rich purple with glassmorphism

---

### 2. Ocean Blue
```css
--primary-hue: 210;
--primary-sat: 85%;
--primary-light: 55%;
```
**Result:** Professional blue, great for corporate feel

---

### 3. Emerald Green
```css
--primary-hue: 140;
--primary-sat: 70%;
--primary-light: 50%;
```
**Result:** Fresh green, nature-inspired

---

### 4. Sunset Orange
```css
--primary-hue: 30;
--primary-sat: 90%;
--primary-light: 60%;
```
**Result:** Warm orange, energetic vibe

---

### 5. Rose Pink
```css
--primary-hue: 330;
--primary-sat: 75%;
--primary-light: 65%;
```
**Result:** Soft pink, modern and friendly

---

### 6. Teal
```css
--primary-hue: 180;
--primary-sat: 70%;
--primary-light: 50%;
```
**Result:** Balanced teal, calm and professional

---

### 7. Royal Purple
```css
--primary-hue: 280;
--primary-sat: 90%;
--primary-light: 55%;
```
**Result:** Deep purple, premium feel

---

### 8. Crimson Red
```css
--primary-hue: 350;
--primary-sat: 80%;
--primary-light: 55%;
```
**Result:** Bold red, attention-grabbing

---

## 🎯 Understanding HSL

**HSL = Hue, Saturation, Lightness**

### Hue (0-360)
The color on the color wheel:
- **0° / 360°** = Red
- **30°** = Orange
- **60°** = Yellow
- **120°** = Green
- **180°** = Cyan
- **210°** = Blue
- **240°** = Deep Blue
- **270°** = Purple
- **300°** = Magenta
- **330°** = Pink

### Saturation (0-100%)
Color intensity:
- **0%** = Gray (no color)
- **50%** = Muted color
- **100%** = Full, vibrant color

**Recommendation:** Keep between 70-90% for modern UI

### Lightness (0-100%)
Brightness:
- **0%** = Black
- **50%** = Pure color
- **100%** = White

**Recommendation:** Keep between 50-65% for primary colors

---

## 🔧 Advanced Customization

### Custom Gradient Backgrounds

Edit the stat cards in `resources/views/pages/dashboard.blade.php`:

```html
<!-- Example: Custom gradient -->
<div class="w-12 h-12 rounded-lg flex items-center justify-center" 
     style="background: linear-gradient(135deg, #YOUR_COLOR_1 0%, #YOUR_COLOR_2 100%);">
    <i class="fas fa-icon text-white text-xl"></i>
</div>
```

**Pre-made Gradients:**

1. **Purple Dream:**
   ```css
   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
   ```

2. **Ocean Breeze:**
   ```css
   background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
   ```

3. **Sunset Glow:**
   ```css
   background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
   ```

4. **Forest Green:**
   ```css
   background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
   ```

5. **Royal Gold:**
   ```css
   background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
   ```

---

## 🎨 Theme-Specific Colors

### Light Theme Colors
```css
:root {
    --bg-primary: #f0f4f8;           /* Main background */
    --bg-secondary: #ffffff;          /* Card backgrounds */
    --text-primary: #1a202c;          /* Main text */
    --text-secondary: #4a5568;        /* Secondary text */
    --border-color: rgba(203, 213, 225, 0.3);
    --glass-bg: rgba(255, 255, 255, 0.7);
    --glass-border: rgba(255, 255, 255, 0.8);
}
```

### Dark Theme Colors
```css
[data-theme="dark"] {
    --bg-primary: #0f172a;            /* Main background */
    --bg-secondary: #1e293b;          /* Card backgrounds */
    --text-primary: #f1f5f9;          /* Main text */
    --text-secondary: #cbd5e1;        /* Secondary text */
    --border-color: rgba(51, 65, 85, 0.5);
    --glass-bg: rgba(30, 41, 59, 0.6);
    --glass-border: rgba(71, 85, 105, 0.5);
}
```

---

## 🖼️ Customizing Glassmorphism Effect

### Adjust Glass Intensity

In `dashboard.blade.php`, find the `.glass` class:

```css
.glass {
    background: var(--glass-bg);
    backdrop-filter: blur(16px) saturate(180%);  /* ADJUST THESE */
    -webkit-backdrop-filter: blur(16px) saturate(180%);
    border: 1px solid var(--glass-border);
    box-shadow: 0 8px 32px 0 var(--shadow-color);
}
```

**Blur Options:**
- `blur(8px)` - Subtle glass
- `blur(16px)` - **Default** - Balanced
- `blur(24px)` - Strong glass effect

**Saturation Options:**
- `saturate(150%)` - Muted
- `saturate(180%)` - **Default** - Vibrant
- `saturate(200%)` - Very vibrant

---

## 🎯 Quick Test

1. Open `demo-preview.html` in your browser
2. Edit the CSS variables in the `<style>` section
3. Refresh to see changes instantly
4. Once happy, apply the same values to `dashboard.blade.php`

---

## 📝 Tips

✅ **DO:**
- Use HSL for easy color variations
- Keep saturation between 70-90%
- Test in both light and dark themes
- Maintain good contrast for accessibility

❌ **DON'T:**
- Use pure black (#000000) or pure white (#FFFFFF)
- Set saturation below 50% (colors look washed out)
- Use more than 2-3 primary colors
- Forget to test on mobile devices

---

## 🚀 Apply Changes

After customizing:

1. Save `resources/views/layouts/dashboard.blade.php`
2. Clear browser cache (Ctrl + Shift + R)
3. Refresh your Laravel app
4. Enjoy your custom-branded dashboard!

---

**Need help?** Check the main README.md for more details!
