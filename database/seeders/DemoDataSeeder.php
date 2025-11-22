<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Menu;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Product;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Lead;
use App\Models\Branch;
use App\Models\Career;
use App\Models\Event;
use App\Models\User;
use App\Models\Module;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Settings
        $this->seedSettings();

        // Seed Pages
        $this->seedPages();

        // Seed Menu Items
        $this->seedMenus();

        // Seed Categories and Tags
        $categories = $this->seedCategories();
        $tags = $this->seedTags();

        // Seed Services (if module enabled or we'll enable it)
        Module::where('name', 'services')->update(['enabled' => true]);
        $this->seedServices($categories['service'], $tags['service']);

        // Seed Products (if module enabled)
        Module::where('name', 'products')->update(['enabled' => true]);
        $this->seedProducts($categories['product'], $tags['product']);

        // Seed Portfolio
        Module::where('name', 'portfolio')->update(['enabled' => true]);
        $this->seedPortfolio();

        // Seed Blog Posts
        Module::where('name', 'blog')->update(['enabled' => true]);
        $this->seedBlogPosts($categories['post'], $tags['post']);

        // Seed FAQs
        Module::where('name', 'faq')->update(['enabled' => true]);
        $this->seedFaqs();

        // Seed Branches
        Module::where('name', 'branches')->update(['enabled' => true]);
        $this->seedBranches();

        // Seed Careers
        Module::where('name', 'careers')->update(['enabled' => true]);
        $this->seedCareers();

        // Seed Events
        Module::where('name', 'events')->update(['enabled' => true]);
        $this->seedEvents();

        // Seed Sample Leads
        $this->seedLeads();

        $this->command->info('Demo data seeded successfully!');
    }

    private function seedSettings(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Business Pro Solutions', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Your Partner in Success', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'info@businesspro.com', 'type' => 'email', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'group' => 'general'],
            ['key' => 'address', 'value' => '123 Business Street, Suite 100, New York, NY 10001', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/businesspro', 'type' => 'url', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/businesspro', 'type' => 'url', 'group' => 'social'],
            ['key' => 'linkedin_url', 'value' => 'https://linkedin.com/company/businesspro', 'type' => 'url', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/businesspro', 'type' => 'url', 'group' => 'social'],
            ['key' => 'meta_title', 'value' => 'Business Pro Solutions - Your Trusted Business Partner', 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'We provide comprehensive business solutions to help your company grow and succeed.', 'type' => 'textarea', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => 'business solutions, consulting, services, products', 'type' => 'text', 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    private function seedPages(): array
    {
        $pages = [
            [
                'title' => 'Welcome to Business Pro Solutions',
                'slug' => 'home',
                'content' => '<h1>Welcome to Business Pro Solutions</h1><p>We are a leading provider of comprehensive business solutions, dedicated to helping companies achieve their goals and drive growth. With years of experience and a team of experts, we deliver results that matter.</p><h2>Why Choose Us?</h2><p>Our commitment to excellence, innovation, and customer satisfaction sets us apart. We work closely with our clients to understand their unique needs and deliver tailored solutions.</p>',
                'page_type' => 'home',
                'published' => true,
                'order' => 0,
                'meta_title' => 'Business Pro Solutions - Home',
                'meta_description' => 'Welcome to Business Pro Solutions. We provide comprehensive business solutions to help your company grow.',
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About Business Pro Solutions</h1><p>Founded in 2015, Business Pro Solutions has been at the forefront of providing innovative business solutions to companies across various industries. Our mission is to empower businesses with the tools and expertise they need to succeed in today\'s competitive market.</p><h2>Our Mission</h2><p>To deliver exceptional value and innovative solutions that drive business growth and success.</p><h2>Our Vision</h2><p>To be the most trusted and preferred partner for businesses seeking comprehensive solutions.</p><h2>Our Values</h2><ul><li>Integrity</li><li>Innovation</li><li>Excellence</li><li>Customer Focus</li></ul>',
                'page_type' => 'about',
                'published' => true,
                'order' => 1,
                'meta_title' => 'About Us - Business Pro Solutions',
                'meta_description' => 'Learn about Business Pro Solutions, our mission, vision, and values.',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Get in Touch</h1><p>We\'d love to hear from you. Send us a message and we\'ll respond as soon as possible.</p><p><strong>Email:</strong> info@businesspro.com<br><strong>Phone:</strong> +1 (555) 123-4567<br><strong>Address:</strong> 123 Business Street, Suite 100, New York, NY 10001</p>',
                'page_type' => 'page',
                'published' => true,
                'order' => 2,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>Last updated: ' . date('F d, Y') . '</p><p>At Business Pro Solutions, we take your privacy seriously. This policy describes how we collect, use, and protect your personal information.</p><h2>Information We Collect</h2><p>We collect information that you provide directly to us, including name, email address, phone number, and other contact details.</p><h2>How We Use Your Information</h2><p>We use your information to provide services, respond to inquiries, and improve our offerings.</p>',
                'page_type' => 'page',
                'published' => true,
                'order' => 10,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'content' => '<h1>Terms & Conditions</h1><p>Last updated: ' . date('F d, Y') . '</p><p>By using our website and services, you agree to these terms and conditions.</p><h2>Use of Services</h2><p>Our services are provided for business purposes. You agree to use them in accordance with applicable laws and regulations.</p><h2>Limitation of Liability</h2><p>We strive to provide accurate information, but we are not liable for any errors or omissions.</p>',
                'page_type' => 'page',
                'published' => true,
                'order' => 11,
            ],
        ];

        $createdPages = [];
        foreach ($pages as $page) {
            $createdPages[$page['slug']] = Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }

        return $createdPages;
    }

    private function seedMenus(): void
    {
        $homePage = Page::where('slug', 'home')->first();
        $aboutPage = Page::where('slug', 'about')->first();
        $contactPage = Page::where('slug', 'contact')->first();

        $menus = [
            // Header Menu
            ['name' => 'header', 'label' => 'Home', 'page_id' => $homePage->id, 'url' => null, 'order' => 1, 'active' => true],
            ['name' => 'header', 'label' => 'About', 'page_id' => $aboutPage->id, 'url' => null, 'order' => 2, 'active' => true],
            ['name' => 'header', 'label' => 'Services', 'page_id' => null, 'url' => '/services', 'order' => 3, 'active' => true],
            ['name' => 'header', 'label' => 'Products', 'page_id' => null, 'url' => '/products', 'order' => 4, 'active' => true],
            ['name' => 'header', 'label' => 'Blog', 'page_id' => null, 'url' => '/blog', 'order' => 5, 'active' => true],
            ['name' => 'header', 'label' => 'Contact', 'page_id' => $contactPage->id, 'url' => null, 'order' => 6, 'active' => true],

            // Footer Menu
            ['name' => 'footer', 'label' => 'Privacy Policy', 'page_id' => Page::where('slug', 'privacy-policy')->first()->id, 'url' => null, 'order' => 1, 'active' => true],
            ['name' => 'footer', 'label' => 'Terms & Conditions', 'page_id' => Page::where('slug', 'terms-conditions')->first()->id, 'url' => null, 'order' => 2, 'active' => true],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name'], 'label' => $menu['label']],
                $menu
            );
        }
    }

    private function seedCategories(): array
    {
        $categories = [];

        // Service Categories
        $categories['service'] = [
            Category::updateOrCreate(
                ['slug' => 'consulting', 'type' => 'service'],
                ['name' => 'Consulting', 'slug' => 'consulting', 'type' => 'service', 'published' => true, 'order' => 1]
            ),
            Category::updateOrCreate(
                ['slug' => 'technology', 'type' => 'service'],
                ['name' => 'Technology', 'slug' => 'technology', 'type' => 'service', 'published' => true, 'order' => 2]
            ),
            Category::updateOrCreate(
                ['slug' => 'marketing', 'type' => 'service'],
                ['name' => 'Marketing', 'slug' => 'marketing', 'type' => 'service', 'published' => true, 'order' => 3]
            ),
        ];

        // Product Categories
        $categories['product'] = [
            Category::updateOrCreate(
                ['slug' => 'software', 'type' => 'product'],
                ['name' => 'Software', 'slug' => 'software', 'type' => 'product', 'published' => true, 'order' => 1]
            ),
            Category::updateOrCreate(
                ['slug' => 'hardware', 'type' => 'product'],
                ['name' => 'Hardware', 'slug' => 'hardware', 'type' => 'product', 'published' => true, 'order' => 2]
            ),
            Category::updateOrCreate(
                ['slug' => 'solutions', 'type' => 'product'],
                ['name' => 'Solutions', 'slug' => 'solutions', 'type' => 'product', 'published' => true, 'order' => 3]
            ),
        ];

        // Post Categories
        $categories['post'] = [
            Category::updateOrCreate(
                ['slug' => 'news', 'type' => 'post'],
                ['name' => 'News', 'slug' => 'news', 'type' => 'post', 'published' => true, 'order' => 1]
            ),
            Category::updateOrCreate(
                ['slug' => 'tips', 'type' => 'post'],
                ['name' => 'Tips & Tricks', 'slug' => 'tips', 'type' => 'post', 'published' => true, 'order' => 2]
            ),
            Category::updateOrCreate(
                ['slug' => 'case-studies', 'type' => 'post'],
                ['name' => 'Case Studies', 'slug' => 'case-studies', 'type' => 'post', 'published' => true, 'order' => 3]
            ),
        ];

        return $categories;
    }

    private function seedTags(): array
    {
        $tags = [];

        $tagNames = ['business', 'technology', 'innovation', 'strategy', 'growth', 'digital', 'marketing', 'sales'];

        // Service Tags
        $tags['service'] = [];
        foreach ($tagNames as $name) {
            $slug = $name . '-service';
            $tags['service'][] = Tag::updateOrCreate(
                ['slug' => $slug],
                ['name' => ucfirst($name), 'slug' => $slug, 'type' => 'service']
            );
        }

        // Product Tags
        $tags['product'] = [];
        foreach ($tagNames as $name) {
            $slug = $name . '-product';
            $tags['product'][] = Tag::updateOrCreate(
                ['slug' => $slug],
                ['name' => ucfirst($name), 'slug' => $slug, 'type' => 'product']
            );
        }

        // Post Tags
        $tags['post'] = [];
        foreach ($tagNames as $name) {
            $slug = $name . '-post';
            $tags['post'][] = Tag::updateOrCreate(
                ['slug' => $slug],
                ['name' => ucfirst($name), 'slug' => $slug, 'type' => 'post']
            );
        }

        return $tags;
    }

    private function seedServices($categories, $tags): void
    {
        $services = [
            [
                'title' => 'Business Consulting',
                'slug' => 'business-consulting',
                'short_description' => 'Expert business consulting services to help your company grow and succeed.',
                'description' => '<p>Our business consulting services provide you with strategic insights and actionable recommendations to drive growth and improve performance. Our experienced consultants work closely with you to understand your unique challenges and develop tailored solutions.</p><h3>What We Offer:</h3><ul><li>Strategic Planning</li><li>Process Improvement</li><li>Organizational Development</li><li>Performance Optimization</li></ul>',
                'icon' => 'mdi-briefcase',
                'price_range' => 'Starting from $2,500/month',
                'features' => ['Expert Consultants', 'Custom Solutions', 'Ongoing Support', 'Proven Results'],
                'benefits' => ['Increased Efficiency', 'Cost Savings', 'Competitive Advantage', 'Scalable Growth'],
                'published' => true,
                'order' => 1,
            ],
            [
                'title' => 'Digital Marketing',
                'slug' => 'digital-marketing',
                'short_description' => 'Comprehensive digital marketing services to boost your online presence.',
                'description' => '<p>Enhance your digital presence with our comprehensive marketing services. We help you reach your target audience, increase brand awareness, and drive conversions through proven digital marketing strategies.</p><h3>Services Include:</h3><ul><li>SEO Optimization</li><li>Social Media Marketing</li><li>Content Marketing</li><li>PPC Advertising</li><li>Email Marketing</li></ul>',
                'icon' => 'mdi-bullhorn',
                'price_range' => 'Starting from $1,500/month',
                'features' => ['Multi-Channel Strategy', 'Analytics & Reporting', 'Content Creation', 'Campaign Management'],
                'benefits' => ['Increased Visibility', 'Higher Engagement', 'More Leads', 'Better ROI'],
                'published' => true,
                'order' => 2,
            ],
            [
                'title' => 'IT Solutions',
                'slug' => 'it-solutions',
                'short_description' => 'Complete IT solutions and support services for modern businesses.',
                'description' => '<p>Keep your business running smoothly with our comprehensive IT solutions. From infrastructure setup to ongoing support, we ensure your technology works for you, not against you.</p><h3>Our IT Services:</h3><ul><li>Cloud Migration</li><li>Network Setup & Maintenance</li><li>Cybersecurity</li><li>IT Support & Helpdesk</li><li>Software Implementation</li></ul>',
                'icon' => 'mdi-server',
                'price_range' => 'Starting from $3,000/month',
                'features' => ['24/7 Support', 'Proactive Monitoring', 'Security Updates', 'Scalable Infrastructure'],
                'benefits' => ['Reduced Downtime', 'Enhanced Security', 'Improved Productivity', 'Cost Efficiency'],
                'published' => true,
                'order' => 3,
            ],
            [
                'title' => 'Financial Planning',
                'slug' => 'financial-planning',
                'short_description' => 'Expert financial planning and advisory services for businesses.',
                'description' => '<p>Navigate the complexities of business finance with our expert planning services. We help you make informed financial decisions, optimize cash flow, and plan for long-term success.</p><h3>Financial Services:</h3><ul><li>Financial Analysis</li><li>Budget Planning</li><li>Cash Flow Management</li><li>Investment Advisory</li><li>Tax Planning</li></ul>',
                'icon' => 'mdi-chart-line',
                'price_range' => 'Starting from $2,000/month',
                'features' => ['Expert Analysts', 'Comprehensive Reports', 'Strategic Advice', 'Regular Reviews'],
                'benefits' => ['Better Financial Health', 'Informed Decisions', 'Optimized Resources', 'Growth Planning'],
                'published' => true,
                'order' => 4,
            ],
        ];

        $createdServices = [];
        foreach ($services as $index => $service) {
            $createdService = Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );

            // Attach categories
            if (isset($categories[$index % count($categories)])) {
                $createdService->categories()->syncWithoutDetaching([$categories[$index % count($categories)]->id]);
            }

            // Attach random tags
            $randomTags = array_slice($tags, 0, rand(2, 4));
            $tagIds = array_map(fn($tag) => $tag->id, $randomTags);
            $createdService->tags()->syncWithoutDetaching($tagIds);
        }
    }

    private function seedProducts($categories, $tags): void
    {
        $products = [
            [
                'title' => 'Business Management Suite',
                'slug' => 'business-management-suite',
                'sku' => 'BMS-001',
                'short_description' => 'All-in-one business management software for small to medium enterprises.',
                'description' => '<p>The Business Management Suite is a comprehensive solution designed to streamline your operations. Manage projects, track inventory, handle finances, and more - all from one platform.</p><h3>Key Features:</h3><ul><li>Project Management</li><li>Inventory Tracking</li><li>Financial Management</li><li>CRM Integration</li><li>Reporting & Analytics</li></ul>',
                'price' => 2999.00,
                'price_range' => '$2,999 - $4,999',
                'show_price' => true,
                'specifications' => ['Cloud-Based', 'Mobile App Available', 'Multi-User Support', 'API Integration'],
                'published' => true,
                'featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'Marketing Automation Platform',
                'slug' => 'marketing-automation-platform',
                'sku' => 'MAP-002',
                'short_description' => 'Automate your marketing campaigns and boost conversions.',
                'description' => '<p>Streamline your marketing efforts with our automation platform. Create, manage, and optimize campaigns across multiple channels with ease.</p><h3>Features:</h3><ul><li>Email Automation</li><li>Social Media Scheduling</li><li>Lead Nurturing</li><li>A/B Testing</li><li>Analytics Dashboard</li></ul>',
                'price' => 1999.00,
                'price_range' => '$1,999 - $3,499',
                'show_price' => true,
                'specifications' => ['Unlimited Contacts', 'Advanced Segmentation', 'Integration Hub', '24/7 Support'],
                'published' => true,
                'featured' => true,
                'order' => 2,
            ],
            [
                'title' => 'Enterprise Security Package',
                'slug' => 'enterprise-security-package',
                'sku' => 'ESP-003',
                'short_description' => 'Comprehensive cybersecurity solution for enterprise businesses.',
                'description' => '<p>Protect your business with our enterprise-grade security package. Advanced threat detection, firewall protection, and compliance management all in one.</p><h3>Protection Includes:</h3><ul><li>Firewall Protection</li><li>Malware Detection</li><li>Intrusion Prevention</li><li>Compliance Tools</li><li>Security Monitoring</li></ul>',
                'price' => 4999.00,
                'price_range' => '$4,999 - $9,999',
                'show_price' => true,
                'specifications' => ['Real-Time Monitoring', 'Automated Updates', 'Compliance Reporting', 'Dedicated Support'],
                'published' => true,
                'featured' => false,
                'order' => 3,
            ],
        ];

        foreach ($products as $index => $product) {
            $createdProduct = Product::updateOrCreate(
                ['slug' => $product['slug']],
                $product
            );

            // Attach categories
            if (isset($categories[$index % count($categories)])) {
                $createdProduct->categories()->syncWithoutDetaching([$categories[$index % count($categories)]->id]);
            }

            // Attach random tags
            $randomTags = array_slice($tags, 0, rand(2, 4));
            $tagIds = array_map(fn($tag) => $tag->id, $randomTags);
            $createdProduct->tags()->syncWithoutDetaching($tagIds);
        }
    }

    private function seedPortfolio(): void
    {
        $portfolio = [
            [
                'title' => 'E-Commerce Platform Redesign',
                'slug' => 'ecommerce-platform-redesign',
                'description' => '<p>Complete redesign and development of a modern e-commerce platform resulting in 300% increase in sales.</p>',
                'client_name' => 'Retail Solutions Inc.',
                'industry' => 'E-Commerce',
                'project_date' => Carbon::now()->subMonths(6),
                'challenge' => 'Outdated platform with poor user experience and low conversion rates.',
                'solution' => 'Modern responsive design, improved checkout process, and mobile optimization.',
                'results' => '300% increase in sales, 50% reduction in bounce rate, improved customer satisfaction.',
                'published' => true,
                'featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'Digital Transformation for Manufacturing',
                'slug' => 'digital-transformation-manufacturing',
                'description' => '<p>Comprehensive digital transformation project that improved efficiency by 40%.</p>',
                'client_name' => 'Manufacturing Corp',
                'industry' => 'Manufacturing',
                'project_date' => Carbon::now()->subMonths(4),
                'challenge' => 'Manual processes causing delays and errors in production.',
                'solution' => 'Implemented automated systems, IoT integration, and real-time monitoring.',
                'results' => '40% efficiency improvement, 30% cost reduction, enhanced quality control.',
                'published' => true,
                'featured' => true,
                'order' => 2,
            ],
            [
                'title' => 'Cloud Migration Project',
                'slug' => 'cloud-migration-project',
                'description' => '<p>Successful migration of enterprise infrastructure to cloud, reducing costs by 35%.</p>',
                'client_name' => 'Tech Enterprises',
                'industry' => 'Technology',
                'project_date' => Carbon::now()->subMonths(8),
                'challenge' => 'High infrastructure costs and limited scalability.',
                'solution' => 'Migrated to cloud infrastructure with automated scaling and backup solutions.',
                'results' => '35% cost reduction, improved scalability, 99.9% uptime.',
                'published' => true,
                'featured' => false,
                'order' => 3,
            ],
        ];

        foreach ($portfolio as $item) {
            Portfolio::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }

    private function seedBlogPosts($categories, $tags): void
    {
        $author = User::where('email', 'admin@example.com')->first();

        $posts = [
            [
                'title' => '10 Ways to Improve Your Business Efficiency',
                'slug' => 'improve-business-efficiency',
                'excerpt' => 'Discover practical strategies to streamline operations and boost productivity in your business.',
                'content' => '<p>Efficiency is key to business success. In this article, we explore ten proven strategies to improve your business operations and drive better results.</p><h2>1. Automate Repetitive Tasks</h2><p>Automation can save countless hours and reduce errors. Identify tasks that can be automated and implement solutions.</p><h2>2. Streamline Communication</h2><p>Effective communication is essential. Use collaboration tools to improve team communication.</p><p>Continue reading for more tips...</p>',
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(5),
                'published' => true,
            ],
            [
                'title' => 'The Future of Digital Marketing',
                'slug' => 'future-of-digital-marketing',
                'excerpt' => 'Exploring emerging trends and technologies shaping the future of digital marketing.',
                'content' => '<p>Digital marketing continues to evolve. Let\'s explore the trends that will shape the industry in the coming years.</p><h2>AI and Machine Learning</h2><p>Artificial intelligence is revolutionizing how we approach marketing, enabling personalization at scale.</p><h2>Voice Search Optimization</h2><p>With the rise of smart speakers, optimizing for voice search is becoming increasingly important.</p>',
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(12),
                'published' => true,
            ],
            [
                'title' => 'Case Study: Successful Business Transformation',
                'slug' => 'case-study-business-transformation',
                'excerpt' => 'How one company achieved remarkable results through strategic business transformation.',
                'content' => '<p>This case study examines how a mid-size company achieved significant growth through strategic transformation.</p><h2>The Challenge</h2><p>The company faced declining market share and operational inefficiencies.</p><h2>The Solution</h2><p>Through comprehensive analysis and strategic planning, we helped implement transformative changes.</p><h2>The Results</h2><p>The results exceeded expectations, with 200% growth in revenue.</p>',
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(20),
                'published' => true,
            ],
        ];

        foreach ($posts as $index => $post) {
            $createdPost = BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );

            // Attach categories
            if (isset($categories[$index % count($categories)])) {
                $createdPost->categories()->syncWithoutDetaching([$categories[$index % count($categories)]->id]);
            }

            // Attach random tags
            $randomTags = array_slice($tags, 0, rand(2, 4));
            $tagIds = array_map(fn($tag) => $tag->id, $randomTags);
            $createdPost->tags()->syncWithoutDetaching($tagIds);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What services do you offer?',
                'answer' => 'We offer a wide range of business services including consulting, digital marketing, IT solutions, and financial planning. Our services are tailored to meet your specific business needs.',
                'category' => 'General',
                'order' => 1,
                'published' => true,
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'Project timelines vary depending on the scope and complexity. Simple projects may take 2-4 weeks, while larger implementations can take 3-6 months. We provide detailed timelines during the consultation phase.',
                'category' => 'General',
                'order' => 2,
                'published' => true,
            ],
            [
                'question' => 'Do you offer ongoing support?',
                'answer' => 'Yes, we offer various support packages to ensure your systems continue to run smoothly. Support options include helpdesk, maintenance, and strategic consulting.',
                'category' => 'Support',
                'order' => 1,
                'published' => true,
            ],
            [
                'question' => 'What is your pricing model?',
                'answer' => 'Our pricing varies based on the services required. We offer both one-time project fees and monthly retainer options. Contact us for a customized quote based on your needs.',
                'category' => 'Pricing',
                'order' => 1,
                'published' => true,
            ],
            [
                'question' => 'Can you work with businesses of all sizes?',
                'answer' => 'Absolutely! We work with startups, small businesses, and large enterprises. Our solutions are scalable and adaptable to businesses of any size.',
                'category' => 'General',
                'order' => 3,
                'published' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }

    private function seedBranches(): void
    {
        $branches = [
            [
                'name' => 'New York Office',
                'slug' => 'new-york-office',
                'address' => '123 Business Street, Suite 100',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'phone' => '+1 (555) 123-4567',
                'email' => 'ny@businesspro.com',
                'opening_hours' => [
                    'monday' => '9:00 AM - 6:00 PM',
                    'tuesday' => '9:00 AM - 6:00 PM',
                    'wednesday' => '9:00 AM - 6:00 PM',
                    'thursday' => '9:00 AM - 6:00 PM',
                    'friday' => '9:00 AM - 5:00 PM',
                    'saturday' => 'Closed',
                    'sunday' => 'Closed',
                ],
                'published' => true,
                'order' => 1,
            ],
            [
                'name' => 'Los Angeles Office',
                'slug' => 'los-angeles-office',
                'address' => '456 Corporate Boulevard, Floor 5',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'USA',
                'phone' => '+1 (555) 987-6543',
                'email' => 'la@businesspro.com',
                'opening_hours' => [
                    'monday' => '8:00 AM - 6:00 PM',
                    'tuesday' => '8:00 AM - 6:00 PM',
                    'wednesday' => '8:00 AM - 6:00 PM',
                    'thursday' => '8:00 AM - 6:00 PM',
                    'friday' => '8:00 AM - 5:00 PM',
                    'saturday' => '10:00 AM - 2:00 PM',
                    'sunday' => 'Closed',
                ],
                'published' => true,
                'order' => 2,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['slug' => $branch['slug']],
                $branch
            );
        }
    }

    private function seedCareers(): void
    {
        $careers = [
            [
                'title' => 'Senior Business Consultant',
                'slug' => 'senior-business-consultant',
                'department' => 'Consulting',
                'location' => 'New York, NY',
                'employment_type' => 'Full-time',
                'description' => '<p>We are seeking an experienced business consultant to join our team.</p>',
                'responsibilities' => '<ul><li>Lead client consulting engagements</li><li>Develop strategic recommendations</li><li>Manage project teams</li><li>Build client relationships</li></ul>',
                'requirements' => '<ul><li>5+ years of consulting experience</li><li>MBA or equivalent</li><li>Strong analytical skills</li><li>Excellent communication</li></ul>',
                'deadline' => Carbon::now()->addMonths(2),
                'published' => true,
                'order' => 1,
            ],
            [
                'title' => 'Digital Marketing Specialist',
                'slug' => 'digital-marketing-specialist',
                'department' => 'Marketing',
                'location' => 'Los Angeles, CA',
                'employment_type' => 'Full-time',
                'description' => '<p>Join our marketing team and help clients grow their digital presence.</p>',
                'responsibilities' => '<ul><li>Develop marketing strategies</li><li>Manage campaigns</li><li>Analyze performance</li><li>Create content</li></ul>',
                'requirements' => '<ul><li>3+ years of digital marketing experience</li><li>Proficiency in SEO/SEM</li><li>Social media expertise</li><li>Analytics skills</li></ul>',
                'deadline' => Carbon::now()->addMonths(1),
                'published' => true,
                'order' => 2,
            ],
        ];

        foreach ($careers as $career) {
            Career::updateOrCreate(
                ['slug' => $career['slug']],
                $career
            );
        }
    }

    private function seedEvents(): void
    {
        $events = [
            [
                'title' => 'Business Growth Summit 2024',
                'slug' => 'business-growth-summit-2024',
                'description' => '<p>Join us for a day of insights, networking, and strategies for business growth.</p>',
                'event_date' => Carbon::now()->addMonths(2),
                'event_time' => Carbon::now()->setTime(9, 0),
                'end_date_time' => Carbon::now()->addMonths(2)->setTime(17, 0),
                'venue' => 'Grand Conference Center',
                'address' => '789 Event Avenue, New York, NY 10001',
                'speakers' => [
                    ['name' => 'John Smith', 'title' => 'CEO, Business Pro'],
                    ['name' => 'Jane Doe', 'title' => 'Marketing Expert'],
                ],
                'allow_registration' => true,
                'max_attendees' => 200,
                'published' => true,
            ],
            [
                'title' => 'Digital Transformation Workshop',
                'slug' => 'digital-transformation-workshop',
                'description' => '<p>Learn how to transform your business with digital technologies.</p>',
                'event_date' => Carbon::now()->addMonths(3),
                'event_time' => Carbon::now()->setTime(10, 0),
                'end_date_time' => Carbon::now()->addMonths(3)->setTime(16, 0),
                'venue' => 'Tech Hub Los Angeles',
                'address' => '321 Innovation Drive, Los Angeles, CA 90001',
                'speakers' => [
                    ['name' => 'Mike Johnson', 'title' => 'CTO, Tech Solutions'],
                ],
                'allow_registration' => true,
                'max_attendees' => 50,
                'published' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['slug' => $event['slug']],
                $event
            );
        }
    }

    private function seedLeads(): void
    {
        $leads = [
            [
                'type' => 'contact',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+1 (555) 111-2222',
                'subject' => 'Inquiry about Services',
                'message' => 'I am interested in learning more about your business consulting services.',
                'status' => 'new',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'type' => 'quote',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+1 (555) 333-4444',
                'subject' => 'Request for Quote - Digital Marketing',
                'message' => 'Please provide a quote for your digital marketing services for our company.',
                'status' => 'in_progress',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'type' => 'contact',
                'name' => 'Robert Johnson',
                'email' => 'robert.j@example.com',
                'phone' => '+1 (555) 555-6666',
                'subject' => 'General Inquiry',
                'message' => 'Would like to schedule a consultation meeting.',
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(10),
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }
    }
}
