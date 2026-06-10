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

            @if(session('success'))
                <div style="margin-bottom:16px;padding:12px 16px;background:#dcfce7;border:1px solid #86efac;border-radius:12px;color:#166534;font-weight:700;font-size:0.9rem;">✅ {{ session('success') }}</div>
            @endif

            @auth
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <label>Name</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" placeholder="Your name" required />
                <label>Email</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" placeholder="you@example.com" required />
                <label>Subject <span style="font-weight:400;color:#94a3b8;font-size:0.85em;">(optional)</span></label>
                <input type="text" name="subject" placeholder="e.g. Order inquiry" />
                <label>Message</label>
                <textarea name="body" placeholder="Your message" required></textarea>
                <button type="submit" class="btn-primary">Send Message</button>
            </form>
            @else
            <p style="color:#475569;margin:0 0 16px;">Please <a href="{{ route('login') }}" style="color:#16a34a;font-weight:700;">log in</a> to send us a message.</p>
            @endauth
        </div>
    </div>
</section>
@endsection
