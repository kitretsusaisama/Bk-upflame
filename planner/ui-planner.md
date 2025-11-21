You are an enterprise Laravel architect and UI engineer.

I already have a complete Laravel backend system (multi-tenant, multi-role, SSO, RBAC/ABAC, workflows, providers, bookings, etc.). 

I now want ONLY the dashboard UI in Laravel Blade + pure CSS (NO Vite, NO Tailwind, NO Mix, NO frontend build tools).  
Just clean Blade + simple modular CSS files stored in /public/css/.

Generate a fully production-ready dashboard UI system with the following requirements:

---------------------------------------
DASHBOARDS TO GENERATE (5 TOTAL)
---------------------------------------
1. Super Admin Dashboard
2. Tenant Admin Dashboard
3. Provider Dashboard (Pandit/Vendor/Astrologer)
4. Operations Dashboard
5. Customer Dashboard

---------------------------------------
UI REQUIREMENTS
---------------------------------------
- Use Blade templates only (no SPA frameworks).
- Use pure CSS (dashboard.css) saved in /public/css/dashboard.css.
- Dashboard Layout: sidebar + topbar + page content.
- Provide reusable components:
  - sidebar.blade.php
  - topbar.blade.php
  - card.blade.php
  - table.blade.php
  - badge.blade.php
- Fully responsive (mobile + desktop).
- Professional, clean, modern UI (MNC-grade).
- Unified layout file: layouts/dashboard.blade.php
- Use simple icon placeholders (emoji or Unicode).
- No external dependencies except Google Fonts.

---------------------------------------
DASHBOARD STRUCTURE
---------------------------------------
Create:
- resources/views/layouts/dashboard.blade.php
- resources/views/components/sidebar.blade.php
- resources/views/components/topbar.blade.php
- resources/views/components/card.blade.php
- resources/views/components/table.blade.php
- resources/views/components/badge.blade.php

For each dashboard create sample pages:
- superadmin/dashboard.blade.php
- tenantadmin/dashboard.blade.php
- provider/dashboard.blade.php
- ops/dashboard.blade.php
- customer/dashboard.blade.php

---------------------------------------
CSS REQUIREMENTS
---------------------------------------
Create a full production-ready CSS file:
- /public/css/dashboard.css

CSS must include:
- Layout (sidebar + topbar)
- Cards
- Tables
- Buttons
- Forms
- Typography
- Responsive grid
- Hover states
- Dark sidebar theme

---------------------------------------
INTEGRATION
---------------------------------------
Output must include:
- How to pass menu items from Controller to Blade
- Route group structure for each dashboard
- How to include dashboard.css
- Example usage of components
- Blade placeholders like @yield('content')

---------------------------------------
FINAL OUTPUT FORMAT
---------------------------------------
Produce:
1. Full folder structure
2. All Blade component code
3. All dashboard pages (sample)
4. The complete dashboard.css code
5. Controller examples for injecting menus
6. Route examples for each dashboard
7. Instructions for integration

---------------------------------------
IMPORTANT NOTES
---------------------------------------
- No Vue, React, Vite, Mix.
- Only Blade + pure CSS.
- Must be modular and reusable.
- Must look professional (enterprise design).
- Must be compatible with Laravel 9/10/11.
- Use minimal JS; only where necessary.
- Include comments for clarity.

---------------------------------------

Generate the entire thing now.
