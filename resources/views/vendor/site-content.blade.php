@extends('layouts.vendor')

@section('title', 'Site Content')
@section('page_title', 'Site Content & Branding')
@section('page_subtitle', 'Full control over everything customers see')

@section('content')
    <form action="{{ route('vendor.site.content.update') }}" method="POST" enctype="multipart/form-data" class="sa-form">
        @csrf
        @method('PUT')

        <div class="sa-form-grid">
            {{-- Brand / Identity --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-crown"></i> Brand Identity</h3>
                        <p>Logo, favicon, store name & tagline</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label>Store Name</label>
                    <input class="sa-input" type="text" name="store_name" value="{{ old('store_name', $siteSetting->store_name) }}" required>

                    <label>Store Tagline</label>
                    <input class="sa-input" type="text" name="store_tagline" value="{{ old('store_tagline', $siteSetting->store_tagline) }}">

                    <label>Logo</label>
                    <div class="sa-upload">
                        @if($siteSetting->logo_path)
                            <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="Logo" class="sa-upload-preview">
                        @else
                            <div class="sa-upload-preview sa-upload-empty"><i class="fa-solid fa-image"></i></div>
                        @endif
                        <input type="file" name="logo" accept="image/*">
                    </div>

                    <label>Favicon</label>
                    <div class="sa-upload">
                        @if($siteSetting->favicon_path)
                            <img src="{{ asset('storage/' . $siteSetting->favicon_path) }}" alt="Favicon" class="sa-upload-preview">
                        @else
                            <div class="sa-upload-preview sa-upload-empty"><i class="fa-solid fa-star"></i></div>
                        @endif
                        <input type="file" name="favicon" accept="image/*">
                    </div>
                </div>
            </div>

            {{-- Hero Section --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-image"></i> Homepage Hero</h3>
                        <p>Banner image, headline & call-to-action</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label>Hero Image</label>
                    <div class="sa-upload">
                        @if($siteSetting->hero_image_path)
                            <img src="{{ asset('storage/' . $siteSetting->hero_image_path) }}" alt="Hero" class="sa-upload-preview sa-upload-hero">
                        @else
                            <div class="sa-upload-preview sa-upload-hero sa-upload-empty"><i class="fa-solid fa-mountain-sun"></i></div>
                        @endif
                        <input type="file" name="hero_image" accept="image/*">
                    </div>

                    <label>Hero Title</label>
                    <input class="sa-input" type="text" name="hero_title" value="{{ old('hero_title', $siteSetting->hero_title) }}">

                    <label>Hero Subtitle</label>
                    <input class="sa-input" type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $siteSetting->hero_subtitle) }}">

                    <div class="sa-grid-2">
                        <div>
                            <label>CTA Button Text</label>
                            <input class="sa-input" type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $siteSetting->hero_cta_text) }}">
                        </div>
                        <div>
                            <label>CTA Button URL</label>
                            <input class="sa-input" type="text" name="hero_cta_url" value="{{ old('hero_cta_url', $siteSetting->hero_cta_url) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-address-book"></i> Contact Info</h3>
                        <p>Displayed in footer and checkout</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label>Email</label>
                    <input class="sa-input" type="email" name="contact_email" value="{{ old('contact_email', $siteSetting->contact_email) }}">

                    <label>Phone</label>
                    <input class="sa-input" type="text" name="contact_phone" value="{{ old('contact_phone', $siteSetting->contact_phone) }}">

                    <label>Address</label>
                    <input class="sa-input" type="text" name="contact_address" value="{{ old('contact_address', $siteSetting->contact_address) }}">
                </div>
            </div>

            {{-- Social --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-share-nodes"></i> Social Links</h3>
                        <p>URLs for your social presence</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label><i class="fa-brands fa-facebook"></i> Facebook</label>
                    <input class="sa-input" type="text" name="facebook_url" value="{{ old('facebook_url', $siteSetting->facebook_url) }}">
                    <label><i class="fa-brands fa-instagram"></i> Instagram</label>
                    <input class="sa-input" type="text" name="instagram_url" value="{{ old('instagram_url', $siteSetting->instagram_url) }}">
                    <label><i class="fa-brands fa-twitter"></i> Twitter / X</label>
                    <input class="sa-input" type="text" name="twitter_url" value="{{ old('twitter_url', $siteSetting->twitter_url) }}">
                    <label><i class="fa-brands fa-youtube"></i> YouTube</label>
                    <input class="sa-input" type="text" name="youtube_url" value="{{ old('youtube_url', $siteSetting->youtube_url) }}">
                </div>
            </div>

            {{-- Footer --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-anchor"></i> Footer</h3>
                        <p>About text and copyright</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label>About Text</label>
                    <textarea class="sa-input" name="footer_about" rows="4">{{ old('footer_about', $siteSetting->footer_about) }}</textarea>

                    <label>Copyright Line</label>
                    <input class="sa-input" type="text" name="footer_copyright" value="{{ old('footer_copyright', $siteSetting->footer_copyright) }}">
                </div>
            </div>

            {{-- Maintenance --}}
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-triangle-exclamation"></i> Maintenance Mode</h3>
                        <p>Take the storefront offline when needed</p>
                    </div>
                </div>
                <div class="sa-card-body">
                    <label class="sa-switch-row">
                        <input type="hidden" name="maintenance_mode" value="0">
                        <input type="checkbox" name="maintenance_mode" value="1" @checked($siteSetting->maintenance_mode)>
                        <span>Enable Maintenance Mode</span>
                    </label>

                    <label>Maintenance Message</label>
                    <textarea class="sa-input" name="maintenance_message" rows="3">{{ old('maintenance_message', $siteSetting->maintenance_message) }}</textarea>
                </div>
            </div>
        </div>

        <div class="sa-form-actions">
            <button type="submit" class="sa-btn sa-btn-primary"><i class="fa-solid fa-save"></i> Save Site Content</button>
        </div>
    </form>
@endsection
