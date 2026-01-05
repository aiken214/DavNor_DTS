# Document Tracking System (DTS)

The Document Tracking System (DTS) is a robust and efficient web application developed using the Laravel 11 framework. It is designed to streamline the process of managing and tracking documents through various stages of processing, ensuring that organizations can handle their document workflows with ease, accuracy, and security.

## Features

- **QR Code Scanning**: The DTS includes a powerful QR code scanning feature, allowing users to quickly access document details, update statuses, and forward documents with a simple scan. This feature enhances the accuracy and speed of document management, reducing manual data entry and minimizing errors.

- **Secure and Scalable**: Built on Laravel 11, the DTS benefits from advanced security features like CSRF protection, encryption, and secure authentication. The system is also designed to scale, capable of handling large volumes of documents efficiently.

- **Modular Architecture**: The application is organized into distinct modules, each responsible for specific aspects of document tracking. This modular design makes the codebase clean, maintainable, and easy to extend.

- **Responsive Design**: The DTS integrates modern front-end technologies, including Bootstrap 5, to provide a responsive and intuitive user interface. Users can access the system on various devices, including desktops, tablets, and smartphones.

- **Database Management**: Utilizing Laravel's Eloquent ORM, the DTS offers powerful database management capabilities, making it easy to perform complex queries, data manipulations, and manage database schemas.

## Installation

To get started with the Document Tracking System, follow these steps:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/stephenpascual/newdts
    ```

2. **Install dependencies**:
    ```bash
    cd newdts
    composer install
    npm install
    ```

3. **Set up the environment file**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure your database**:
    Update the `.env` file with your database credentials.

5. **Run migrations and seed the database**:
    ```bash
    php artisan migrate:fresh --seed
    ```

6. **Serve the application**:
    ```bash
    php artisan serve
    ```

7. Access the application at `http://127.0.0.1:8000`.

## Support This Project via GCash

If you appreciate the work put into this project and would like to contribute, you can send a donation via GCash. Every bit of support helps keep this project alive and well-maintained.

**GCash Number**: 09081537916

**GCash QR Code**: ![GCash QR Code](path_to_qr_code_image)

Thank you for your generosity and support!


## Acknowledgments

- [Laravel](https://laravel.com/)
- [Bootstrap](https://getbootstrap.com/)
- [QR Code Generator](https://github.com/endroid/qr-code)
