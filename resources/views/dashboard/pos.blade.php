<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Ticket Booking - Cinema Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Responsive Design Framework -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<style>
    :root {
        --primary: rgba(44, 62, 80, 0.72);
        --primary-dark: rgba(34, 49, 63, 0.85);
        --primary-light: rgba(44, 62, 80, 0.1);
        --success: #2ecc71;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
    }

    html {
        overflow-x: hidden;
        width: 100%;
        max-width: 100vw;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: white;
        color: var(--text-primary);
        overflow-x: hidden;
        width: 100%;
        max-width: 100vw;
        margin: 0;
        padding: 0;
    }

    /* Logo Styles */
    .logo {
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .logo-image {
        width: 80px;
        height: 80px;
        transition: transform 0.3s ease;
    }

    .logo:hover .logo-image {
        transform: scale(1.1);
    }

    .logo-text {
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-align: center;
        line-height: 1.2;
    }

    .text-blue {
        color: var(--primary);
        transition: color 0.3s ease;
    }

    .text-green {
        color: var(--success);
        transition: color 0.3s ease;
    }

    .logo:hover .text-blue {
        color: var(--primary-dark);
    }

    .logo:hover .text-green {
        color: #27ae60;
    }

    /* Profile Styles */
    .profile-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-btn {
        background: none;
        border: none;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .profile-btn:hover {
        transform: scale(1.1);

    }

    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-light);
        transition: all 0.3s ease;
    }

    .profile-btn:hover .profile-img {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }

    .profile-name {
        font-weight: 500;
        color: var(--text-primary);
        margin: 0;
        transition: all 0.3s ease;
    }

    .profile-btn:hover .profile-name {
        color: var(--primary);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        padding: 0.5rem;
        max-height: none !important; /* Remove height restriction */
        height: auto !important; /* Force auto height */
        overflow: visible !important; /* Remove scroll */
        overflow-y: visible !important; /* Force visible Y overflow */
        overflow-x: visible !important; /* Force visible X overflow */
    }

    /* Simple dropdown without Bootstrap JS - ABSOLUTELY NO SCROLL */
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        left: auto;
        z-index: 1000;
        min-width: 160px;
        width: 160px;
        height: 60px !important;
        max-height: 60px !important;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        overflow: hidden !important;
        overflow-y: hidden !important;
        overflow-x: hidden !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    /* Hide scrollbar completely */
    .dropdown-menu::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    .dropdown-menu.show {
        display: block !important;
    }

    .dropdown {
        position: relative;
    }

    /* Target the specific profile dropdown */
    .profile-section .dropdown-menu,
    .profile-section .dropdown-menu.show,
    ul.dropdown-menu.dropdown-menu-end {
        overflow: hidden !important;
        overflow-y: hidden !important;
        overflow-x: hidden !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    .profile-section .dropdown-menu::-webkit-scrollbar,
    ul.dropdown-menu.dropdown-menu-end::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        color: var(--text-primary);
        height: 60px !important;
        display: flex !important;
        align-items: center !important;
        box-sizing: border-box !important;
    }

    .dropdown-item:hover {
        background-color: var(--primary-light);
        transform: translateX(5px);
        color: var(--primary);
    }

    .dropdown-item i {
        width: 20px;
        color: var(--primary);
    }

    /* Page Title Section */
    .page-title-section {
        padding: 4rem 0;
        margin-bottom: 2.5rem;
        background: white;
        box-shadow: var(--shadow-sm);
        position: relative;
        z-index: 1;
        min-height: 120px;
    }

    .page-title-section::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(44, 62, 80, 0.2) 20%, 
            rgba(44, 62, 80, 0.4) 50%, 
            rgba(44, 62, 80, 0.2) 80%, 
            transparent 100%
        );
    }

    .page-title {
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        font-size: 2rem;
        position: relative;
        padding-left: 1rem;
        line-height: 1.3;
    }

    .page-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: var(--gradient-primary);
        border-radius: 2px;
    }

    /* Main Content */
    .main-container {
        padding: 2rem 0;
        position: relative;
    }

    .main-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(44, 62, 80, 0.1) 20%, 
            rgba(44, 62, 80, 0.2) 50%, 
            rgba(44, 62, 80, 0.1) 80%, 
            transparent 100%
        );
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        background: white;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem;
        border-radius: 15px 15px 0 0 !important;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary);
        border: none;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(44, 62, 80, 0.2);
    }

    .btn-outline-primary {
        border: 2px solid var(--primary);
        color: var(--primary);
    }

    .btn-outline-primary:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Form Controls */
    .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.15);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title-section {
            padding: 2.5rem 0;
            min-height: 100px;
        }
        
        .card {
            margin-bottom: 1rem;
        }

        .logo-text {
            font-size: 1rem;
        }

        .profile-section {
            gap: 0.5rem;
        }

        .profile-name {
            display: none;
        }
    }

    /* Movie card styles */
    .movie-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .movie-card.selected {
        border-color: var(--primary);
        background-color: rgba(var(--primary-rgb), 0.05);
    }
    
    /* Showtime styles */
    .showtime-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .showtime-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .showtime-card.selected {
        border-color: var(--primary);
        background-color: rgba(var(--primary-rgb), 0.05);
    }
    
    /* Seat Map Styling - Responsive */
    .screen-container {
        perspective: 500px;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {
        .screen-container {
            margin-bottom: 30px;
        }
    }
    
    .screen {
        height: 40px;
        background-color: #d5d5d5;
        width: 80%;
        margin: 0 auto;
        transform: rotateX(-30deg);
        box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        text-align: center;
        font-weight: bold;
        color: #333;
        padding-top: 8px;
        border-radius: 5px;
    }
    
    .seat-map {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .seat-row {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 6px;
    }

    .row-label {
        width: 20px;
        text-align: center;
        font-size: 0.8rem;
    }

    @media (min-width: 768px) {
        .seat-row {
            margin-bottom: 8px;
        }

        .row-label {
            width: 30px;
            font-size: 0.9rem;
        }
    }
        font-weight: bold;
        margin-right: 10px;
    }
    
    .seats {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    /* Create a curved cinema layout effect */
    .seat-row:nth-child(odd) .seats {
        transform: perspective(200px) rotateX(2deg);
    }
    
    .seat-row:nth-child(even) .seats {
        transform: perspective(200px) rotateX(1deg);
    }
    
    /* Increase spacing between rows as they go further */
    .seat-row:nth-child(1) { margin-bottom: 5px; }
    .seat-row:nth-child(2) { margin-bottom: 6px; }
    .seat-row:nth-child(3) { margin-bottom: 7px; }
    .seat-row:nth-child(4) { margin-bottom: 8px; }
    .seat-row:nth-child(5) { margin-bottom: 9px; }
    
    /* Seat Styling */
    .seat {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 25px;
        height: 25px;
        margin: 2px;
        border-radius: 4px;
        background-color: #6FCF97; /* Soft Green for Available */
        color: white;
        font-size: 10px;
        font-weight: bold;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s ease;
        overflow: visible;
    }

    @media (min-width: 768px) {
        .seat {
            width: 30px;
            height: 30px;
            margin: 3px;
            border-radius: 5px;
            font-size: 12px;
        }
    }
    
    .seat:not(.booked):hover {
        background-color: #2D9CDB; /* Royal Blue for Selected */
        transform: scale(1.05);
    }
    
    .seat.selected {
        background-color: #2D9CDB; /* Royal Blue for Selected */
        border-bottom-color: #1a7ba8;
        transform: scale(1.05);
    }
    
    .seat.booked {
        background-color: #4F4F4F; /* Charcoal Gray for Booked */
        border-bottom-color: #333333;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .seat.premium {
        background-color: #F2C94C; /* Gold for Premium */
        border-bottom-color: #d9b545;
    }
    
    .seat.premium.selected {
        background-color: #F2C94C; /* Gold for Premium */
        border-bottom-color: #d9b545;
    }
    
    .seat.vip {
        background-color: #EB5757; /* Deep Red for VIP */
        border-bottom-color: #d14d4d;
    }
    
    .seat.vip.selected {
        background-color: #EB5757; /* Deep Red for VIP */
        border-bottom-color: #d14d4d;
    }
    
    /* Seat Legend Styling */
    .seat-icon {
        width: 20px;
        height: 20px;
        border-radius: 4px 4px 0 0;
        margin-right: 5px;
        display: inline-block;
        box-shadow: 0 2px 2px rgba(0,0,0,0.2);
        border-bottom: 3px solid;
    }
    
    .seat-icon.available {
        background-color: #6FCF97; /* Soft Green for Available */
        border-bottom-color: #5db884;
    }
    
    .seat-icon.selected {
        background-color: #2D9CDB; /* Royal Blue for Selected */
        border-bottom-color: #1a7ba8;
    }
    
    .seat-icon.booked {
        background-color: #4F4F4F; /* Charcoal Gray for Booked */
        border-bottom-color: #333333;
    }
    
    .seat-icon.premium {
        background-color: #F2C94C; /* Gold for Premium */
        border-bottom-color: #d9b545;
    }
    
    .seat-icon.vip {
        background-color: #EB5757; /* Deep Red for VIP */
        border-bottom-color: #d14d4d;
    }
    
    .seat-map-legend {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 15px;
        font-size: 0.9rem;
    }
    
    .selected-seat-tag {
        background-color: #e9f0ff;
        border-left: 3px solid #415fb7;
        padding: 5px 10px;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
        border-radius: 3px;
        font-size: 0.9rem;
    }

    /* Animation for seat selection */
    @keyframes seatSelectPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .seat.selected {
        animation: seatSelectPulse 0.3s ease-in-out;
    }
    
    /* Food & Drink styling */
    .concession-card {
        transition: all 0.2s ease;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e5e5e5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .concession-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .concession-img {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .concession-name {
        font-weight: 600;
        margin-top: 10px;
        color: #333;
    }
    
    .concession-price {
        font-weight: 700;
        font-size: 1.1rem;
        color: #28a745;
    }
    
    .quantity-control {
        margin: 10px auto;
        border-radius: 30px;
        overflow: hidden;
    }
    
    .quantity-control .btn {
        border-radius: 0;
        font-weight: bold;
        width: 36px;
        padding: 4px 0;
    }
    
    .quantity-control .form-control {
        border-left: none;
        border-right: none;
        font-weight: 600;
    }
    
    .quantity-control .btn-outline-primary {
        color: #4361ee;
        border-color: #4361ee;
    }
    
    .quantity-control .btn-outline-primary:hover {
        background-color: #4361ee;
        color: white;
    }
    
    /* QR code and receipt styling */
    .qr-code-container {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        padding: 10px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #payment-qr-code {
        max-width: 100%;
        max-height: 100%;
    }
    
    .receipt-card {
        border: 1px solid #e0e0e0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    #receipt-content {
        font-family: 'Courier New', monospace;
    }
    
    .bg-success.rounded-circle {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    .Qr-image {
        width: 200px;
        height: 8=60px;
        transition: transform 0.3s ease;
    }
    
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        
        * {
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        body * {
            visibility: hidden;
        }

        #receipt-content {
            visibility: visible !important;
            position: relative !important;
            width: 80mm !important;
            max-width: 80mm !important;
            margin: auto !important;
            padding: 15px !important;
            background: white !important;
            box-sizing: border-box !important;
            min-height: fit-content !important;
            display: block !important;
        }

        #receipt-content * {
            visibility: visible !important;
            max-width: 100% !important;
        }

        .receipt-card {
            margin: 0 auto !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
        }

        .text-center {
            text-align: center !important;
        }

        h4.mb-1 {
            margin-top: 0 !important;
            padding-top: 0 !important;
            font-size: 18px !important;
            text-align: center !important;
        }

        .movie-details, .customer-details, .order-items {
            margin: 10px 0 !important;
            width: 100% !important;
        }

        .table {
            width: 100% !important;
            margin: 10px 0 !important;
            border-collapse: collapse !important;
        }

        .table th, .table td {
            padding: 5px !important;
            text-align: left !important;
            font-size: 12px !important;
        }

        .table th:last-child, .table td:last-child {
            text-align: right !important;
        }

        #receipt-total {
            font-weight: bold !important;
            color: #28a745 !important;
            text-align: right !important;
        }

        .receipt-footer {
            margin-top: 20px !important;
            text-align: center !important;
            width: 100% !important;
        }

        .receipt-footer img {
            max-width: 50px !important;
            height: auto !important;
            margin: 0 auto !important;
            display: block !important;
        }

        .no-print {
            display: none !important;
        }

        p {
            text-align: center !important;
            margin: 3px 0 !important;
            font-size: 12px !important;
        }

        .small {
            font-size: 11px !important;
        }

        @page {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
    }

    /* Add this class to your receipt footer div */
    .receipt-footer {
        text-align: center;
        margin-top: 30px;
    }

    .seat-price {
        position: absolute;
        bottom: -20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 4px;
        border-radius: 4px;
        font-size: 0.7rem;
        white-space: nowrap;
        z-index: 5;
    }

    /* Clean Header Styles */
    .clean-header {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 2rem 0;
        margin-bottom: 2rem;
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
        overflow-x: hidden;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .logo-section {
        display: flex;
        align-items: center;
    }

    .logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.5rem;
    }

    .header-logo {
        width: 60px;
        height: 60px;
        border-radius: 8px;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .brand-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3f6ad8;
        line-height: 1;
    }

    .brand-suffix {
        font-size: 1.5rem;
        font-weight: 700;
        color: #27ae60;
        line-height: 1;
    }

    .clean-header .profile-section {
        display: flex;
        align-items: center;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #f8f9fa;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        flex-shrink: 0;
        min-width: 0;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .default-avatar {
        background: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .user-name {
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 120px;
    }

    .logout-form {
        margin: 0;
    }

    .logout-btn {
        background: #dc3545;
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    /* Responsive adjustments for sticky header */
    @media (max-width: 768px) {
        .clean-header {
            padding: 1.5rem 0;
        }

        .header-logo {
            width: 50px;
            height: 50px;
        }

        .brand-name, .brand-suffix {
            font-size: 1.2rem;
        }

        .user-name {
            display: none;
        }

        .user-info {
            padding: 0.4rem 0.8rem;
            gap: 0.5rem;
        }
    }
</style>
<body>
    <!-- Clean Header Section -->
    <header class="clean-header">
        <div class="container-fluid" style="padding-left: 1rem; padding-right: 1rem; max-width: 100%; overflow: hidden;">
            <div class="header-content">
                <!-- Logo Section -->
                <div class="logo-section">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="header-logo">
                        <div class="brand-text">
                            <span class="brand-name">TOS-MERL</span>
                            <span class="brand-suffix">RG HY</span>
                        </div>
                    </div>
                </div>

                <!-- Profile Section -->
                <div class="profile-section">
                    <div class="user-info">
                        @if(Auth::guard('admin')->check())
                            <img src="{{ Auth::guard('admin')->user()->profile_image ? asset(Auth::guard('admin')->user()->profile_image) : asset('images/default-avatar.png') }}"
                                 alt="Profile" class="user-avatar">
                            <span class="user-name">{{ Auth::guard('admin')->user()->name }}</span>
                        @elseif(Auth::guard('web')->check())
                            <img src="{{ Auth::guard('web')->user()->profile_image ? asset(Auth::guard('web')->user()->profile_image) : asset('images/default-avatar.png') }}"
                                 alt="Profile" class="user-avatar">
                            <span class="user-name">{{ Auth::guard('web')->user()->name }}</span>
                        @else
                            <div class="user-avatar default-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name">Guest</span>
                        @endif

                        <!-- Simple Logout Button -->
                        <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container-fluid main-container px-2 px-md-4">
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-8 order-2 order-lg-1">
        <!-- Booking Panel -->
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Booking Details</h5>
            </div>
            <div class="card-body">
                <div id="booking-step-1" class="booking-step">
                    <h5 class="mb-3">Step 1: Select Movie</h5>
                    <div class="row g-2 g-md-3">
                        @foreach($movies as $movie)
                        <div class="col-6 col-sm-4 col-md-6 col-lg-4 mb-3">
                            <div class="card movie-card h-100" data-movie-id="{{ $movie->id }}">
                                <div class="card-body text-center">
                                    <div class="movie-poster mb-3 d-flex align-items-center justify-content-center" style=" overflow: hidden; border-radius: 4px;">
                                        @if($movie->image)
                                        <img src="{{ asset($movie->image) }}" alt="{{ $movie->title }}" class="img-fluid">
                                        @else
                                        <i class="fas fa-film fa-3x text-muted"></i>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold">{{ $movie->title }}</h6>
                                    <p class="small text-muted mb-2">{{ $movie->duration }} min | {{ $movie->genre }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div id="booking-step-2" class="booking-step d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Step 2: Select Showtime</h5>
                        <button class="btn btn-sm btn-outline-secondary back-step" data-step="1">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    
                    <div id="showtime-container">
                        <div class="text-center py-4 loading-indicator">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading showtimes...</p>
                        </div>
                        
                        <div id="showtime-list" class="d-none">
                            <!-- Showtimes will be loaded here -->
                        </div>
                    </div>
                </div>
                
                <div id="booking-step-3" class="booking-step d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Step 3: Select Seats</h5>
                        <button class="btn btn-sm btn-outline-secondary back-step" data-step="2">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    
                    <div id="seat-selection-container">
                        <div class="text-center py-4 loading-indicator">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading seats...</p>
                        </div>
                        
                        <div id="seat-map-container" class="d-none">
                            <div class="seat-map-legend mb-4 mt-4">
                                <div class="d-flex flex-wrap justify-content-center">
                                    <div class="legend-item">
                                        <div class="seat-icon available"></div>
                                        <span>Available</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="seat-icon selected"></div>
                                        <span>Selected</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="seat-icon booked"></div>
                                        <span>Booked</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="seat-icon premium"></div>
                                        <span>Premium</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="seat-icon vip"></div>
                                        <span>VIP</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="screen-container text-center">
                                <div class="screen">Screen</div>
                                <p class="text-muted small mt-2">All eyes this way</p>
                            </div>
                            
                            <div id="seat-map" class="mb-4">
                                <!-- Seat map will be loaded here -->
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <button id="reset-seats-btn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Reset Selection
                                </button>
                                <button id="continue-to-food-btn" class="btn btn-primary">
                                    Continue <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="booking-step-4" class="booking-step d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Step 4: Add Food & Drinks (Optional)</h5>
                        <button class="btn btn-sm btn-outline-secondary back-step" data-step="3">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    
                    <!-- Food Section -->
                    <div class="concession-section food-section mb-4">
                        <div class="section-header d-flex align-items-center mb-3">
                            <div class="section-icon me-2">
                                <i class="fas fa-utensils fa-lg text-primary"></i>
                            </div>
                            <h5 class="mb-0">Food Items</h5>
                        </div>
                        <div class="row g-2 g-md-3">
                            @foreach($foodItems as $food)
                            <div class="col-6 col-sm-4 col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 concession-card">
                                    <div class="card-body">
                                        <div class="concession-img mb-2 d-flex justify-content-center">
                                            @if($food->image)
                                            <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="img-fluid" style="height: 100px; object-fit: cover; border-radius: 8px;">
                                            @else
                                            <i class="fas fa-hamburger fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="concession-name text-center">{{ $food->name }}</h6>
                                        <p class="concession-price text-success text-center mb-2">${{ number_format($food->price, 2) }}</p>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="input-group input-group-sm quantity-control" style="width: 110px;">
                                                <button class="btn btn-outline-primary decrease-qty" type="button" data-item-id="{{ $food->id }}" data-item-type="food">-</button>
                                                <input type="text" class="form-control text-center qty-input" value="0" data-item-id="{{ $food->id }}" data-item-type="food" data-item-name="{{ $food->name }}" data-item-price="{{ $food->price }}" readonly>
                                                <button class="btn btn-outline-primary increase-qty" type="button" data-item-id="{{ $food->id }}" data-item-type="food">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Drinks Section -->
                    <div class="concession-section drinks-section mb-4">
                        <div class="section-header d-flex align-items-center mb-3">
                            <div class="section-icon me-2">
                                <i class="fas fa-glass-martini-alt fa-lg text-info"></i>
                            </div>
                            <h5 class="mb-0">Drinks</h5>
                        </div>
                        <div class="row g-2 g-md-3">
                            @foreach($drinks as $drink)
                            <div class="col-6 col-sm-4 col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 concession-card">
                                    <div class="card-body">
                                        <div class="concession-img mb-2 d-flex justify-content-center">
                                            @if($drink->image)
                                            <img src="{{ asset($drink->image) }}" alt="{{ $drink->name }}" class="img-fluid" style="height: 100px; object-fit: cover; border-radius: 8px;">
                                            @else
                                            <i class="fas fa-glass-whiskey fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="concession-name text-center">{{ $drink->name }} ({{ ucfirst($drink->size) }})</h6>
                                        <p class="concession-price text-success text-center mb-2">${{ number_format($drink->price, 2) }}</p>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="input-group input-group-sm quantity-control" style="width: 110px;">
                                                <button class="btn btn-outline-primary decrease-qty" type="button" data-item-id="{{ $drink->id }}" data-item-type="drink">-</button>
                                                <input type="text" class="form-control text-center qty-input" value="0" data-item-id="{{ $drink->id }}" data-item-type="drink" data-item-name="{{ $drink->name }}" data-item-price="{{ $drink->price }}" readonly>
                                                <button class="btn btn-outline-primary increase-qty" type="button" data-item-id="{{ $drink->id }}" data-item-type="drink">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button id="skip-food-btn" class="btn btn-outline-secondary">
                            Skip
                        </button>
                        <button id="continue-to-checkout-btn" class="btn btn-primary">
                            Continue to Checkout <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
                
                <div id="booking-step-5" class="booking-step d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Step 5: Customer Information</h5>
                        <button class="btn btn-sm btn-outline-secondary back-step" data-step="4">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    
                    <form id="customer-info-form">
                        <div class="mb-3">
                            <label for="customer-name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer-name" name="customer_name">
                        </div>
                        <div class="mb-3">
                            <label for="customer-email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="customer-email" name="customer_email">
                        </div>
                        <div class="mb-3">
                            <label for="customer-phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="customer-phone" name="customer_phone">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" id="finalize-booking-btn" class="btn btn-success">
                                <i class="fas fa-check-circle me-1"></i> Complete Booking
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Payment and Confirmation Steps -->
                <div id="booking-step-6" class="booking-step d-none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Payment</h5>
                        <button class="btn btn-sm btn-outline-secondary back-step" data-step="5">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mx-auto text-center">
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                            <h6 class="mb-3">Payment Details</h6>
                                            {{-- image --}}
                                            <img src="{{ asset('images/Qr.jpg') }}" alt="RD Logo" class="Qr-image mb-3">
                                            <p class="text-muted mb-4">Total Amount: <span id="payment-amount" class="fw-bold text-success fs-4"></span></p>
                                    
                                    <!-- Simulated payment actions for demo -->
                                            <div class="d-grid gap-2">
                                                <button type="button" id="simulate-payment-btn" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-money-bill-wave me-1"></i> Process Payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="booking-step-7" class="booking-step d-none">
                    <div class="text-center mb-4">
                        <div class="bg-success text-white p-3 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h4 class="mb-3">Payment Successful!</h4>
                        <p class="text-muted">Your booking has been confirmed.</p>
                    </div>
                    
                    <div class="card shadow-sm mb-4 receipt-card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Booking Receipt</h5>
                                <button id="print-receipt-btn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-print me-1"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="receipt-content">
                                <div class="text-center mb-4">
                                    <h4 class="mb-1">Cinema Booking</h4>
                                    <p class="text-muted small">Transaction ID: <span id="receipt-booking-id"></span></p>
                                </div>
                                
                                <div class="movie-details mb-4">
                                    <h5 id="receipt-movie-title" class="mb-1"></h5>
                                    <p class="mb-1"><strong>Date & Time:</strong> <span id="receipt-showtime"></span></p>
                                    <p class="mb-1"><strong>Hall:</strong> <span id="receipt-hall"></span></p>
                                    <p class="mb-0"><strong>Seats:</strong> <span id="receipt-seats"></span></p>
                                </div>
                                
                                <div class="customer-details mb-4">
                                    <h6 class="border-bottom pb-2 mb-2">Customer Information</h6>
                                    <p class="mb-1"><strong>Name:</strong> <span id="receipt-customer-name"></span></p>
                                    <p class="mb-1"><strong>Email:</strong> <span id="receipt-customer-email"></span></p>
                                    <p class="mb-0"><strong>Phone:</strong> <span id="receipt-customer-phone"></span></p>
                                </div>
                                
                                <div class="order-items mb-4">
                                    <h6 class="border-bottom pb-2 mb-3">Order Details</h6>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="receipt-items">
                                            <!-- Items will be loaded here -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Subtotal:</th>
                                                <th class="text-end" id="receipt-subtotal"></th>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th class="text-end text-success" id="receipt-total"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <p class="small text-muted mb-0">Thank you for your purchase!</p>
                                    <p class="small text-muted">Please present this receipt upon entry.</p>
                                </div>
                                
                                <div class="receipt-footer">
                                    <img src="{{ asset('images/logo.png') }}" alt="Cinema Logo" style="width: 60px; margin-bottom: 10px;">
                                    <p class="small text-muted mb-0">Cinema Management System</p>
                                    <p class="small text-muted">{{ date('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button id="new-booking-from-receipt-btn" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> New Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4 order-1 order-lg-2">
        <!-- Order Summary -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Order Summary</h5>
            </div>
            <div class="card-body">
                <div id="order-summary" class="empty-state text-center py-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <p>No items added yet</p>
                    <p class="text-muted">Select a movie and seats to continue</p>
                </div>
                <div id="order-items" class="d-none">
                    <div class="movie-details mb-3 border-bottom pb-2">
                        <h6 class="fw-bold selected-movie-title">Movie Title</h6>
                        <div class="text-muted selected-movie-details">Duration | Genre</div>
                        <div class="text-primary selected-showtime">Date & Time</div>
                        <div class="text-muted selected-hall">Hall Name</div>
                    </div>
                    
                    <div class="selected-seats mb-3">
                        <h6 class="fw-bold mb-2">Selected Seats</h6>
                        <div id="selected-seats-list" class="d-flex flex-wrap">
                            <!-- Selected seats will appear here -->
                        </div>
                    </div>
                    
                    <div class="selected-concessions mb-3">
                        <h6 class="fw-bold mb-2">Food & Drinks</h6>
                        <ul id="selected-concessions-list" class="list-group list-group-flush">
                            <!-- Food & drinks will appear here -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span id="subtotal-amount">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total:</span>
                    <span id="total-amount" class="fw-bold text-success">$0.00</span>
                </div>
                <button id="complete-booking-btn" class="btn btn-success w-100 d-none">
                    <i class="fas fa-check-circle me-1"></i> Complete Booking
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/pos.js') }}"></script>
<script>
    // Simple dropdown toggle - NO BOOTSTRAP JS, NO SCROLL
    function toggleSimpleDropdown() {
        const dropdown = document.querySelector('.dropdown-menu');
        const isVisible = dropdown.classList.contains('show');

        if (isVisible) {
            dropdown.classList.remove('show');
        } else {
            dropdown.classList.add('show');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            const dropdown = document.querySelector('.dropdown-menu');
            if (dropdown) {
                dropdown.classList.remove('show');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Date button functionality
        document.querySelectorAll('.date-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });
</script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>