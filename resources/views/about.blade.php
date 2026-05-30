@extends('layouts.app')
@section('title', 'About – MediCare Pharmacy')

@section('content')
<section class="page-header">
    <div class="page-panel">
        <h1>Caring for your health, <span>since 2010.</span></h1>
        <p>MediCare Pharmacy is built on the belief that everyone deserves access to genuine medicines and trusted health advice. From prescription refills to wellness essentials, we’re your partner in better living.</p>
    </div>
</section>

<section class="section">
    <div class="section-inner stats-grid">
        <div class="stat-card">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <strong>50K+</strong>
            <span>Happy Customers</span>
        </div>
        <div class="stat-card">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <path d="M12 3v18M3 12h18"/>
            </svg>
            <strong>15+</strong>
            <span>Years in Business</span>
        </div>
        <div class="stat-card">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <strong>1M+</strong>
            <span>Prescriptions Delivered</span>
        </div>
        <div class="stat-card">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="stat-icon">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
            </svg>
            <strong>120+</strong>
            <span>Cities Served</span>
        </div>
    </div>

    <div class="values-grid">
        <div class="value-card">
            <h3>Authenticity</h3>
            <p>Every product is sourced from licensed manufacturers and verified for quality.</p>
        </div>
        <div class="value-card">
            <h3>Care</h3>
            <p>Our pharmacists are available around the clock to guide you with your health concerns.</p>
        </div>
        <div class="value-card">
            <h3>Accessibility</h3>
            <p>Affordable medicines delivered to your doorstep, wherever you are.</p>
        </div>
    </div>

    <div class="mission-panel">
        <h2>Our Mission</h2>
        <p>To make healthcare simple, affordable, and trustworthy — one prescription at a time.</p>
    </div>
</section>
@endsection
