# Multivendor Marketplace - Complete E-commerce Platform

A complete Laravel-based multivendor e-commerce marketplace with full Arabic (RTL) support.

## Features

### Core Functionality
- ✅ **Multivendor System** - Multiple vendors can register and manage their own shops
- ✅ **Product Management** - Full CRUD with images, inventory tracking, variants
- ✅ **Order Management** - Complete order lifecycle with status tracking
- ✅ **Commission System** - Automatic commission calculation and vendor payouts
- ✅ **Shopping Cart** - Session-based cart for guests, persistent for users
- ✅ **Review System** - Customer reviews with ratings
- ✅ **Multilingual** - Full Arabic and French/English support with RTL

### Payment Methods
- Cash on Delivery (COD) - Primary method for Tunisia
- Credit Card (Integration ready)
- Bank Transfer
- Mobile Payment (Integration ready)

### User Roles
1. **Admin** - Full system control, vendor approval, commission management
2. **Vendor** - Shop management, product CRUD, order fulfillment
3. **Customer** - Shopping, orders, reviews

## Database Schema

### Core Tables
```
users                 - User accounts with role-based access
vendors               - Vendor/Shop information
categories            - Product categories (hierarchical)
products              - Product catalog
product_images        - Multiple images per product
orders                - Customer orders
order_items           - Order line items with commission calculation
cart_items            - Shopping cart (session + user based)
reviews               - Product reviews and ratings
vendor_transactions   - Commission tracking and payouts
```

## Installation

### Requirements
- PHP 8.1+
- MySQL 8.0+ or PostgreSQL
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone and Install Dependencies**
```bash
cd rahoThi3a
composer install
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Configuration**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rahothi3a
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Run Migrations**
```bash
php artisan migrate
```

5. **Create Storage Link**
```bash
php artisan storage:link
```

6. **Seed Database (Optional)**
```bash
php artisan db:seed
```

7. **Compile Assets**
```bash
npm run dev
# or for production
npm run build
```

8. **Start Development Server**
```bash
php artisan serve
```

## Project Structure

```
app/
├── Models/              # Eloquent models
│   ├── User.php
│   ├── Vendor.php
│   ├── Product.php
│   ├── Order.php
│   └── ...
├── Http/
│   ├── Controllers/     # Route controllers
│   └── Middleware/      # Custom middleware
├── Services/            # Business logic
│   ├── CartService.php
│   └── OrderService.php
database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
resources/
├── views/              # Blade templates
└── js/                 # Frontend assets
routes/
├── web.php             # Web routes
└── api.php             # API routes
```

## Key Models

### Product Model
```php
- Full multilingual support (name, name_ar, description, description_ar)
- Inventory tracking with low stock alerts
- SKU management
- Price, compare_price (for discounts), cost
- Rating system integration
- View counter, sales counter
- Soft deletes
```

### Order Model
```php
- Complete order lifecycle management
- Multiple payment methods
- Shipping information
- Order status tracking (pending → confirmed → processing → shipped → delivered)
- Commission calculation
- Vendor-specific order items
```

### Vendor Model
```php
- Shop information (name, logo, banner)
- Commission rate (customizable per vendor)
- Balance tracking
- Approval workflow (pending → approved)
- Transaction history
```

## Commission System

The platform automatically:
1. Calculates commission on each order item
2. Splits payment between vendor and platform
3. Tracks all transactions
4. Maintains vendor balance
5. Supports commission adjustments

**Example:**
- Product price: 100 TND
- Commission rate: 10%
- Platform commission: 10 TND
- Vendor receives: 90 TND

## RTL (Right-to-Left) Support

The system fully supports Arabic RTL:
- All text fields have `_ar` counterparts
- CSS configured for RTL layouts
- Automatic direction switching based on locale
- Arabic-friendly date/number formatting

## Security Features

- ✅ Password hashing
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection
- ✅ Role-based access control
- ✅ Soft deletes for data integrity

## API Endpoints (Planned)

```
GET    /api/products              # List products
GET    /api/products/{id}         # Product details
POST   /api/cart/add              # Add to cart
GET    /api/categories            # List categories
POST   /api/orders                # Create order
GET    /api/vendor/products       # Vendor's products
```

## Payment Integration

### Supported Gateways
- **Cash on Delivery** - ✅ Implemented
- **Credit Card** - Ready for integration (Stripe/PayPal)
- **Bank Transfer** - ✅ Implemented
- **Mobile Money** - Ready for Tunisia-specific providers

## Deployment

### Production Checklist
```bash
# 1. Optimize configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Optimize autoloader
composer install --optimize-autoloader --no-dev

# 3. Set environment
APP_ENV=production
APP_DEBUG=false

# 4. Build assets
npm run build

# 5. Set file permissions
chmod -R 755 storage bootstrap/cache
```

## Tunisian Market Considerations

- **Cash on Delivery** - Primary payment method
- **Multiple images** per product (essential for trust)
- **Phone contact** - Required for orders
- **Arabic content** - Full RTL support
- **Commission system** - Transparent for vendors
- **Tax compliance** - Built-in (19% VAT for Tunisia)

## Roadmap

### Phase 1 (Current)
- [x] Database schema
- [x] Core models
- [x] Cart system
- [x] Order processing
- [x] Commission tracking

### Phase 2 (Next)
- [ ] Admin dashboard
- [ ] Vendor dashboard
- [ ] Customer frontend
- [ ] Authentication & authorization
- [ ] Image upload & management

### Phase 3
- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] SMS notifications (Tunisia)
- [ ] Advanced search & filters
- [ ] Wishlist functionality

### Phase 4
- [ ] Analytics dashboard
- [ ] Reporting system
- [ ] Mobile app (React Native)
- [ ] API v1 release

## Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter ProductTest
```

## Contributing

This is a personal project, but suggestions are welcome!

## License

Proprietary - All rights reserved

## Support

For questions or issues, contact: [your-email]

---

Built with ❤️ for the Tunisian market
