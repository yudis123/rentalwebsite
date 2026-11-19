# RentCar / Reno asset kit

UI palette: warm white #FCFAF6, navy #142536, amber #FFBE3D.
Typeface: Plus Jakarta Sans, with system sans-serif fallback.

## Files
- reno-wave.png: transparent PNG, welcoming pose; website hero / video opener.
- reno-point.png: transparent PNG, pointing pose; FAQ / explainer captions.
- reno-key.png: transparent PNG, holding key; booking completion / video closing.
- hero-mpv.png: transparent PNG, illustrative family MPV; marketing hero only.
- route.svg: independent scalable route background; editable vector for motion paths.

Images were created with the built-in imagegen tool. PNGs retain alpha transparency.
The hero vehicle is an illustration, not an inventory photograph. Product cards keep
existing inventory assets and data. Replace inventory pictures through the admin UI.

## Video use
Import PNGs separately into the video editor. Keep text, logo, route, vehicle, and
mascot on separate tracks. Use wave for the opener, point for the explanation, key
for the closing call to action. Avoid stretching. Leave safe margins for 16:9 or 9:16.
These are rendered poses, not a rigged 3D character or editable limb layers.
Website float animation respects prefers-reduced-motion.

## Generation prompt briefs
- Wave: standalone full-body Reno amber car-key mascot; rounded key-fob head,
  navy smiling face, silver key protrusion, white gloves, white car emblem, navy
  sneakers, waving, soft polished 3D studio render, transparent background.
- Point: use wave as exact character reference; same identity and rendering,
  open happy eyes, point to viewer's right, other hand on hip, transparent background.
- Key: use wave as exact character reference; same identity and rendering,
  open smiling eyes, hold a black car key remote, transparent background.
- Vehicle: pearl-white modern family MPV, front three-quarter view facing left,
  realistic studio product render, full vehicle visible, no text, transparent background.

## Local development
From the repository root: npm.cmd run build
Preview with PHP and the existing MySQL database: php -S 127.0.0.1:8091 -t .
Open http://127.0.0.1:8091/ . Stop with Ctrl+C.
Existing admin settings override default hero text and custom hero images.

## Verification
CSS production build passed. PHP syntax checks passed. Local homepage returned
HTTP 200 with 9 inventory cards, all navigation targets, and four FAQ entries.
All local assets referenced by the homepage returned HTTP 200.
Browser visual QA and interactive filter/menu checks could not be performed because
the native browser-control connection was unavailable.
