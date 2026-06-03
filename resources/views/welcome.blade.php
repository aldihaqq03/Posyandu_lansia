<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPEL – Sistem Informasi Peduli Lansia</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy: #0c1835;
            --blue-mid: #1e3a6e;
            --blue: #2563eb;
            --blue-light: #3b82f6;
            --blue-pale: #eff6ff;
            --white: #fff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-400: #94a3b8;
            --gray-600: #475569;
            --gray-800: #1e293b;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth
        }

        body {
            font-family: var(--font);
            background: var(--white);
            color: var(--gray-800);
            overflow-x: hidden
        }

        /* NAV */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 72px;
            background: rgba(12, 24, 53, .92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, .06)
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none
        }

        /* ── Logo icon: gambar bulat kecil di navbar ── */
        .nav-logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            box-shadow: 0 6px 20px rgba(37, 99, 235, .4);
            flex-shrink: 0;
        }

        .nav-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        /* fallback icon jika img gagal load */
        .nav-logo-icon .fallback-icon {
            font-size: 20px;
            color: #fff;
            display: none;
        }

        .nav-logo-icon img:not([src]),
        .nav-logo-icon img[src=""] {
            display: none;
        }

        .nav-logo-text {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.5px
        }

        .nav-logo-text span {
            color: #60a5fa
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, .8);
            text-decoration: none;
            padding: 8px 20px;
            border-radius: 10px;
            transition: all .2s
        }

        .nav-links a:hover {
            color: #fff;
            background: rgba(255, 255, 255, .08)
        }

        .btn-nav-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
            color: #fff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(37, 99, 235, .35)
        }

        .btn-nav-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, .45) !important
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            background: linear-gradient(160deg, #f0f7ff 0%, #e8f2ff 40%, #dbeafe 100%);
            display: flex;
            align-items: center;
            padding-top: 72px;
            position: relative;
            overflow: hidden
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, .12) 0%, transparent 70%);
            pointer-events: none
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(12, 24, 53, .08) 0%, transparent 70%);
            pointer-events: none
        }

        .hero-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 60px 48px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            width: 100%
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37, 99, 235, .1);
            border: 1px solid rgba(37, 99, 235, .2);
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 28px
        }

        .hero-title {
            font-size: clamp(36px, 4vw, 56px);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1.5px;
            color: var(--navy);
            margin-bottom: 20px
        }

        .hero-title span {
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text
        }

        .hero-desc {
            font-size: 17px;
            line-height: 1.75;
            color: var(--gray-600);
            margin-bottom: 36px;
            max-width: 520px
        }

        .hero-buttons {
            display: flex;
            gap: 14px;
            flex-wrap: wrap
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(37, 99, 235, .4);
            transition: all .25s;
            border: none;
            cursor: pointer;
            font-family: var(--font)
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 40px rgba(37, 99, 235, .5)
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: #fff;
            color: var(--navy);
            font-size: 15px;
            font-weight: 600;
            border-radius: 14px;
            text-decoration: none;
            border: 1.5px solid var(--gray-200);
            transition: all .25s
        }

        .btn-outline:hover {
            border-color: var(--blue);
            color: var(--blue);
            transform: translateY(-2px)
        }

        .hero-right {
            display: flex;
            justify-content: center;
            align-items: center
        }

        /* ═══════════════════════════════════════
           Hero logo circle — gambar memenuhi bulatan
        ═══════════════════════════════════════ */
        .hero-logo-circle {
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0c1835 0%, #1e3a6e 50%, #2563eb 100%);
            box-shadow:
                0 0 0 20px rgba(37, 99, 235, .08),
                0 0 0 40px rgba(37, 99, 235, .04),
                0 30px 80px rgba(12, 24, 53, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            /* kunci: gambar tidak meluap keluar bulatan */
        }

        .hero-logo-circle::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 255, 255, .06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 1;
        }

        /* Gambar memenuhi circle dengan padding 10% agar ada ruang napas */
        .hero-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
            position: relative;
            z-index: 0;
        }

        /* SECTION COMMONS */
        .section {
            max-width: 1280px;
            margin: 0 auto;
            padding: 100px 48px
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 16px
        }

        .section-title {
            font-size: clamp(28px, 3vw, 44px);
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -1px;
            line-height: 1.15;
            margin-bottom: 16px
        }

        .section-desc {
            font-size: 17px;
            color: var(--gray-600);
            line-height: 1.7;
            max-width: 600px
        }

        /* ABOUT */
        .about-section {
            background: linear-gradient(160deg, #0c1835 0%, #1e3a6e 50%, #2563eb 100%);
            color: #fff;
            padding: 100px 0
        }

        .about-section-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 48px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center
        }

        .about-section .section-label {
            color: #93c5fd
        }

        .about-section .section-title {
            color: #fff
        }

        .about-section .section-desc {
            color: rgba(255, 255, 255, .7)
        }

        /* Hapus about-section-points — tidak digunakan lagi */

        .about-right-visual {
            display: flex;
            flex-direction: column;
            gap: 16px
        }

        .about-visual-card {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 20px;
            padding: 28px
        }

        .about-visual-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px
        }

        .about-visual-card p {
            font-size: 14px;
            color: rgba(255, 255, 255, .65);
            line-height: 1.7
        }

        /* ═══════════════════════════════════════
   FITUR WEBSITE — STICKY SCROLL
   ═══════════════════════════════════════ */
        .wf-wrap {
            background: #f0f4ff
        }

        .wf-header {
            max-width: 1280px;
            margin: 0 auto;
            padding: 100px 48px 60px
        }

        .wf-sticky-shell {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 48px 140px;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 0px;
            align-items: start;
        }

        .wf-sidebar {
            position: sticky;
            top: 96px;
            background: linear-gradient(175deg, #0c1835 0%, #1a346a 55%, #2563eb 100%);
            border-radius: 24px;
            padding: 28px 18px;
            box-shadow: 0 24px 60px rgba(12, 24, 53, .28);
            overflow: hidden;
            min-height: 500px;
        }

        .wf-sidebar::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(96, 165, 250, .12) 0%, transparent 70%);
            pointer-events: none
        }

        .wf-sidebar-lbl {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .4);
            margin-bottom: 18px;
            padding: 0 6px
        }

        .wf-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 14px;
            color: rgba(255, 255, 255, .48);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
            cursor: default;
            position: relative;
            user-select: none;
            transition: color .55s ease
        }

        .wf-ni-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .07);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            transition: background .5s ease, color .5s ease, transform .55s cubic-bezier(.34, 1.56, .64, 1)
        }

        .wf-ni-text {
            line-height: 1.2;
            flex: 1
        }

        .wf-ni-text small {
            font-size: 10px;
            opacity: .5;
            display: block;
            margin-top: 1px
        }

        .wf-ni-bar {
            width: 3px;
            height: 0;
            background: #60a5fa;
            border-radius: 4px;
            margin-left: auto;
            transition: height .65s cubic-bezier(.4, 0, .2, 1);
            flex-shrink: 0
        }

        .wf-nav-item.is-on {
            color: #fff;
            font-weight: 600
        }

        .wf-nav-item.is-on .wf-ni-icon {
            background: rgba(96, 165, 250, .22);
            color: #93c5fd;
            transform: scale(1.1)
        }

        .wf-nav-item.is-on .wf-ni-bar {
            height: 22px
        }

        .wf-prog-wrap {
            margin-top: 22px;
            padding: 0 6px
        }

        .wf-prog-label {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: rgba(255, 255, 255, .3);
            margin-bottom: 6px
        }

        .wf-prog-track {
            height: 3px;
            background: rgba(255, 255, 255, .1);
            border-radius: 4px;
            overflow: hidden
        }

        .wf-prog-fill {
            height: 100%;
            width: 16.67%;
            background: linear-gradient(90deg, #60a5fa, #93c5fd);
            border-radius: 4px;
            transition: width .9s cubic-bezier(.4, 0, .2, 1)
        }

        .wf-panels {}

        .wf-panel {
            min-height: 88vh;
            display: flex;
            align-items: center;
            padding: 48px 0;
            opacity: 0;
            transition: opacity .7s ease;
        }

        .wf-panel.vis {
            opacity: 1;
        }

        .wf-card {
            background: #fff;
            border-radius: 28px;
            padding: 48px;
            border: 1px solid rgba(37, 99, 235, .08);
            box-shadow: 0 8px 40px rgba(12, 24, 53, .07);
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .wf-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            border-radius: 4px 4px 0 0;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .7s cubic-bezier(.4, 0, .2, 1) .15s;
        }

        .wf-panel.vis .wf-card::after {
            transform: scaleX(1)
        }

        .wf-card-top {
            display: flex;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 28px
        }

        .wf-card-icon {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: var(--blue-pale);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--blue);
            flex-shrink: 0
        }

        .wf-card-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 6px
        }

        .wf-card-heading h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -.5px;
            line-height: 1.15;
            margin-bottom: 12px
        }

        .wf-card-heading p {
            font-size: 15px;
            color: var(--gray-600);
            line-height: 1.8;
            max-width: 560px
        }

        .wf-divider {
            height: 1px;
            background: var(--gray-100);
            margin: 24px 0
        }

        .wf-hl-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gray-400);
            margin-bottom: 14px
        }

        .wf-pills {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px
        }

        .wf-pill {
            display: flex;
            align-items: center;
            gap: 9px;
            background: #f8faff;
            border: 1px solid rgba(37, 99, 235, .1);
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--navy);
            opacity: 0;
            transition: opacity .5s ease, background .2s, border-color .2s
        }

        .wf-pill:hover {
            background: var(--blue-pale);
            border-color: rgba(37, 99, 235, .25)
        }

        .wf-panel.vis .wf-pill {
            opacity: 1
        }

        .wf-panel.vis .wf-pill:nth-child(1) {
            transition-delay: .25s
        }

        .wf-panel.vis .wf-pill:nth-child(2) {
            transition-delay: .35s
        }

        .wf-panel.vis .wf-pill:nth-child(3) {
            transition-delay: .45s
        }

        .wf-panel.vis .wf-pill:nth-child(4) {
            transition-delay: .55s
        }

        .wf-panel.vis .wf-pill:nth-child(5) {
            transition-delay: .65s
        }

        .pill-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--blue);
            flex-shrink: 0
        }

        .wf-strip {
            margin-top: 28px;
            background: linear-gradient(135deg, #f0f4ff, #e8efff);
            border-radius: 20px;
            padding: 22px 26px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid rgba(37, 99, 235, .08);
            opacity: 0;
            transition: opacity .6s ease .5s
        }

        .wf-panel.vis .wf-strip {
            opacity: 1
        }

        .strip-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 6px 20px rgba(37, 99, 235, .35)
        }

        .strip-text h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 3px
        }

        .strip-text p {
            font-size: 12.5px;
            color: var(--gray-400)
        }

        /* MOBILE APP */
        .mobile-app-section {
            background: linear-gradient(160deg, #f0f7ff 0%, #e8f2ff 100%);
            padding: 100px 0
        }

        .mobile-app-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 48px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 80px;
            align-items: center
        }

        .phone-wrapper {
            display: flex;
            justify-content: center
        }

        .phone-frame {
            width: 280px;
            background: #0c1835;
            border-radius: 44px;
            padding: 14px;
            box-shadow: 0 40px 80px rgba(12, 24, 53, .3), 0 0 0 1px rgba(255, 255, 255, .1)
        }

        .phone-screen {
            background: #fff;
            border-radius: 32px;
            overflow: hidden;
            min-height: 540px;
            display: flex;
            flex-direction: column
        }

        .phone-notch {
            width: 90px;
            height: 28px;
            background: #0c1835;
            border-radius: 0 0 20px 20px;
            margin: 0 auto
        }

        .phone-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 20px 0;
            font-size: 11px;
            font-weight: 600;
            color: var(--navy)
        }

        .phone-screen-content {
            flex: 1;
            padding: 16px 20px 0;
            overflow: hidden
        }

        .phone-header-app {
            background: linear-gradient(135deg, #0c1835, #2563eb);
            border-radius: 18px;
            padding: 18px;
            color: #fff;
            margin-bottom: 14px
        }

        .phone-header-app .greeting {
            font-size: 12px;
            opacity: .7;
            margin-bottom: 2px
        }

        .phone-header-app .username {
            font-size: 16px;
            font-weight: 700
        }

        .phone-content-panel {
            display: none
        }

        .phone-content-panel.active {
            display: block;
            animation: fadeUp .3s ease
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .phone-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--gray-100);
            font-size: 12px
        }

        .phone-info-row:last-child {
            border-bottom: none
        }

        .phone-info-row .lbl {
            color: var(--gray-400)
        }

        .phone-info-row .val {
            font-weight: 600;
            color: var(--navy)
        }

        .pmcard {
            background: var(--blue-pale);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .pmcard .pmi {
            width: 32px;
            height: 32px;
            background: var(--blue);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #fff;
            flex-shrink: 0
        }

        .pmcard .pmt h5 {
            font-size: 12px;
            font-weight: 700;
            color: var(--navy)
        }

        .pmcard .pmt p {
            font-size: 11px;
            color: var(--gray-400)
        }

        .phone-bottom-nav {
            background: #fff;
            border-top: 1px solid var(--gray-100);
            padding: 10px 4px 8px;
            display: flex;
            justify-content: space-around
        }

        .pnb {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: 10px;
            transition: all .2s;
            color: var(--gray-400);
            font-size: 10px;
            font-family: var(--font)
        }

        .pnb i {
            font-size: 18px
        }

        .pnb.active {
            color: var(--blue)
        }

        .mfi-list {
            display: flex;
            flex-direction: column;
            gap: 14px
        }

        .mfi-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 18px 20px;
            background: #fff;
            border-radius: 16px;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all .2s
        }

        .mfi-item:hover {
            border-color: var(--blue-light);
            box-shadow: 0 4px 20px rgba(37, 99, 235, .1)
        }

        .mfi-item.active {
            border-color: var(--blue);
            background: var(--blue-pale)
        }

        .mfi-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: var(--blue-pale);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--blue);
            flex-shrink: 0;
            transition: all .2s
        }

        .mfi-item.active .mfi-icon {
            background: var(--blue);
            color: #fff
        }

        .mfi-text h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 3px
        }

        .mfi-text p {
            font-size: 12.5px;
            color: var(--gray-600);
            line-height: 1.5
        }

        /* DOWNLOAD */
        .dl-section {
            background: linear-gradient(160deg, #0c1835 0%, #1a3560 40%, #2563eb 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden
        }

        .dl-section::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(96, 165, 250, .08) 0%, transparent 60%)
        }

        .dl-inner {
            max-width: 760px;
            margin: 0 auto;
            padding: 0 48px;
            text-align: center;
            position: relative;
            z-index: 1
        }

        .dl-icon {
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
            margin: 0 auto 32px
        }

        .dl-inner h2 {
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 16px
        }

        .dl-inner p {
            font-size: 17px;
            color: rgba(255, 255, 255, .7);
            line-height: 1.7;
            margin-bottom: 44px
        }

        .btn-apk {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            padding: 18px 40px;
            background: #fff;
            color: var(--navy);
            font-size: 16px;
            font-weight: 700;
            border-radius: 18px;
            text-decoration: none;
            box-shadow: 0 12px 40px rgba(255, 255, 255, .18);
            transition: all .25s;
            border: none;
            cursor: pointer;
            font-family: var(--font)
        }

        .btn-apk:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 50px rgba(255, 255, 255, .25)
        }

        .btn-apk i {
            font-size: 26px;
            color: var(--blue)
        }

        .btn-apk-text {
            display: flex;
            flex-direction: column;
            text-align: left
        }

        .btn-apk-text small {
            font-size: 11px;
            color: var(--gray-400);
            font-weight: 500
        }

        .btn-apk-text strong {
            font-size: 18px;
            font-weight: 800;
            color: var(--navy)
        }

        .dl-phones {
            display: flex;
            justify-content: center;
            gap: 24px;
            align-items: flex-end;
            margin-top: 60px
        }

        .dp {
            background: #1a2744;
            border: 1.5px solid rgba(255, 255, 255, .1);
            border-radius: 30px;
            padding: 6px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .4)
        }

        .dp-screen {
            background: linear-gradient(160deg, #0c1835, #2563eb);
            border-radius: 22px;
            aspect-ratio: 9 / 16;
            width: 160px;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            /* agar gambar tidak keluar dari radius */
            padding: 0;
        }

        .dp.lg .dp-screen {
            width: 200px;
            aspect-ratio: 9 / 16;
        }

        .dp-app-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #fff
        }

        .dp-title {
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            text-align: center
        }

        .dp-title span {
            color: #60a5fa
        }

        .dp-tag {
            font-size: 12px;
            color: rgba(255, 255, 255, .6);
            text-align: center;
            line-height: 1.5
        }

        .dp-btn {
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 18px;
            cursor: pointer;
            font-family: var(--font)
        }

        /* FOOTER */
        footer {
            background: #060e1f;
            color: rgba(255, 255, 255, .5);
            text-align: center;
            padding: 32px 48px;
            font-size: 14px;
            border-top: 1px solid rgba(255, 255, 255, .06)
        }

        /* REVEAL */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s ease, transform .7s ease
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0)
        }

        /* RESPONSIVE */
        @media(max-width:900px) {
            nav {
                padding: 0 20px
            }

            .hero-inner,
            .about-section-inner,
            .mobile-app-inner {
                grid-template-columns: 1fr;
                gap: 40px
            }

            .hero-right {
                display: none
            }

            .section {
                padding: 60px 20px
            }

            .wf-sticky-shell {
                grid-template-columns: 1fr;
                padding: 0 20px 60px
            }

            .wf-sidebar {
                position: static
            }

            .wf-header {
                padding: 60px 20px 40px
            }

            .wf-panel {
                min-height: auto;
                padding: 20px 0
            }

            .wf-card {
                padding: 28px 22px
            }

            .wf-card-top {
                flex-direction: column;
                gap: 14px
            }

            .wf-card-heading h2 {
                font-size: 22px
            }

            .about-section-inner,
            .mobile-app-inner,
            .dl-inner {
                padding: 0 20px
            }

            .dp-screen img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 18px;
                background: #0c1835;
                /* warna background gelap seperti layar HP */
            }
        }
    </style>
