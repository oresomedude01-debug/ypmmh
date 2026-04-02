@php
  $defaultTitle = "YPMMH - Young Productive Muslim Mentoring Hub | Islamic Guidance & Counseling";
  $defaultDescription = "YPMMH (Young Productive Muslim Mentoring Hub) is Nigeria's leading Islamic mentoring platform for children and young Muslims. Expert counseling, leadership training, and value-based guidance to build confident, purpose-driven youth. Visit ypmmh.com.ng to join today.";
  $defaultKeywords = implode(', ', [
    // Brand name variations (all user-specified searches)
    "YPMMH", "ypmmh", "YPMH", "ypmh", "PMMH", "pmmh", "YPMM", "ypmm",
    "ypmmh.com", "YPMMH.com", "ypmmh.com.ng", "YPMMH.com.ng",
    "ypmh.com", "YPMH.com",
    "young productive muslim mentoring hub",
    "Young Productive Muslim Mentoring Hub",
    // Islamic mentorship & counseling
    "islamic mentorship", "islamic mentoring", "islamic counseling", "islamic guidance",
    "muslim youth mentoring", "muslim children mentoring", "muslim mentorship program",
    "islamic leadership training", "muslim leadership development",
    "quran mentoring", "islamic education platform", "muslim community hub",
    // Youth & children specific
    "children counselling Nigeria", "child counseling Nigeria", "youth mentoring Nigeria",
    "islamic children program", "muslim youth program", "muslim teens mentoring",
    "young muslims Nigeria", "muslim kids mentorship",
    // Purpose & values
    "purpose-driven youth", "value-based education", "character development muslim",
    "islamic values children", "productive muslim", "muslim productivity",
    // Parenting
    "muslim parenting Nigeria", "islamic parenting", "raising muslim children",
    "parenting guidance islamic", "muslim family mentoring",
    // Platform type
    "mentoring hub Nigeria", "online islamic mentoring", "islamic counseling platform",
    "muslim mentoring app", "ypmmh app", "ypmmh platform",
    // Location
    "Nigeria islamic mentoring", "Nigeria muslim mentorship", "Nigeria youth counseling",
  ]);
  $defaultImage = asset('images/og-image.jpg');

  // Check if sections are defined, otherwise use defaults
  $pageTitle       = trim($__env->yieldContent('title', $defaultTitle));
  $pageDescription = trim($__env->yieldContent('description', $defaultDescription));
  $pageKeywords    = trim($__env->yieldContent('keywords', $defaultKeywords));
  $pageImage       = trim($__env->yieldContent('image', $defaultImage));
  $currentUrl      = url()->current();

  // Alternate brand name labels for structured data
  $alternateNames = ["YPMMH", "YPMH", "PMMH", "YPMM", "ypmmh.com", "ypmh.com", "Young Productive Muslim Mentoring Hub"];
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- Primary SEO Tags --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<meta name="keywords" content="{{ $pageKeywords }}">
<meta name="author" content="YPMMH - Young Productive Muslim Mentoring Hub">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="googlebot" content="index, follow">
<link rel="canonical" href="{{ $currentUrl }}">

{{-- Brand & Identity --}}
<meta name="application-name" content="YPMMH">
<meta name="generator" content="YPMMH Platform">
<meta name="rating" content="general">
<meta name="revisit-after" content="3 days">
<meta name="language" content="English">
<meta name="geo.region" content="NG">
<meta name="geo.placename" content="Nigeria">

