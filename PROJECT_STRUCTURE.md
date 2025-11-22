# Business Website Platform - Project Structure

This is a comprehensive business website platform built according to the SRS document. The platform supports multiple business types with configurable modules.

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 with Vuetify 3
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Sanctum (API tokens)
- **Build Tool**: Vite

## Project Structure

### Backend (Laravel)

#### Models (`app/Models/`)
- **Core Models**: Page, Module, Menu, Setting, Lead, User
- **Optional Modules**: Service, Product, Portfolio, BlogPost, Faq
- **Extended Modules**: Career, JobApplication, Booking, Event, EventRegistration, Branch
- **Supporting**: Category, Tag, Media

#### Controllers
- **API Controllers** (`app/Http/Controllers/Api/`): Admin panel API endpoints
  - AuthController (login/logout)
  - PageController, ServiceController, ProductController
  - LeadController, SettingController
  
- **Public Controllers** (`app/Http/Controllers/Public/`): Public website endpoints
  - HomeController, PageController, ServiceController, ProductController
  - ContactController

#### Routes
- `routes/api.php`: Admin API routes (protected with Sanctum)
- `routes/web.php`: Public API routes + Vue SPA catch-all

#### Database
- All migrations created for core and optional modules
- Seeders for initial modules and admin user

### Frontend (Vue 3)

#### Components
- **Admin Components** (`resources/js/components/admin/`):
  - AdminLayout.vue (admin navigation)
  - AdminDashboard.vue (dashboard with statistics)

- **Public Components** (`resources/js/components/public/`):
  - PublicLayout.vue (public website layout)
  - HomePage.vue (homepage component)

## Setup Instructions

### 1. Install Dependencies

```bash
composer install
npm install
```

### 2. Environment Configuration

Copy `.env.example` to `.env` and configure:
- Database connection
- APP_URL
- Mail settings

```bash
php artisan key:generate
```

### 3. Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

This will create:
- All database tables
- Module definitions (disabled by default)
- Admin user (email: admin@example.com, password: password)

### 4. Build Assets

```bash
npm run dev  # Development
# or
npm run build  # Production
```

### 5. Start Development Server

```bash
php artisan serve
npm run dev
```

## API Endpoints

### Admin API (`/api/v1/`)

**Authentication:**
- `POST /api/v1/auth/login` - Login
- `POST /api/v1/auth/logout` - Logout (requires auth)
- `GET /api/v1/auth/user` - Get current user (requires auth)

**Protected Routes (require authentication):**
- `GET /api/v1/pages` - List pages
- `POST /api/v1/pages` - Create page
- `GET /api/v1/pages/{id}` - Get page
- `PUT /api/v1/pages/{id}` - Update page
- `DELETE /api/v1/pages/{id}` - Delete page

- Similar CRUD for services, products, leads, etc.

### Public API (`/api/public/`)

- `GET /api/public/home` - Homepage data
- `GET /api/public/pages/{slug}` - Get page by slug
- `GET /api/public/services` - List services
- `GET /api/public/services/{slug}` - Get service by slug
- `GET /api/public/products` - List products
- `POST /api/public/contact` - Submit contact form

## Features Implemented

✅ **Core Features:**
- Pages management
- Menus management
- Settings management
- Leads management
- Module system (enable/disable modules)

✅ **Optional Modules:**
- Services catalog
- Products catalog
- Portfolio/Projects
- Blog/News
- FAQ
- Careers & Job Applications
- Booking/Appointments
- Events & Registrations
- Multi-location/Branches

✅ **Admin Panel:**
- Authentication (Sanctum)
- Dashboard with statistics
- CRUD operations for content
- Leads management and export

✅ **Public Website:**
- Dynamic homepage
- Page system
- Services/Products display
- Contact forms

## Next Steps (To Complete)

1. **Vue Routes**: Update `resources/js/routes.js` to include admin and public routes
2. **Admin Components**: Create full CRUD components for pages, services, products
3. **Public Components**: Create full public website components (about, services, products, contact)
4. **File Upload**: Implement media library and file upload functionality
5. **Email Notifications**: Configure and test email sending for leads
6. **SEO**: Implement SEO meta tags management
7. **Settings UI**: Create admin UI for managing site settings

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: password

⚠️ **Change these immediately in production!**

## Module Configuration

Modules are stored in the `modules` table. To enable a module:

```php
Module::where('name', 'services')->update(['enabled' => true]);
```

Or via API/Admin panel (when implemented).

## Notes

- This is a foundational structure that can be expanded
- All core models and relationships are set up
- Admin authentication is working
- Public API endpoints are ready
- Vue components need routes configuration
- Additional admin and public components can be added incrementally