</head>

<body>

    <!-- NAV -->
    <nav>
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">
                <img src="assets/img/logo_simpel.png" alt="Logo SIMPEL"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <i class="fa-solid fa-heart-pulse fallback-icon"></i>
            </div>
            <span class="nav-logo-text">SIM<span>PEL</span></span>
        </a>
        <div class="nav-links">
            <a href="#tentang">Tentang Kami</a>
            <a href="#fitur">Fitur Website</a>
            <a href="#mobile">Fitur Mobile</a>
            <a href="{{ route('login') }}" class="btn-nav-primary"><i class="fa-solid fa-right-to-bracket"></i>
                Masuk</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-inner">
            <div class="reveal">
                <div class="hero-badge"><i class="fa-solid fa-stethoscope"></i> Aplikasi Posyandu Lansia</div>
                <h1 class="hero-title">Memberdayakan Lansia <span>Menjadi Bagian Dari perkembangan Teknologi</span></h1>
                <p class="hero-desc">SIMPEL hadir untuk mempermudah proses kegiatan posyandu dalam pemantauan kesehatan,
                    penjadwalan posyandu
                    dan pengelolaan data lansia dan petugas, dan konten edukasi.</p>
                <div class="hero-buttons">
                    <a href="#download" class="btn-primary"><i class="fa-solid fa-download"></i> Unduh Aplikasi</a>
                    <a href="{{ route('login') }}" class="btn-outline"><i class="fa-solid fa-right-to-bracket"></i>
                        Masuk</a>
                </div>
            </div>
            <div class="hero-right reveal">
                <div class="hero-logo-circle">
                    <img src="assets/img/logo_simpel.png" alt="Logo SIMPEL">
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section class="about-section" id="tentang">
        <div class="about-section-inner">
            <div class="reveal">
                <div class="section-label"><i class="fa-solid fa-circle-info"></i> Tentang Kami</div>
                <h2 class="section-title">Simple dan Posyandu Pegagan</h2>
                <p class="section-desc">Posyandu Pegagan merupakan layanan kesehatan Masyarakat desa Poncogati Bondowoso
                    yang berfokus pada
                    pemantauan dan peningkatan kualitas hidup lansia.</p>
            </div>
            <div class="reveal">
                <div class="about-right-visual">
                    <div class="about-visual-card">
                        <h3><i class="fa-solid fa-laptop-medical" style="margin-right:10px;color:#60a5fa"></i>Tentang
                            SIMPEL</h3>
                        <p>SIMPEL (Sistem Informasi Peduli Lansia) dibangun untuk membantu Posyandu Pegagan dalam
                            proses pemberian saran, pemantauan, skrining, manajemen petugas, dan pengelolaan data
                            kesehatan secara terintegrasi antara petugas dan peserta.</p>
                    </div>
                    <div class="about-visual-card">
                        <h3><i class="fa-solid fa-link" style="margin-right:10px;color:#60a5fa"></i>Integrasi
                            Menyeluruh</h3>
                        <p>Menghubungkan petugas posyandu dan peserta lansia dalam satu platform — dari konten
                            edukasi, data kesehatan, hingga pengelolaan obat, semuanya tersedia dalam satu sistem
                            terpadu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR WEBSITE — STICKY SCROLL -->
    <div class="wf-wrap" id="fitur">
        <div class="wf-header reveal">
            <div class="section-label"><i class="fa-solid fa-desktop"></i> Fitur Website SIMPEL</div>
            <h2 class="section-title">Kelola Posyandu Secara Digital</h2>
            <p class="section-desc">Website SIMPEL digunakan oleh petugas untuk mengelola dan memantau seluruh
                kegiatan Posyandu Pegagan. Scroll untuk menjelajahi tiap fitur.</p>
        </div>

        <div class="wf-sticky-shell">

            <!-- Sticky Sidebar -->
            <aside class="wf-sidebar">
                <div class="wf-sidebar-lbl">Fitur Website</div>
                <div class="wf-nav-item is-on" data-wi="0">
                    <div class="wf-ni-icon"><i class="fa-solid fa-users"></i></div>
                    <div class="wf-ni-text">Data Lansia<small>Identitas & rekam medis</small></div>
                    <div class="wf-ni-bar"></div>
                </div>
                <div class="wf-nav-item" data-wi="1">
                    <div class="wf-ni-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                    <div class="wf-ni-text">Monitoring<small>Kondisi kesehatan</small></div>
                    <div class="wf-ni-bar"></div>
                </div>
                <div class="wf-nav-item" data-wi="2">
                    <div class="wf-ni-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="wf-ni-text">Histori Skrining<small>Rutin, PTM, PPOK</small></div>
                    <div class="wf-ni-bar"></div>
                </div>
                <div class="wf-nav-item" data-wi="3">
                    <div class="wf-ni-icon"><i class="fa-solid fa-pills"></i></div>
                    <div class="wf-ni-text">Manajemen Obat<small>Stok & distribusi</small></div>
                    <div class="wf-ni-bar"></div>
                </div>
                <div class="wf-nav-item" data-wi="4">
                    <div class="wf-ni-icon"><i class="fa-solid fa-newspaper"></i></div>
                    <div class="wf-ni-text">Konten Edukasi<small>Artikel, video, foto</small></div>
                    <div class="wf-ni-bar"></div>
                </div>

                <div class="wf-prog-wrap">
                    <div class="wf-prog-label"><span>Progress</span><span id="wfPct">1 / 6</span></div>
                    <div class="wf-prog-track">
                        <div class="wf-prog-fill" id="wfFill"></div>
                    </div>
                </div>
            </aside>

            <!-- Scroll Panels -->
            <div class="wf-panels">
                <!-- Panel 1 -->
                <div class="wf-panel" id="wfp-0">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon"><i class="fa-solid fa-users"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 01</div>
                                <h2>Manajemen Data Lansia</h2>
                                <p>Website memungkinkan petugas mengelola data identitas lansia secara terpusat dan
                                    terstruktur, memastikan setiap rekam medis tersimpan aman dan mudah diakses kapan
                                    saja.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Tambah data lansia
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Edit data lansia
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Data keluarga
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Filtering Untuk Status Kesehatan
                            </div>
                        </div>
                        <div class="wf-strip">
                            <div class="strip-icon"><i class="fa-solid fa-database"></i></div>
                            <div class="strip-text">
                                <h4>Data Lansia Terpusat </h4>
                                <p>Manajemen data lansia</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2 -->
                <div class="wf-panel" id="wfp-1">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon" style="background:#fdf4ff;color:#9333ea"><i
                                    class="fa-solid fa-heart-pulse"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 02</div>
                                <h2>Monitoring Kesehatan</h2>
                                <p>Memantau perkembangan kondisi kesehatan lansia berdasarkan hasil skrining dan
                                    pemeriksaan,
                                    dilengkapi grafik tren dan catatan petugas medis secara real-time.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#9333ea"></div>Grafik kesehatan
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#9333ea"></div>Keluhan & diagnosis
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#9333ea"></div>Saran petugas
                            </div>

                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#9333ea"></div>Status kesehatan
                            </div>
                        </div>
                        <div class="wf-strip" style="background:linear-gradient(135deg,#fdf4ff,#f5e8ff)">
                            <div class="strip-icon" style="background:linear-gradient(135deg,#7e22ce,#9333ea)"><i
                                    class="fa-solid fa-chart-line"></i></div>
                            <div class="strip-text">
                                <h4>Grafik </h4>
                                <p>Grafik interaktif perkembangan tensi, gula darah, dan indikator lainnya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 3 -->
                <div class="wf-panel" id="wfp-2">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon" style="background:#fff7ed;color:#ea580c"><i
                                    class="fa-solid fa-clock-rotate-left"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 03</div>
                                <h2>Histori Skrining</h2>
                                <p>Menampilkan seluruh riwayat skrining kesehatan lansia secara lengkap dan
                                    terorganisir,
                                    dari skrining rutin bulanan hingga pemeriksaan PTM dan PPOK khusus.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#ea580c"></div>Skrining rutin
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#ea580c"></div>Skrining PTM
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#ea580c"></div>Skrining PPOK
                            </div>

                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#ea580c"></div>Download History Skrining
                            </div>
                        </div>
                        <div class="wf-strip" style="background:linear-gradient(135deg,#fff7ed,#ffedd5)">
                            <div class="strip-icon" style="background:linear-gradient(135deg,#ea580c,#fb923c)"><i
                                    class="fa-solid fa-file-medical"></i></div>
                            <div class="strip-text">
                                <h4>Riwayat Semua Skrining</h4>
                                <p>Petugas kesehatan dapat mendownload History Skrining Lansia</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 4 -->
                <div class="wf-panel" id="wfp-3">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon" style="background:#f0fdf4;color:#16a34a"><i
                                    class="fa-solid fa-pills"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 04</div>
                                <h2>Manajemen Obat</h2>
                                <p>Mengelola stok dan distribusi obat untuk kegiatan Posyandu Lansia secara transparan,
                                    termasuk pencatatan restok, monitoring ketersediaan, dan riwayat penggunaan.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#16a34a"></div>Data obat
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#16a34a"></div>Stok obat
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#16a34a"></div>Riwayat restok
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#16a34a"></div>Monitoring ketersediaan
                            </div>

                        </div>
                        <div class="wf-strip" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                            <div class="strip-icon" style="background:linear-gradient(135deg,#15803d,#16a34a)"><i
                                    class="fa-solid fa-boxes-stacked"></i></div>
                            <div class="strip-text">
                                <h4>Stok Selalu Terpantau</h4>
                                <p>Notifikasi otomatis saat stok obat mendekati batas minimum</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 5 -->
                <div class="wf-panel" id="wfp-4">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon" style="background:#eff6ff;color:#2563eb"><i
                                    class="fa-solid fa-newspaper"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 05</div>
                                <h2>Konten Edukasi</h2>
                                <p>Membuat dan mengelola artikel, gambar, dan video edukasi kesehatan yang dapat diakses
                                    melalui aplikasi mobile oleh lansia dan keluarganya kapan saja dan di mana saja.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Artikel kesehatan
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot"></div>Video edukasi
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot"></div>foto informasi Kesehatan
                            </div>

                        </div>
                        <div class="wf-strip">
                            <div class="strip-icon"><i class="fa-solid fa-play"></i></div>
                            <div class="strip-text">
                                <h4>Konten Multi-Format</h4>
                                <p>Artikel, video, dan foto — dipublikasikan langsung ke aplikasi mobile lansia</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 6 -->
                <div class="wf-panel" id="wfp-5">
                    <div class="wf-card">
                        <div class="wf-card-top">
                            <div class="wf-card-icon" style="background:#fdf4ff;color:#7c3aed"><i
                                    class="fa-solid fa-chart-pie"></i></div>
                            <div class="wf-card-heading">
                                <div class="wf-card-eyebrow">Fitur 06</div>
                                <h2>Laporan </h2>
                                <p>Menyediakan laporan kegiatan dan rekap data kesehatan untuk kebutuhan administrasi
                                    serta evaluasi pelayanan, lengkap dengan statistik dan kemampuan export PDF.</p>
                            </div>
                        </div>
                        <div class="wf-divider"></div>
                        <div class="wf-hl-title">Highlight Fitur</div>
                        <div class="wf-pills">
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#7c3aed"></div>Kehadiran lansia
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#7c3aed"></div>Laporan kegiatan
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#7c3aed"></div>Export PDF laporan
                            </div>
                            <div class="wf-pill">
                                <div class="pill-dot" style="background:#7c3aed"></div>Laporan obat keluar
                            </div>
                        </div>
                        <div class="wf-strip" style="background:linear-gradient(135deg,#faf5ff,#f3e8ff)">
                            <div class="strip-icon" style="background:linear-gradient(135deg,#5b21b6,#7c3aed)"><i
                                    class="fa-solid fa-file-export"></i></div>
                            <div class="strip-text">
                                <h4>Export Instan</h4>
                                <p>Laporan lengkap siap unduh dalam format PDF dengan satu klik</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /wf-panels -->
        </div><!-- /wf-sticky-shell -->
    </div><!-- /wf-wrap -->

    <!-- MOBILE APP -->
    <section class="mobile-app-section" id="mobile">
        <div class="mobile-app-inner">
            <div class="reveal">
                <div class="section-label"><i class="fa-solid fa-mobile-screen-button"></i> Fitur Aplikasi Mobile
                </div>
                <h2 class="section-title">Semua dalam Genggaman</h2>
                <p class="section-desc" style="margin-bottom:28px">Aplikasi mobile SIMPEL membantu lansia dan keluarga
                    memperoleh informasi kesehatan secara lebih mudah dan praktis. Sentuh fitur di bawah untuk melihat
                    pratinjau.</p>
                <div class="mfi-list">
                    <div class="mfi-item active" data-f="home">
                        <div class="mfi-icon"><i class="fa-solid fa-house"></i></div>
                        <div class="mfi-text">
                            <h4>Beranda</h4>
                            <p>Info terkini jadwal dan kondisi kesehatan</p>
                        </div>
                    </div>
                    <div class="mfi-item" data-f="health">
                        <div class="mfi-icon"><i class="fa-solid fa-heart-pulse"></i></div>
                        <div class="mfi-text">
                            <h4>Riwayat Kesehatan</h4>
                            <p>Grafik tensi, gula darah, kolesterol, dan berat badan</p>
                        </div>
                    </div>
                    <div class="mfi-item" data-f="medicine">
                        <div class="mfi-icon"><i class="fa-solid fa-pills"></i></div>
                        <div class="mfi-text">
                            <h4>Resep & Obat</h4>
                            <p>Informasi obat dan pengingat minum obat</p>
                        </div>
                    </div>
                    <div class="mfi-item" data-f="edu">
                        <div class="mfi-icon"><i class="fa-solid fa-book-open-reader"></i></div>
                        <div class="mfi-text">
                            <h4>Edukasi</h4>
                            <p>Artikel dan video kesehatan dari posyandu</p>
                        </div>
                    </div>
                    <div class="mfi-item" data-f="emergency">
                        <div class="mfi-icon"><i class="fa-solid fa-phone-volume"></i></div>
                        <div class="mfi-text">
                            <h4>Darurat</h4>
                            <p>Hubungi bantuan dengan satu sentuhan</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="phone-wrapper reveal">
                <div class="phone-frame">
                    <div class="phone-screen">
                        <div class="phone-notch"></div>
                        <div class="phone-status-bar">
                            <span>9:41</span>
                            <span><i class="fa-solid fa-signal" style="font-size:10px"></i> <i
                                    class="fa-solid fa-battery-three-quarters" style="font-size:10px"></i></span>
                        </div>
                        <div class="phone-screen-content">
                            <div class="phone-header-app">
                                <div class="greeting">Selamat Pagi,</div>
                                <div class="username">Ibu Siti Rahayu 👋</div>
                            </div>
                            <div class="phone-content-panel active" id="ph-home">
                                <div class="pmcard">
                                    <div class="pmi"><i class="fa-solid fa-calendar-check"></i></div>
                                    <div class="pmt">
                                        <h5>Posyandu Berikutnya</h5>
                                        <p>Senin, 9 Juni 2025 – 08.00</p>
                                    </div>
                                </div>
                                <div class="pmcard">
                                    <div class="pmi" style="background:#10b981"><i
                                            class="fa-solid fa-heart-pulse"></i></div>
                                    <div class="pmt">
                                        <h5>Skrining Terakhir</h5>
                                        <p>Tensi: 120/80 · Normal</p>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-content-panel" id="ph-health">
                                <div class="phone-info-row"><span class="lbl">Tensi</span><span class="val"
                                        style="color:#2563eb">120/80 mmHg</span></div>
                                <div class="phone-info-row"><span class="lbl">Gula Darah</span><span
                                        class="val" style="color:#10b981">95 mg/dL</span></div>
                                <div class="phone-info-row"><span class="lbl">Kolesterol</span><span
                                        class="val" style="color:#f59e0b">180 mg/dL</span></div>
                                <div class="phone-info-row"><span class="lbl">Berat Badan</span><span
                                        class="val">58 kg</span></div>
                                <div class="phone-info-row"><span class="lbl">Status</span><span class="val"
                                        style="color:#10b981">✓ Normal</span></div>
                            </div>
                            <div class="phone-content-panel" id="ph-medicine">
                                <div class="pmcard">
                                    <div class="pmi" style="background:#8b5cf6"><i
                                            class="fa-solid fa-tablets"></i></div>
                                    <div class="pmt">
                                        <h5>Amlodipine 5mg</h5>
                                        <p>1x sehari · 07:00</p>
                                    </div>
                                </div>
                                <div class="pmcard">
                                    <div class="pmi" style="background:#ec4899"><i
                                            class="fa-solid fa-capsules"></i></div>
                                    <div class="pmt">
                                        <h5>Metformin 500mg</h5>
                                        <p>2x sehari · Sesudah makan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-content-panel" id="ph-edu">
                                <div class="pmcard">
                                    <div class="pmi" style="background:#0891b2"><i class="fa-solid fa-play"></i>
                                    </div>
                                    <div class="pmt">
                                        <h5>Video: Senam Lansia</h5>
                                        <p>5 menit · Hari ini</p>
                                    </div>
                                </div>
                                <div class="pmcard">
                                    <div class="pmi" style="background:#16a34a"><i
                                            class="fa-solid fa-file-medical"></i></div>
                                    <div class="pmt">
                                        <h5>Tips Diet Diabetes</h5>
                                        <p>Artikel · 3 menit baca</p>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-content-panel" id="ph-emergency">
                                <div style="text-align:center;padding:10px 0">
                                    <div
                                        style="width:70px;height:70px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#dc2626">
                                        <i class="fa-solid fa-phone-volume"></i>
                                    </div>
                                    <div style="font-size:13px;font-weight:700;color:var(--navy);margin-bottom:8px">
                                        Tombol Darurat</div>
                                    <div style="font-size:11px;color:var(--gray-400);margin-bottom:12px">Kirim lokasi &
                                        hubungi keluarga otomatis</div>
                                    <button
                                        style="background:#dc2626;color:#fff;border:none;border-radius:12px;padding:10px 22px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font)">
                                        <i class="fa-solid fa-triangle-exclamation"></i> PANGGIL BANTUAN
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="phone-bottom-nav">
                            <button class="pnb active" data-t="home"><i
                                    class="fa-solid fa-house"></i><span>Beranda</span></button>
                            <button class="pnb" data-t="health"><i
                                    class="fa-solid fa-heart-pulse"></i><span>Kesehatan</span></button>
                            <button class="pnb" data-t="medicine"><i
                                    class="fa-solid fa-pills"></i><span>Obat</span></button>
                            <button class="pnb" data-t="edu"><i
                                    class="fa-solid fa-book-open"></i><span>Edukasi</span></button>
                            <button class="pnb" data-t="emergency"><i
                                    class="fa-solid fa-triangle-exclamation"></i><span>Darurat</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DOWNLOAD -->
    <section class="dl-section" id="download">
        <div class="dl-inner reveal">
            <div class="dl-icon"><i class="fa-solid fa-mobile-screen"></i></div>
            <h2>Unduh Aplikasi SIMPEL</h2>
            <p>Aplikasi ini hanya ditunjukan untuk Lansia yang terdata dalam kegiatan Posyandu Pegagan </p>
            <a href="https://github.com/Jejecan/simpel_mobile/releases/download/SIMPLE_MOBILE/app-release.apk"
                class="btn-apk" download> <i class="fa-solid fa-download"
                    style="font-size:26px;color:var(--blue)"></i>
                <div class="btn-apk-text"><strong>Unduh APK</strong></div>
            </a>
            <div class="dl-phones">
                <!-- Screenshot 1 - Ukuran standar -->
                <div class="dp">
                    <div class="dp-screen">
                        <img src="assets/img/gambar1.jpeg" alt="Screenshot SIMPEL 1"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 18px;">
                    </div>
                </div>

                <!-- Screenshot 2 - Ukuran besar (tengah) -->
                <div class="dp lg">
                    <div class="dp-screen">
                        <img src="assets/img/gambar2.jpeg" alt="Screenshot SIMPEL 2"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 18px;">
                    </div>
                </div>

                <!-- Screenshot 3 - Ukuran standar -->
                <div class="dp">
                    <div class="dp-screen">
                        <img src="assets/img/gambar3.jpeg" alt="Screenshot SIMPEL 3"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 18px;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 <strong style="color:rgba(255,255,255,.8)">SIMPEL</strong> – Sistem Informasi Peduli Lansia
            · Posyandu Pegagan. All rights reserved.</p>
    </footer>

    <script>
        /* 1. REVEAL ON SCROLL */
        const revealObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('visible')
            })
        }, {
            threshold: 0.12
        })
        document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el))

        /* 2. SMOOTH SCROLL */
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const t = document.querySelector(a.getAttribute('href'))
                if (t) {
                    e.preventDefault();
                    t.scrollIntoView({
                        behavior: 'smooth'
                    })
                }
            })
        })

        /* 3. PHONE NAV */
        const pnBtns = document.querySelectorAll('.pnb')
        const phPanels = document.querySelectorAll('.phone-content-panel')

        function activatePhone(target) {
            pnBtns.forEach(b => b.classList.toggle('active', b.dataset.t === target))
            phPanels.forEach(p => p.classList.toggle('active', p.id === 'ph-' + target))
        }
        pnBtns.forEach(btn => btn.addEventListener('click', () => activatePhone(btn.dataset.t)))
        document.querySelectorAll('.mfi-item').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.mfi-item').forEach(i => i.classList.remove('active'))
                item.classList.add('active')
                const map = {
                    home: 'home',
                    health: 'health',
                    medicine: 'medicine',
                    edu: 'edu',
                    emergency: 'emergency'
                }
                const t = map[item.dataset.f]
                if (t) activatePhone(t)
            })
        })

        /* 4. WEB FEATURES — STICKY SCROLL */
        ;
        (function() {
            const panels = document.querySelectorAll('.wf-panel')
            const navItems = document.querySelectorAll('.wf-nav-item')
            const fillEl = document.getElementById('wfFill')
            const pctEl = document.getElementById('wfPct')
            const TOTAL = panels.length
            const dTimers = Array(TOTAL).fill(null)

            function setActive(idx) {
                idx = Math.max(0, Math.min(TOTAL - 1, idx))
                navItems.forEach((n, i) => n.classList.toggle('is-on', i === idx))
                if (fillEl) fillEl.style.width = ((idx + 1) / TOTAL * 100) + '%'
                if (pctEl) pctEl.textContent = (idx + 1) + ' / ' + TOTAL
            }

            const obs = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    const idx = Array.from(panels).indexOf(entry.target)
                    if (entry.isIntersecting) {
                        entry.target.classList.add('vis')
                        clearTimeout(dTimers[idx])
                        dTimers[idx] = setTimeout(() => setActive(idx), 350)
                    } else {
                        clearTimeout(dTimers[idx])
                        if (entry.boundingClientRect.top > window.innerHeight * 0.3) {
                            entry.target.classList.remove('vis')
                        }
                    }
                })
            }, {
                threshold: [0.5],
                rootMargin: '0px 0px -10% 0px'
            })

            panels.forEach(p => obs.observe(p))
            setActive(0)
        })()
    </script>

</body>

</html>
