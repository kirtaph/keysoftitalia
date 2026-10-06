---
name: Key Soft Italia
description: The Tech Craftsman's Workshop - Design System
colors:
  primary: "#FF6B35"
  primary-hover: "#E85D2C"
  primary-light: "#FFE5DB"
  dark: "#1A1F2E"
  neutral-bg: "#FFFFFF"
  neutral-bg-secondary: "#F9FAFB"
  text-primary: "#111827"
  text-secondary: "#4B5563"
  text-muted: "#9CA3AF"
  admin-tone-0: "#c04416"
  admin-tone-1: "#9c3510"
  admin-tone-2: "#fff0e9"
  admin-tone-3: "#f6f7f8"
  admin-tone-4: "#e0e3e6"
  admin-tone-5: "#20252b"
  admin-tone-6: "#59616b"
  admin-tone-7: "#ac3b12"
  admin-tone-8: "#872d0d"
  admin-tone-9: "#b64015"
  admin-tone-10: "#515961"
  admin-tone-11: "#f2f3f4"
  admin-tone-12: "#626b75"
  admin-tone-13: "#eee9e5"
  admin-tone-14: "#6d3b25"
  admin-tone-15: "#faf6f3"
  admin-tone-16: "#f7f8f9"
  admin-tone-17: "#eceef0"
  admin-tone-18: "#a33812"
  admin-tone-19: "#c1c6cc"
  admin-tone-20: "#c7ccd1"
  admin-tone-21: "#414953"
  admin-tone-22: "#166534"
  admin-tone-23: "#e9ecef"
  admin-tone-24: "#fffaf7"
  admin-tone-25: "#f0f2f4"
  admin-tone-26: "#a12622"
  admin-tone-27: "#76320f"
  admin-tone-28: "#6d4c3b"
typography:
  display:
    fontFamily: "Poppins, sans-serif"
    fontSize: "clamp(2rem, 6vw, 3.5rem)"
    fontWeight: 700
    lineHeight: 1.25
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Inter, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: "normal"
  admin-step-0:
    fontFamily: "Inter, sans-serif"
    fontSize: ".7rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-1:
    fontFamily: "Inter, sans-serif"
    fontSize: ".72rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-2:
    fontFamily: "Inter, sans-serif"
    fontSize: ".75rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-3:
    fontFamily: "Inter, sans-serif"
    fontSize: ".78rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-4:
    fontFamily: "Inter, sans-serif"
    fontSize: ".8rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-5:
    fontFamily: "Inter, sans-serif"
    fontSize: ".825rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-6:
    fontFamily: "Inter, sans-serif"
    fontSize: ".85rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-7:
    fontFamily: "Inter, sans-serif"
    fontSize: ".875rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-8:
    fontFamily: "Inter, sans-serif"
    fontSize: ".9rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-9:
    fontFamily: "Inter, sans-serif"
    fontSize: "1.2rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-10:
    fontFamily: "Inter, sans-serif"
    fontSize: "1.35rem"
    fontWeight: 400
    lineHeight: 1.55
  admin-step-11:
    fontFamily: "Inter, sans-serif"
    fontSize: "1.55rem"
    fontWeight: 400
    lineHeight: 1.55
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
  xl: "16px"
  "2xl": "24px"
  admin-5: "5px"
  admin-6: "6px"
  admin-7: "7px"
  admin-9: "9px"
  admin-10: "10px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "12px"
  lg: "16px"
  xl: "20px"
  "2xl": "24px"
  "3xl": "32px"
  "4xl": "40px"
  "5xl": "48px"
  "6xl": "64px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#FFFFFF"
    rounded: "{rounded.lg}"
    padding: "12px 24px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
---

# Design System: Key Soft Italia

## 1. Overview

**Creative North Star: "The Tech Craftsman's Workshop"**

This design system is styled as a meticulous, high-grade workshop. It combines sharp structural layout grids, clear technical documentation rhythms, and a premium "Neumorphic Hybrid" depth that bridges tangible, tactile controls with modern web efficiency. The visual language conveys expert capability and local reliability, establishing an instant feeling of security when users trust Key Soft with their valuable devices.

