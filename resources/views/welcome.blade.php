<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدمات الصيانة والإصلاح</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
    --primary-color: #FFBF6B;
    --secondary-color: #333;
    --background-color: #f9f9f9;
    --text-color: #333;
    --white: #ffffff;
    --footer-bg: #1a1a1a;
    --footer-text: #ffffff;
    --footer-link: #cccccc;
    --footer-hover: var(--primary-color);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: var(--background-color);
    color: var(--text-color);
    line-height: 1.6;
}

/* Header & Navigation */
header {
    background-color: var(--white);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

nav {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--primary-color);
}

.nav-links a {
    color: var(--text-color);
    text-decoration: none;
    margin-left: 2rem;
    transition: color 0.3s ease;
}

.nav-links a:hover {
    color: var(--primary-color);
}

/* Hero Section */
#hero {
    padding: 8rem 2rem 4rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    min-height: 80vh;
}

.hero-content {
    flex: 1;
    padding-right: 2rem;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: var(--secondary-color);
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    color: #666;
}

.app-buttons {
    display: flex;
    gap: 1rem;
}

.app-buttons a {
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.3s ease;
}

.app-buttons a:hover {
    transform: translateY(-2px);
}

.app-store {
    background-color: #000;
    color: var(--white);
}

.play-store {
    background-color: var(--primary-color);
    color: var(--white);
}

.hero-image {
    flex: 1;
    display: flex;
    justify-content: center;
}

.phone-mockup {
    width: 300px;
    height: 600px;
    background: var(--white);
    border-radius: 30px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.screenshot {
    width: 100%;
    height: 100%;
    background-color: #f0f0f0;
    /* Placeholder for app screenshot */
}

/* Features Section */
#ozellikler {
    padding: 4rem 2rem;
    background-color: var(--white);
}

#ozellikler h2 {
    text-align: center;
    margin-bottom: 3rem;
    color: var(--secondary-color);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    padding: 2rem;
    text-align: center;
    background: var(--white);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card i {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 1rem;
    color: var(--secondary-color);
}

/* How It Works Section */
#nasil-calisir {
    padding: 4rem 2rem;
    background-color: #f5f5f5;
}

#nasil-calisir h2 {
    text-align: center;
    margin-bottom: 3rem;
}

.steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.step {
    text-align: center;
    padding: 2rem;
    background: var(--white);
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.step-number {
    width: 40px;
    height: 40px;
    background-color: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-weight: bold;
}

/* Contact Section */
#iletisim {
    padding: 4rem 2rem;
    background-color: var(--white);
}

#iletisim h2 {
    text-align: center;
    margin-bottom: 3rem;
}

.contact-info {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background-color: #f5f5f5;
    border-radius: 10px;
}

.contact-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
}

/* Modern Footer Styles */
footer {
    background-color: var(--footer-bg);
    color: var(--footer-text);
    padding: 4rem 2rem 1rem;
    position: relative;
}

.footer-grid {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    padding-bottom: 3rem;
}

.footer-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.footer-logo img {
    width: 40px;
    height: 40px;
}

.footer-logo h3 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: bold;
}

.footer-description {
    color: var(--footer-link);
    line-height: 1.6;
    font-size: 0.95rem;
}

.footer-section h4 {
    color: var(--white);
    font-size: 1.2rem;
    margin-bottom: 1rem;
    position: relative;
}

.footer-section h4::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -0.5rem;
    width: 30px;
    height: 2px;
    background-color: var(--primary-color);
}

