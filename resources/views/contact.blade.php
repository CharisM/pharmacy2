@extends('layouts.app')
@section('title', 'Contact – MediCare Pharmacy')

@section('content')
<section class="page-header">
    <div class="page-panel">
        <h1>Get in <span>Touch</span></h1>
        <p>Have a question about your order, prescription, or just need health advice? We’re here to help.</p>
    </div>
</section>

<section class="section">
    <div class="section-inner contact-grid">
        <div class="contact-info">
            <div class="info-card">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="info-icon">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.91a16 16 0 0 0 6.06 6.06l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <strong>Phone</strong>
                <span>+1 (555) 123-4567</span>
            </div>
            <div class="info-card">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="info-icon">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
                <strong>Email</strong>
                <span>hello@medicare.com</span>
            </div>
            <div class="info-card">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="info-icon">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                <strong>Address</strong>
                <span>123 Health Street, New York, NY 10001</span>
            </div>
            <div class="info-card">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="info-icon">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                <strong>Hours</strong>
                <span>Mon - Sun: 8:00 AM - 10:00 PM</span>
            </div>
        </div>

        <div class="contact-card">
            <h2>Send us a message</h2>
            <form action="#" method="GET">
                <label>Name</label>
                <input type="text" placeholder="Your name" />
                <label>Email</label>
                <input type="email" placeholder="you@example.com" />
                <label>Message</label>
                <textarea placeholder="Your message"></textarea>
                <button type="submit" class="btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