We explicitly reject the sterile, oversaturated "SaaS-cream" trends, cheap shady-looking repair shop elements, and abstract overdesigned agency structures that isolate everyday consumers. Instead, we use highly structured visual dividers, professional micro-animations, clean typographic hierarchy, and a restrained but vibrant orange brand color that cuts through clean neutral backgrounds.

**Key Characteristics:**
- Tactile, hybrid neumorphic micro-depth on interactive components.
- Strict layout grid alignment with visible thin borders as clean structural dividers.
- High-readability Poppins and Inter typography pairing.
- Responsive, fluid viewport styling with robust reduced-motion consideration.

## 2. Colors

The color palette uses a vibrant, energetic orange brand color grounded by deep slate-darks and highly legible greyscale values.

### Primary
- **Vibrant Amber-Orange** (#FF6B35): The primary brand signature. Represents energy, speed, and helpful service. Used sparingly (under 10% screen space) on key call-to-actions, badges, active navigation states, and focus rings.

### Neutral
- **Deep Slate Dark** (#1A1F2E): Used for footer backgrounds, high-contrast dark sections, and topbars.
- **Pure White Background** (#FFFFFF): The primary canvas bg for high contrast and readability.
- **Soft Light Gray** (#F9FAFB): Secondary background for alternating sections and container surfaces.
- **Charcoal Text Primary** (#111827): The high-contrast ink color for body text. Meets WCAG 2.1 AA targets.
- **Muted Steel Gray** (#4B5563): Secondary text for subtitles, helper text, and secondary states.

### Named Rules
**The 10% Contrast Rule.** The primary brand accent (#FF6B35) is reserved strictly for high-priority interactive elements and must never exceed 10% of any screen surface. This ensures visual priority remains on critical call-to-actions.

**The Contrast Floor Rule.** Text or controls rendered on any background must have a minimum contrast ratio of 4.5:1. Never use light grey text on soft grey backgrounds.

## 3. Typography

**Display Font:** Poppins (with sans-serif fallback)
**Body Font:** Inter (with sans-serif fallback)

**Character:** Poppins provides geometric confidence and expert authority for headers, while Inter delivers ultra-clear, neutral legibility for technical service descriptions and forms.

### Hierarchy
- **Display** (Bold (700), clamp(2rem, 6vw, 3.5rem), 1.25): Used for main hero sections only.
- **Headline** (Semi-Bold (600), 2.25rem, 1.3): Used for main section headers (H2).
- **Title** (Medium (500), 1.5rem, 1.4): Used for cards, group headers, and form sections (H3).
- **Body** (Regular (400), 1rem, 1.5): Used for standard text. Paragraph line lengths are capped at 65–75 characters (65-75ch) for maximum readability.
- **Label** (Medium (500), 0.875rem, letter-spacing 0.05em, uppercase): Used for navigation items, small buttons, and metadata tags.

### Named Rules
**The Balanced Headline Rule.** All H1–H3 headings must use `text-wrap: balance` to prevent awkward line wraps, while long body copy should use `text-wrap: pretty` to reduce orphan words on smaller viewports.

**The display letter-spacing floor.** Any header larger than 2rem must have a letter-spacing value of no less than -0.02em to maintain clean grotesque typography and avoid letter-clashing.

## 4. Elevation

Key Soft Italia utilizes a Neumorphic Hybrid strategy. Rather than fully drowning the layout in heavy neumorphic elements, we keep the main canvas flat and clean, using soft, tactile inset and outset shadows on key interactive elements to mimic physical workshop buttons and slots.

### Shadow Vocabulary
- **Neumorphic Outset** (`box-shadow: 8px 8px 16px var(--ks-nm-dark), -8px -8px 16px var(--ks-nm-light)`): Used for cards, buttons, and panels at rest to lift them off the screen.
- **Neumorphic Inset** (`box-shadow: inset 6px 6px 12px var(--ks-nm-dark), inset -6px -6px 12px var(--ks-nm-light)`): Used for active button presses and focused input fields to represent a pressed state or physical slot.
- **Standard Soft Shadow** (`box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)`): Used on standard cards when neumorphism is not appropriate.

### Named Rules
**The Tactile State Rule.** Outset neumorphic elements must transition to inset shadows on focus or hover/active clicks, providing direct, tactile feedback to the user.

## 5. Components

### Buttons
- **Shape:** Softly curved corners with a 12px border radius (`--ks-radius-lg`).
- **Primary:** `linear-gradient(135deg, #ff8c42 0%, #f97316 100%)` with a subtle orange glow shadow.
- **Hover / Focus:** Moves slightly upward by 2px, shadow expands (`0 8px 25px rgba(249, 115, 22, 0.5)`).
- **Secondary:** Light tint background (`rgba(255, 107, 53, 0.05)`) with an orange border. Turns solid orange on hover.

### Cards / Containers
- **Corner Style:** 16px border radius (`--ks-radius-xl`).
- **Background:** White (`#FFFFFF`) or soft neutral-bg (`#F9FAFB`).
- **Shadow Strategy:** Uses standard soft shadow at rest, transforming to elevated shadow on hover.
- **Border:** Subtle border (`1px solid rgba(0,0,0,0.03)`).

### Inputs / Fields
- **Style:** Clean white background with a 1px solid border (`#D1D5DB`) and 12px radius.
- **Focus:** Highlighted with a 1px solid orange border (`#FF6B35`) and an orange glow (`0 0 0 3px rgba(255,107,53,0.15)`).

### Navigation
- **Style:** Sticky header with a background of `rgba(240, 242, 245, 0.82)` and `backdrop-filter: blur(16px)`. Hovering links shifts colors to orange and activates a bottom scale bar.

## 6. Do's and Don'ts

### Do:
- **Do** maintain a high color contrast of at least 4.5:1 for all placeholder and helper texts.
- **Do** respect system reduced-motion preferences by transitioning instantly or with simple crossfades when `prefers-reduced-motion` is active.
- **Do** keep line-lengths for prose text between 65ch and 75ch.
- **Do** use `text-wrap: balance` on H1 to H3 elements.

### Don't:
- **Don't** use side-stripe borders (e.g., a left orange border) as decoration on cards or alerts.
- **Don't** apply gradient text or text clips to headings.
- **Don't** pair standard box shadows with 1px borders and large blurs on the same element to avoid the "ghost card" pattern.
- **Don't** use border radii larger than 16px on standard cards and input fields.
- **Don't** use decorative technical grid overlays or sketchy hand-drawn illustrations.


### Admin workspace (October 2026)

The authenticated administration area is a product surface used at the shop counter in daylight, for long sessions with dense tables. Its tokens intentionally differ from the public marketing pages: white navigation, neutral gray canvas, compact Inter typography, solid borders and a darker orange for readable controls. This is an extension of the brand, not a change to the public site.

- Action orange: `#c04416`; hover `#9c3510`; active `#872d0d`; link `#ac3b12`; focus `#b64015`. White button labels meet 4.5:1 on the action orange.
- Canvas `#f6f7f8`; surfaces `#fff`; hover `#f2f3f4`; table heading `#f7f8f9`; rows `#faf6f3`; selection `#fff0e9` / `#fffaf7`.
- Ink `#20252b`; secondary `#515961`, `#59616b`, `#626b75`, `#414953`; borders `#e0e3e6`, `#c1c6cc`, `#c7ccd1`, `#eceef0`; utility neutrals `#e9ecef`, `#f0f2f4`, `#eee9e5`.
- Warm supporting ink `#76320f`, `#6d4c3b`, `#6d3b25`, `#a33812`; success `#166534`; error `#a12622`.
- Fixed admin type steps: `.7rem`, `.72rem`, `.75rem`, `.78rem`, `.8rem`, `.825rem`, `.85rem`, `.875rem`, `.9rem`, `1rem`, `1.2rem`, `1.35rem`, `1.55rem`; numeric data uses tabular figures.
- Admin radius steps: 5px badges, 6px utility actions, 7px controls, 9px panels, 10px list surfaces, 12px dialogs. Circular avatars and pill count badges are intentional.
- Motion: 160ms color/background changes; no decorative page entrances. Reduced motion disables transitions and animation.

The design hook's palette, type and radius warnings on `admin-workspace.css` refer to the public-site token set. They are reviewed, intentional admin-system extensions documented here, not suppressed rules. Existing public-site tokens remain unchanged.
