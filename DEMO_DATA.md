# Demo Data Seeder

This document describes the demo data that will be populated when you run the database seeder.

## Running the Seeder

##  if you want to refresh and reseed

```bash
php artisan migrate:fresh --seed
```

```bash
php artisan db:seed
```

Or to run only the demo data seeder:

```bash
php artisan db:seed --class=DemoDataSeeder
```

## What Gets Seeded

### 1. Settings
- Site name: "Business Pro Solutions"
- Site tagline: "Your Partner in Success"
- Contact email: info@businesspro.com
- Contact phone: +1 (555) 123-4567
- Social media links (Facebook, Twitter, LinkedIn, Instagram)
- SEO settings

### 2. Pages
- **Home Page** - Welcome page with company overview
- **About Page** - Company mission, vision, and values
- **Contact Page** - Contact information and form
- **Privacy Policy** - Privacy policy page
- **Terms & Conditions** - Terms and conditions page

### 3. Menu Items
- Header menu: Home, About, Services, Products, Blog, Contact
- Footer menu: Privacy Policy, Terms & Conditions

### 4. Categories
**Service Categories:**
- Consulting
- Technology
- Marketing

**Product Categories:**
- Software
- Hardware
- Solutions

**Post Categories:**
- News
- Tips & Tricks
- Case Studies

### 5. Tags
Tags for services, products, and posts: Business, Technology, Innovation, Strategy, Growth, Digital, Marketing, Sales

### 6. Services (4 services)
1. **Business Consulting**
   - Price: Starting from $2,500/month
   - Categories: Consulting
   - Tags: Business, Strategy, Growth

2. **Digital Marketing**
   - Price: Starting from $1,500/month
   - Categories: Marketing
   - Tags: Marketing, Digital, Growth

3. **IT Solutions**
   - Price: Starting from $3,000/month
   - Categories: Technology
   - Tags: Technology, Innovation, Digital

4. **Financial Planning**
   - Price: Starting from $2,000/month
   - Categories: Consulting
   - Tags: Business, Strategy

### 7. Products (3 products)
1. **Business Management Suite** - $2,999
   - SKU: BMS-001
   - Featured product
   - Categories: Software

2. **Marketing Automation Platform** - $1,999
   - SKU: MAP-002
   - Featured product
   - Categories: Software

3. **Enterprise Security Package** - $4,999
   - SKU: ESP-003
   - Categories: Solutions

### 8. Portfolio (3 projects)
1. **E-Commerce Platform Redesign**
   - Client: Retail Solutions Inc.
   - Industry: E-Commerce
   - Results: 300% increase in sales

2. **Digital Transformation for Manufacturing**
   - Client: Manufacturing Corp
   - Industry: Manufacturing
   - Results: 40% efficiency improvement

3. **Cloud Migration Project**
   - Client: Tech Enterprises
   - Industry: Technology
   - Results: 35% cost reduction

### 9. Blog Posts (3 posts)
1. **10 Ways to Improve Your Business Efficiency**
   - Published: 5 days ago
   - Category: Tips & Tricks

2. **The Future of Digital Marketing**
   - Published: 12 days ago
   - Category: News

3. **Case Study: Successful Business Transformation**
   - Published: 20 days ago
   - Category: Case Studies

### 10. FAQs (5 FAQs)
- What services do you offer? (General)
- How long does a typical project take? (General)
- Do you offer ongoing support? (Support)
- What is your pricing model? (Pricing)
- Can you work with businesses of all sizes? (General)

### 11. Branches (2 locations)
1. **New York Office**
   - Address: 123 Business Street, Suite 100
   - City: New York, NY 10001
   - Phone: +1 (555) 123-4567
   - Email: ny@businesspro.com

2. **Los Angeles Office**
   - Address: 456 Corporate Boulevard, Floor 5
   - City: Los Angeles, CA 90001
   - Phone: +1 (555) 987-6543
   - Email: la@businesspro.com

### 12. Careers (2 job openings)
1. **Senior Business Consultant**
   - Department: Consulting
   - Location: New York, NY
   - Type: Full-time
   - Deadline: 2 months from now

2. **Digital Marketing Specialist**
   - Department: Marketing
   - Location: Los Angeles, CA
   - Type: Full-time
   - Deadline: 1 month from now

### 13. Events (2 upcoming events)
1. **Business Growth Summit 2024**
   - Date: 2 months from now
   - Venue: Grand Conference Center
   - Max Attendees: 200

2. **Digital Transformation Workshop**
   - Date: 3 months from now
   - Venue: Tech Hub Los Angeles
   - Max Attendees: 50

### 14. Sample Leads (3 leads)
1. **Contact Inquiry** - John Doe (2 days ago)
   - Status: New
   - Subject: Inquiry about Services

2. **Quote Request** - Jane Smith (5 days ago)
   - Status: In Progress
   - Subject: Request for Quote - Digital Marketing

3. **General Inquiry** - Robert Johnson (10 days ago)
   - Status: Completed
   - Subject: General Inquiry

## Modules Enabled

The following modules are automatically enabled when seeding:
- ✅ Services
- ✅ Products
- ✅ Portfolio
- ✅ Blog
- ✅ FAQ
- ✅ Branches
- ✅ Careers
- ✅ Events

## Admin Credentials

After seeding, you can login with:
- **Email**: admin@example.com
- **Password**: password

## Viewing the Demo Data

### Public Website
- Homepage: `/`
- Services: `/services`
- Products: `/products`
- About: `/about`
- Contact: `/contact`
- Blog: `/blog` (when implemented)

### Admin Panel
- Login: `/admin/login`
- Dashboard: `/admin/dashboard`
- Pages: `/admin/pages`
- Services: `/admin/services`
- Products: `/admin/products`
- Leads: `/admin/leads`
- Settings: `/admin/settings`

## Notes

- All demo data is published and visible on the public website
- Categories and tags are automatically assigned to services, products, and posts
- Dates are relative to the current date (using Carbon)
- You can modify or add more demo data by editing `database/seeders/DemoDataSeeder.php`

