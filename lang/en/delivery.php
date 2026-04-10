<?php

return [

    // General / Layout
    'panel_title'       => 'Delivery Panel',
    'delivery_hub'      => 'Delivery Hub',
    'delivery_partner'  => 'Delivery Partner',
    'dashboard'         => 'Dashboard',
    'logout'            => 'Logout',

    // Sidebar nav
    'nav_dashboard'        => 'Dashboard',
    'nav_assigned_orders'  => 'Assigned Orders',
    'nav_completed_orders' => 'Completed Orders',
    'nav_settings'         => 'Settings',

    // Dashboard page
    'track_deliveries'      => 'Track your deliveries and performance',
    'assigned_orders'       => 'Assigned Orders',
    'pending_delivery'      => 'Pending delivery',
    'picked_up'             => 'Picked Up',
    'collected_from_vendor' => 'Collected from vendor',
    'on_the_way'            => 'On The Way',
    'in_transit'            => 'In transit to customer',
    'completed'             => 'Completed',
    'awaiting_verification' => 'Awaiting verification',
    'delivered_paid'        => 'Delivered & Paid',
    'successfully_completed' => 'Successfully completed',
    'recent_orders'         => 'Recent Orders',
    'view_all'              => 'View All',
    'no_orders_yet'         => 'No orders assigned yet.',

    // Table headers
    'order_id'     => 'Order ID',
    'order'        => 'Order',
    'customer'     => 'Customer',
    'phone'        => 'Phone',
    'address'      => 'Address',
    'items'        => 'Items',
    'amount'       => 'Amount',
    'status'       => 'Status',
    'action'       => 'Action',
    'delivered_on' => 'Delivered On',
    'delivered'    => 'Delivered',
    'payment'      => 'Payment',
    'item_count'   => ':count item(s)',

    // Orders page
    'manage_assignments'  => 'Manage your current delivery assignments',
    'active_orders'       => 'Active Orders',
    'update_status_hint'  => 'Update status as you pick up and deliver orders',
    'pick_up'             => 'Pick Up',
    'verify_pay'          => 'Verify & Pay',
    'no_assigned_orders'  => 'No assigned orders.',

    // Completed page
    'view_completed_subtitle' => 'View all your successfully delivered orders',
    'delivered_orders'        => 'Delivered Orders',
    'completed_history'       => 'History of all completed deliveries',
    'no_completed_yet'        => 'No completed deliveries yet.',

    // Verify page
    'verify_delivery'    => 'Verify Delivery',
    'confirm_otp_sig'    => 'Confirm delivery with OTP & signature',
    'order_details'      => 'Order Details',
    'order_number'       => 'Order Number',
    'verify_customer_otp' => 'Verify Customer OTP',
    'otp_info'           => 'An OTP will be sent to the customer\'s registered phone number',
    'send_otp'           => 'Send OTP',
    'verify_otp'         => 'Verify OTP',
    'resend_otp'         => 'Resend OTP',
    'customer_signature' => 'Customer Signature',
    'sign_instruction'   => 'Ask the customer to sign below to confirm receipt of the order.',
    'clear'              => 'Clear',
    'confirm_delivery'   => 'Confirm Delivery',
    'order_delivered_paid' => 'Order Delivered & Paid!',
    'order_delivered_msg'  => 'Order #:number has been marked as delivered and payment confirmed.',
    'back_to_orders'       => 'Back to Orders',

    // Verify JS strings
    'sending'               => 'Sending...',
    'verifying'             => 'Verifying...',
    'failed_send_otp'       => 'Failed to send OTP.',
    'enter_complete_otp'    => 'Please enter the complete 6-digit OTP.',
    'verification_failed'   => 'Verification failed.',
    'signature_required'    => 'Please get the customer signature first.',
    'failed_confirm'        => 'Failed to confirm delivery.',
    'failed_update_status'  => 'Failed to update status.',

    // Settings page
    'settings'              => 'Settings',
    'manage_preferences'    => 'Manage your profile and preferences',
    'profile_settings'      => 'Profile Settings',
    'update_account_info'   => 'Update your account information',
    'full_name'             => 'Full Name',
    'email'                 => 'Email',
    'contact_number'        => 'Contact Number',
    'save_settings'         => 'Save Settings',

    // Language switcher
    'language'              => 'Language',
    'language_subtitle'     => 'Choose your preferred language',
    'language_english'      => 'English',
    'language_hindi'        => 'हिन्दी',
    'language_changed'      => 'Language changed successfully.',

    // Controller messages
    'unauthorized'              => 'Unauthorized',
    'unauthorized_access'       => 'Unauthorized access.',
    'must_complete_to_verify'   => 'Order must be marked as completed to verify delivery.',
    'otp_sent'                  => 'OTP sent to registered number.',
    'otp_generated_fallback'    => 'OTP generated (SMS service unavailable). OTP: :otp',
    'invalid_otp'               => 'Invalid OTP.',
    'otp_expired'               => 'OTP has expired. Please request a new one.',
    'otp_verified'              => 'OTP verified. Please collect customer signature.',
    'otp_not_verified'          => 'OTP not verified yet.',
    'order_delivered_success'   => 'Order delivered successfully!',
    'settings_saved'            => 'Settings saved successfully.',
    'sms_otp_message'           => 'Your delivery OTP for order #:number is: :otp. Valid for 10 minutes. Do not share this with anyone.',
];