{{-- Open Graph / Facebook / WhatsApp --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:image:alt" content="YPMMH - Young Productive Muslim Mentoring Hub">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="YPMMH - Young Productive Muslim Mentoring Hub">
<meta property="og:locale" content="en_NG">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@ypmmh">
<meta name="twitter:creator" content="@ypmmh">
<meta name="twitter:url" content="{{ $currentUrl }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">
<meta name="twitter:image:alt" content="YPMMH - Young Productive Muslim Mentoring Hub">

{{-- WhatsApp / Telegram Preview Enhancement --}}
<meta property="og:image:type" content="image/jpeg">

{{-- Structured Data: Organization (covers all brand name variants) --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": ["EducationalOrganization", "Organization"],
  "name": "Young Productive Muslim Mentoring Hub",
  "alternateName": @json($alternateNames),
  "url": "https://ypmmh.com.ng",
  "logo": {
    "@@type": "ImageObject",
    "url": "{{ asset('logo.png') }}",
    "width": 512,
    "height": 512
  },
  "image": "{{ $defaultImage }}",
  "description": "{{ $defaultDescription }}",
  "foundingDate": "2023",
  "areaServed": {
    "@@type": "Country",
    "name": "Nigeria"
  },
  "sameAs": [
    "https://ypmmh.com.ng",
    "https://www.ypmmh.com.ng",
    "https://facebook.com/ypmmh",
    "https://twitter.com/ypmmh",
    "https://instagram.com/ypmmh"
  ],
  "address": {
    "@@type": "PostalAddress",
    "addressCountry": "NG"
  },
  "contactPoint": {
    "@@type": "ContactPoint",
    "contactType": "customer support",
    "email": "info@ypmmh.com.ng"
  },
  "offers": {
    "@@type": "Offer",
    "category": "Mentorship Programs",
    "description": "Islamic Mentorship for Children and Young Muslims"
  },
  "keywords": "YPMMH, ypmmh, YPMH, ypmh, PMMH, pmmh, YPMM, ypmm, ypmmh.com, ypmh.com, young productive muslim mentoring hub, islamic mentorship Nigeria"
}
</script>

{{-- Structured Data: WebSite with SearchAction (Sitelinks Searchbox) --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "YPMMH",
  "alternateName": @json($alternateNames),
  "url": "https://ypmmh.com.ng",
  "potentialAction": {
    "@@type": "SearchAction",
    "target": {
      "@@type": "EntryPoint",
      "urlTemplate": "https://ypmmh.com.ng/programs?search={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>

{{-- Structured Data: BreadcrumbList (Homepage) --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://ypmmh.com.ng"
    }
  ]
}
</script>

{{-- Structured Data: FAQPage (helps appear in "People also ask") --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    {
      "@@type": "Question",
      "name": "What is YPMMH?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "YPMMH stands for Young Productive Muslim Mentoring Hub. It is Nigeria's premier Islamic mentoring and counseling platform designed to guide children and young Muslims through expert mentorship, leadership training, and value-based education."
      }
    },
    {
      "@@type": "Question",
      "name": "What does YPMMH stand for?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "YPMMH stands for Young Productive Muslim Mentoring Hub — an Islamic mentoring platform based in Nigeria that provides guidance, counseling, and leadership programs for children and youth."
      }
    },
    {
      "@@type": "Question",
      "name": "What is ypmmh.com?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "ypmmh.com (also at ypmmh.com.ng) is the official website of the Young Productive Muslim Mentoring Hub — an Islamic mentorship platform for Nigerian Muslim youth offering expert counseling, leadership programs, and spiritual development."
      }
    },
    {
      "@@type": "Question",
      "name": "What is ypmh or ypmh.com?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "YPMH is another common abbreviated reference to YPMMH (Young Productive Muslim Mentoring Hub), the leading Islamic mentoring hub for children and young Muslims in Nigeria. Visit ypmmh.com.ng for the official site."
      }
    },
    {
      "@@type": "Question",
      "name": "How can I join YPMMH?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "You can join YPMMH by registering on the official website at ypmmh.com.ng. Create an account to access mentoring sessions, Islamic counseling, leadership programs, and more for your child or yourself."
      }
    },
    {
      "@@type": "Question",
      "name": "Is YPMMH free?",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "YPMMH offers both free and premium mentoring plans. Visit ypmmh.com.ng to explore membership options and find a plan that suits your family's needs."
      }
    }
  ]
}
</script>
