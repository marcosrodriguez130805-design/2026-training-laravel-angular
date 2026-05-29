# 🎨 Backoffice Unified Design System Documentation

## Overview

A comprehensive Senior-level UX/UI design system has been implemented for the entire backoffice application, creating a modern, enterprise-grade management interface with consistent styling across all pages and components.

**Implementation Date:** May 28, 2026

---

## 📁 Architecture

### Core Styles File
**Location:** `frontend/src/app/pages/backoffice/backoffice.unified.scss`

This central SCSS file contains:
- **Color Palette** (11 variables)
- **Spacing System** (8px base unit, 6 scales)
- **Border Radius** (3 standard values)
- **Transitions** (3 speed levels)
- Complete component styling

### Pages Using Unified System
- ✅ Users & Templates (`users/`)
- ✅ Families (`families/`)
- ✅ Taxes (`taxes/`)
- ✅ Products (`products/`)
- ✅ Zones (`zones/`)
- ✅ Tables (`tables/`)

### Forms Using Unified System
- ✅ User Form
- ✅ Family Form
- ✅ Tax Form
- ✅ Product Form
- ✅ Zone Form
- ✅ Table Form

---

## 🎯 Design Specifications

### Color System

| Role | Value | Code |
|------|-------|------|
| Primary Dark (Sidebar) | Dark Matte | `#1a1d24` |
| Primary Light (Background) | Clean Gray | `#f4f6f9` |
| Accent (Active/Primary) | Corporate Blue | `#0066ff` |
| Accent Hover | Deep Blue | `#0052cc` |
| Surface (Cards/Inputs) | Pure White | `#ffffff` |
| Text Primary | Dark Gray | `#2d3748` |
| Text Secondary | Medium Gray | `#718096` |
| Text Tertiary | Light Gray | `#a0aec0` |
| Border | Subtle Gray | `#e2e8f0` |
| Icon Muted | Faded Gray | `#b3b3b3` |
| Status Success | Green | `#10b981` |
| Status Error | Red | `#ef4444` |
| Status Warning | Amber | `#f59e0b` |

### Spacing Scale (8px base)

```
XS   = 4px
SM   = 8px
MD   = 12px
LG   = 16px
XL   = 24px
2XL  = 32px
```

### Border Radius

```
SM  = 8px
MD  = 12px
LG  = 16px
```

### Transitions

```
Fast     = 0.15s ease
Smooth   = 0.2s ease
Standard = 0.3s ease
```

---

## 📐 Component Styling

### 1. Sidebar Navigation