.footer-links, .footer-contact {
    list-style: none;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.footer-links a {
    color: var(--footer-link);
    text-decoration: none;
    transition: color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.footer-links a:hover {
    color: var(--footer-hover);
    transform: translateX(5px);
}

.footer-contact li {
    color: var(--footer-link);
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.footer-contact i {
    color: var(--primary-color);
    font-size: 1.1rem;
}

.footer-app-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-app-button {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.8rem 1.2rem;
    background-color: #333;
    border-radius: 8px;
    text-decoration: none;
    color: var(--white);
    transition: all 0.3s ease;
}

.footer-app-button:hover {
    background-color: #444;
    transform: translateY(-2px);
}

.footer-app-button i {
    font-size: 1.8rem;
}

.button-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.button-text span {
    font-size: 0.7rem;
    color: var(--footer-link);
}

.button-text strong {
    font-size: 1rem;
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 2rem;
    margin-top: 2rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
}

.social-link {
    color: var(--footer-link);
    font-size: 1.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
}

.social-link:hover {
    color: var(--white);
    background-color: var(--primary-color);
    transform: translateY(-3px);
}

.copyright {
    color: var(--footer-link);
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    #hero {
        flex-direction: column;
        text-align: center;
        padding-top: 6rem;
    }

    .hero-content {
        padding-right: 0;
        margin-bottom: 2rem;
    }

    .app-buttons {
        justify-content: center;
    }

    .nav-links {
        display: none;
    }

    .footer-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .footer-app-buttons {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .footer-section {
        text-align: center;
    }

    .footer-section h4::after {
        left: 50%;
        transform: translateX(-50%);
    }

    .footer-links a:hover {
        transform: none;
    }

    .footer-contact li {
        justify-content: center;
    }
}
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">اضغط ليأتي الفني</div>
            <div class="nav-links">
                <a href="#anasayfa">الصفحة الرئيسية</a>
                <a href="#ozellikler">الخصائص</a>
                <a href="#nasil-calisir">طريقة العمل</a>
                <a href="#iletisim">تواصل معنا</a>
            </div>
        </nav>
    </header>

    <section id="hero">
        <div class="hero-content">
            <h1>فنيون محترفون في متناول يدك</h1>
            <p>استمتع بخدمة صيانة فورية مع فنيين محترفين في الكهرباء، السباكة، الأثاث وأكثر.</p>
            <div class="app-buttons">
                <a href="#" class="app-store"><i class="fab fa-apple"></i> App Store</a>
                <a href="#" class="play-store"><i class="fab fa-google-play"></i> Google Play</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="phone-mockup">
                <!-- Placeholder for app screenshot -->
                <div class="screenshot"></div>
            </div>
        </div>
    </section>

    <section id="ozellikler">
        <h2>Özellikler</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-search"></i>
                <h3>البحث السريع</h3>
                <p>تواصل مع فنيين متخصصين بسرعة وسهولة حسب حاجتك</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-comments"></i>
                <h3>التراسل الفوري</h3>
                <p>تواصل مباشرة مع الفنيين</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-file-invoice"></i>
                <h3>الفاتورة الإلكترونية</h3>
                <p>الدفع الآمن مع الفواتير الإلكترونية الموقعة</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-bell"></i>
                <h3>التنبيهات</h3>
                <p>تابع مواعيدك وعروضك لحظة بلحظة</p>
            </div>
        </div>
    </section>

    <section id="nasil-calisir">
        <h2>طريقة العمل?</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>سجل حساب جديد</h3>
                <p>قم بالتسجيل باستخدام بريدك الإلكتروني </p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>ختيار الخدمة</h3>
                <p>حدد نوع الخدمة التي تحتاجها</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>اعثر على فني</h3>
                <p>تلقى عروض من فنيين محترفين</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>احجز معاد</h3>
                <p>اختار المعاد اللي يناسبك</p>
            </div>
        </div>
    </section>

    <section id="iletisim">
        <h2>تواصل معانا</h2>
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <p>amr666145@gmail.com</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <p>01154189739</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="{{ URL::asset($company_data->logo) }}" alt="TiklaTamirciGelsin Logo">
                    <h3>اضغط لطلب الفني في أي وقت</h3>
                </div>
                <p class="footer-description">
                    فنيين جاهزين يخدموك في أي وقت، 24 ساعة في اليوم – 7 أيام في الأسبوع.
                </p>
            </div>

            <div class="footer-section">
                <h4>روابط سريعة</h4>
                <ul class="footer-links">
                    <li><a href="#anasayfa">الصفحة الرئيسية</a></li>
                    <li><a href="#ozellikler">الخصائص</a></li>
                    <li><a href="#nasil-calisir">طريقة العمل</a></li>
                    <li><a href="#iletisim">تواصل معانا</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>التواصل</h4>
                <ul class="footer-contact">
                    <li><i class="fas fa-envelope"></i> للتواصل معنا، ابعتلنا على الإيميل: amr666645@gmail.com</li>
                    <li><i class="fas fa-phone"></i> 01154189739</li>
                    <li><i class="fas fa-map-marker-alt"></i> مصر , القاهرة</li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>حمل التطبيق</h4>
                <div class="footer-app-buttons">
                    <a href="#" class="footer-app-button">
                        <i class="fab fa-apple"></i>
                        <div class="button-text">
                            <span>Download on the</span>
                            <strong>App Store</strong>
                        </div>
                    </a>
                    <a href="#" class="footer-app-button">
                        <i class="fab fa-google-play"></i>
                        <div class="button-text">
                            <span>GET IT ON</span>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
            </div>
            <p class="copyright">&copy;جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>

</html>
