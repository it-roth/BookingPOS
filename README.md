# 🎬 BookingPOS - Cinema Management & Point of Sale System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-8.x-red?style=for-the-badge&logo=laravel" alt="Laravel 8.x">
  <img src="https://img.shields.io/badge/PHP-7.3%2B-blue?style=for-the-badge&logo=php" alt="PHP 7.3+">
  <img src="https://img.shields.io/badge/Bootstrap-5.1-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap 5.1">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

## 📖 About BookingPOS

**BookingPOS** is a comprehensive cinema management and point-of-sale system built with Laravel 8. It provides a complete solution for cinema operators to manage movie screenings, handle ticket bookings, process concession sales, and streamline their daily operations through an intuitive web-based interface.

### 🎯 Key Features

#### 🎪 **Cinema Management**
- **Movie Management**: Add, edit, and manage movie listings with detailed information
- **Hall Management**: Configure cinema halls with custom seating arrangements
- **Showtime Scheduling**: Create and manage movie showtimes across multiple halls
- **Seat Configuration**: Flexible seating with different types (Regular, Premium, VIP) and pricing

#### 🎫 **Point of Sale System**
- **Interactive Booking Interface**: Step-by-step booking process with real-time seat selection
- **Concession Sales**: Integrated food and beverage sales with the ticket booking
- **Payment Processing**: Streamlined payment workflow with QR code integration
- **Receipt Generation**: Automatic booking confirmation and receipt printing

#### 👥 **User Management**
- **Multi-Role Authentication**: Separate admin and staff user management
- **Customer Information**: Capture and manage customer details for bookings
- **Booking History**: Complete transaction history and customer records

#### 📊 **Business Operations**
- **Real-time Availability**: Live seat availability tracking
- **Booking Management**: Comprehensive booking status and management system
- **Inventory Control**: Food and beverage inventory management
- **Responsive Design**: Fully responsive interface for desktop, tablet, and mobile devices

### 🛠️ Technology Stack

- **Backend**: Laravel 8.x (PHP Framework)
- **Frontend**: Bootstrap 5.1, JavaScript ES6+, Font Awesome
- **Database**: MySQL/PostgreSQL compatible
- **Authentication**: Laravel Sanctum for API authentication
- **Styling**: Custom CSS with responsive design principles

### 🎨 User Experience

BookingPOS features a modern, intuitive interface designed for both cinema staff and customers:

- **Clean Dashboard**: Easy-to-navigate admin panel for daily operations
- **Visual Seat Selection**: Interactive seat maps with real-time availability
- **Mobile-First Design**: Optimized for all screen sizes and devices
- **Fast Performance**: Optimized for quick booking processing during peak hours

### 🏢 Perfect For

- **Independent Cinemas**: Small to medium-sized cinema operations
- **Multiplex Theaters**: Multi-hall cinema complexes
- **Drive-in Theaters**: Outdoor cinema venues
- **Event Venues**: Theaters hosting special events and screenings
- **Educational Institutions**: School and university auditoriums

## 🚀 Quick Start

### Prerequisites

Before installing BookingPOS, ensure your system meets these requirements:

- **PHP**: 7.3 or higher
- **Composer**: Latest version
- **Node.js**: 14.x or higher (for asset compilation)
- **Database**: MySQL 5.7+ or PostgreSQL 9.6+
- **Web Server**: Apache or Nginx

### Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/it-roth/BookingPOS.git
   cd BookingPOS
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   - Configure your database credentials in `.env`
   - Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Assets**
   ```bash
   npm run dev
   # or for production
   npm run production
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access your BookingPOS installation.

### Default Login Credentials

After running the seeders, you can log in with:

- **Admin Panel**:
  - Email: `admin@bookingpos.com`
  - Password: `password`

- **Staff Account**:
  - Email: `staff@bookingpos.com`
  - Password: `password`

> ⚠️ **Important**: Change these default credentials immediately after installation!

## 📱 Features Overview

### Admin Dashboard
- Complete system overview and analytics
- User and role management
- System configuration and settings

### Movie Management
- Add new movies with posters and details
- Set movie duration, genre, and ratings
- Enable/disable movies for booking

### Hall & Seat Management
- Create and configure cinema halls
- Design custom seating layouts
- Set different seat types and pricing

### Booking System
- Real-time seat availability
- Step-by-step booking process
- Customer information capture
- Payment processing integration

### Concession Management
- Food and beverage inventory
- Pricing and availability control
- Integration with booking system

## 🔧 Configuration

### Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME="BookingPOS"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookingpos
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

### File Permissions

Ensure these directories are writable:
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

## 🧪 Testing

Run the test suite to ensure everything is working correctly:

```bash
# Run all tests
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## 📚 Documentation

### API Endpoints

BookingPOS provides RESTful API endpoints for integration:

- `GET /api/movies` - List all movies
- `GET /api/halls/{id}/seats` - Get hall seating layout
- `POST /api/bookings` - Create new booking
- `GET /api/bookings/{id}` - Get booking details

### Database Schema

The system uses the following main entities:
- **Movies**: Movie information and metadata
- **Halls**: Cinema hall configurations
- **Seats**: Individual seat definitions
- **MovieHalls**: Showtime scheduling
- **Bookings**: Customer booking records
- **BookingItems**: Individual booking line items

## 🤝 Contributing

We welcome contributions to BookingPOS! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Ensure responsive design compatibility

## 🐛 Bug Reports & Feature Requests

If you encounter any issues or have suggestions for improvements:

1. Check existing [Issues](https://github.com/yourusername/BookingPOS/issues)
2. Create a new issue with detailed information
3. Include steps to reproduce (for bugs)
4. Provide system information and error logs

## 📄 License

BookingPOS is open-source software licensed under the [MIT License](LICENSE).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- UI components powered by [Bootstrap](https://getbootstrap.com)
- Icons provided by [Font Awesome](https://fontawesome.com)

## 📞 Support

For support and questions:
- 📧 Email: support@bookingpos.com
- 💬 Discord: [Join our community](https://discord.gg/bookingpos)
- 📖 Documentation: [docs.bookingpos.com](https://docs.bookingpos.com)

---

<p align="center">
  Made with ❤️ for cinema operators worldwide
</p>