**Styling Characteristics:**
- Fixed left sidebar, 100% viewport height
- Width: 260px
- Dark matte background (#1a1d24)
- Light text color (#f3f4f8)
- Smooth scrollbar with custom styling
- Right border accent (1px)

**Navigation Items (.nav-item):**
- 12px vertical/16px horizontal padding
- 12px border-radius
- Transition: all 0.2s ease
- Hover: slight background change, 4px translateX
- Active: Gradient blue background + right white accent bar
- Icon transitions with color change

### 2. Main Content

**Properties:**
- Margin-left: 260px (sidebar width)
- Flex: 1 (takes remaining space)
- Background: #f4f6f9 (clean gray)
- Auto scrolling with custom scrollbar
- Min-height: 100vh

**Inner Container (.main-content-inner):**
- Padding: 32px
- Box-sizing: border-box
- Min-height: 100%

### 3. Custom Lists (.custom-list)

**Premium Card Design:**

```
✓ Background: Pure white (#ffffff)
✓ Border-radius: 12px
✓ Box-shadow: 0 1px 3px rgba(0,0,0,0.08)
✓ Items min-height: 80px
✓ Padding: 16px all sides
✓ Border-bottom: 1px #e2e8f0 (except last)
✓ Hover: translateY(-1px), elevated shadow
```

**List Item Structure:**
```
[Icon] [Title + Subtitles] [Buttons/Toggles]
```

**Items in Lists:**
- First child: border-radius top
- Last child: border-radius bottom, no bottom border
- Middle items: subtle bottom border
- Smooth transitions on hover

### 4. Segment Toolbar (.segment-toolbar)

**Minimalist Tab Design:**
- Flat, modern appearance
- Background: Transparent
- Buttons with 12px border-radius
- States:
  - Default: Light gray text
  - Hover: Light blue background (8% opacity)
  - Checked: Solid blue background + white text
  - Shadow on checked state

### 5. Empty State (.empty-state)

**Centered, Spacious Design:**
- Display: flex + column + center
- Min-height: 400px
- Icon: 72px size, gray (#b3b3b3), 60% opacity
- Text: Centered, medium gray
- Button: Primary blue with elevation on hover

### 6. Form Components (.form-group)

**Input Field Structure:**

```
┌─────────────────────────────┐
│ Label (stacked)             │ ← font-weight: 500, #2d3748
├─────────────────────────────┤
│ Input/Select placeholder    │ ← min-height: 48px
└─────────────────────────────┘
Error text below (if invalid)    ← #ef4444
```

**Properties:**
- 12px border-radius
- 1px border #e2e8f0
- Padding: 12px vertical, 16px horizontal
- Focus: Blue border + blue shadow box
- Error state: Red border + red shadow box
- Transitions: all 0.2s ease

**Form Actions (.form-actions):**
- Display: flex
- Gap: 16px
- Justify: flex-end
- Padding: 16px 0 (with top border)
- Background: #fafbfc (subtle)

**Button Variants:**
- Primary: Blue background, white text, elevation shadow
- Secondary: Outline, transparent background
- Disabled: Gray background, 60% opacity

---

## 🎬 Interactive States

### Buttons (Touch-Friendly)
- **Min Height:** 44px
- **Padding:** 16px horizontal
- **Hover:** 2px elevation, -1px translateY, enhanced shadow
- **Active:** Pressed state, no transform
- **Disabled:** 60% opacity, no shadows

### Form Inputs
- **Default:** Light gray border
- **Hover:** Darker border, subtle shadow
- **Focus:** Blue border, blue shadow box
- **Invalid:** Red border, red shadow box

### Navigation Items
- **Hover:** Subtle background, 4px right shift
- **Active:** Gradient blue, right accent bar
- **Smooth transitions:** 0.2s ease on all properties

---

## 📱 Responsive Breakpoints

### Desktop (> 1024px)
- Sidebar: 260px
- Main: calc(100% - 260px)

### Tablet (≤ 1024px)
- Sidebar: 240px
- Main: calc(100% - 240px)

### Mobile (≤ 768px)
- Sidebar: Horizontal layout (100% width)
- Main: Full width, below sidebar
- Forms: Stack vertically
- Buttons: Full width

---

## 🔧 Usage

### Importing in Page SCSS

```scss
@import '../backoffice.unified.scss';
```

### Applying Classes in HTML

#### Pages
```html
<ion-content class="bg-light">
  <div class="main-content-inner">
    <ion-list class="custom-list">
      <!-- items -->
    </ion-list>
  </div>
</ion-content>
```

#### Forms
```html
<form>
  <div class="form-group">
    <ion-item class="ion-margin-bottom">
      <ion-label class="label-stacked">Label</ion-label>
      <ion-input placeholder="..."></ion-input>
    </ion-item>
  </div>

  <div class="form-actions">
    <ion-button class="btn-secondary" fill="clear">Cancel</ion-button>
    <ion-button class="btn-primary" type="submit">Save</ion-button>
  </div>
</form>
```

#### Segments
```html
<ion-toolbar class="segment-toolbar">
  <ion-segment [value]="selectedFilter">
    <ion-segment-button value="all">
      <ion-label>All</ion-label>
    </ion-segment-button>
  </ion-segment>
</ion-toolbar>
```

#### Empty States
```html
<div class="empty-state">
  <ion-icon name="folder-open-outline"></ion-icon>
  <p>No items found</p>
  <ion-button fill="solid" color="primary">Create First Item</ion-button>
</div>
```

---

## 📋 Component Structure

### List Item Layout
```
<ion-list class="custom-list">
  <ion-item>
    <ion-icon slot="start"></ion-icon>
    <ion-label>
      <h2>Title</h2>
      <p>Subtitle</p>
    </ion-label>
    <ion-buttons slot="end">
      <ion-button color="primary">Edit</ion-button>
      <ion-button color="danger">Delete</ion-button>
    </ion-buttons>
  </ion-item>
</ion-list>
```

### Modal Form Structure
```html
<ion-modal [isOpen]="isOpen" (didDismiss)="closeModal()" [backdropDismiss]="false">
  <ng-template>
    <ion-header>
      <ion-toolbar color="primary">
        <ion-title>Form Title</ion-title>
        <ion-buttons slot="end">
          <ion-button (click)="closeModal()">
            <ion-icon slot="icon-only" name="close"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    
    <ion-content class="form-container">
      <!-- Form content with form-group and form-actions -->
    </ion-content>
  </ng-template>
</ion-modal>
```

---

## ✨ Features

✅ **Enterprise-Grade Design**
- Professional color palette
- Consistent spacing system
- Smooth transitions and animations
- Touch-friendly interactive elements

✅ **Accessibility**
- High color contrast ratios
- Focus states for keyboard navigation
- Proper form semantics
- Error messaging clarity

✅ **Performance**
- Optimized CSS with variables
- Minimal repaints/reflows
- Efficient transition properties
- No unnecessary animations

✅ **Maintainability**
- Centralized SCSS file
- Clear variable naming
- Commented sections
- Easy to extend or modify

✅ **Mobile-First**
- Responsive breakpoints
- Touch targets ≥ 44px
- Readable text sizes
- Flexible layouts

---

## 🚀 Implementation Checklist

- [x] Created `backoffice.unified.scss` with complete styling
- [x] Updated all page HTML files with correct classes
- [x] Updated all page SCSS files to import unified system
- [x] Updated all form component HTML files
- [x] Updated all form component SCSS files
- [x] Applied `.bg-light` to all content areas
- [x] Applied `.custom-list` to all item lists
- [x] Applied `.segment-toolbar` to all tab selectors
- [x] Applied `.empty-state` to no-items views
- [x] Applied `.form-group` and `.form-actions` to all forms
- [x] Applied `.btn-primary` and `.btn-secondary` to all buttons
- [x] Applied `.label-stacked` and `.ion-margin-bottom` to form inputs

---

## 📝 Notes

1. **No Breaking Changes**: All changes are additive; existing functionality is preserved
2. **Ionic Compatible**: Uses Ionic's CSS variables where appropriate
3. **Scalable**: System can be extended with new components or variations
4. **Dark Mode Ready**: Can easily add dark mode by adjusting CSS variables
5. **Performance**: Efficient use of CSS for minimal JavaScript dependencies

---

## 🔗 Related Files

- Main stylesheet: `backoffice.unified.scss`
- Backoffice layout: `backoffice.page.html`
- Individual pages: `[page-name]/[page-name].page.html`
- Individual page styles: `[page-name]/[page-name].page.scss`
- Form components: `[parent]/[form-name]/[form-name].component.html`

---

**Version:** 1.0.0  
**Last Updated:** May 28, 2026  
**Designed by:** UX/UI Senior Design System  
**Framework:** Angular + Ionic + SCSS
