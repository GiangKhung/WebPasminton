// VNB Sports - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Banner Slider
    const slides = document.querySelectorAll('.banner-slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slider-btn.prev');
    const nextBtn = document.querySelector('.slider-btn.next');
    let currentSlide = 0;
    let autoSlide;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        currentSlide = index;
        if (currentSlide >= slides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = slides.length - 1;
        
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    // Auto slide every 3 seconds
    function startAutoSlide() {
        autoSlide = setInterval(nextSlide, 3000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlide);
    }

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoSlide();
            startAutoSlide();
        });

        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoSlide();
            startAutoSlide();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                stopAutoSlide();
                startAutoSlide();
            });
        });

        startAutoSlide();
    }

    // Product Tab Switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Back to Top
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            backToTop.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Search Box
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        const searchInput = searchBox.querySelector('input');
        const searchBtn = searchBox.querySelector('button');
        
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = 'search.php?q=' + encodeURIComponent(query);
            }
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') searchBtn.click();
        });
    }

    // Cart functionality
    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let total = cart.reduce((sum, item) => sum + parseInt(item.qty || 0), 0);
        
        // Cập nhật tất cả các vị trí hiển thị số lượng giỏ hàng
        document.querySelectorAll('.cart-count, .fixed-cart-count').forEach(el => {
            el.textContent = total;
        });
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px;
            background: #4CAF50; color: white;
            padding: 15px 25px; border-radius: 5px;
            z-index: 9999;
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    updateCartCount();

    // Dropdown for touch devices
    document.querySelectorAll('.has-dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.classList.toggle('active');
            }
        });
    });
});


// ========== ABOUT PAGE ANIMATIONS ==========

// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current) + '+';
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target + '+';
            }
        };
        
        updateCounter();
    });
}

// Intersection Observer for animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Trigger counter animation when stats section is visible
                if (entry.target.classList.contains('about-stats')) {
                    animateCounters();
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with data-aos attribute
    document.querySelectorAll('[data-aos]').forEach(el => {
        el.classList.add('aos-init');
        observer.observe(el);
    });

    // Observe stats section
    const statsSection = document.querySelector('.about-stats');
    if (statsSection) {
        observer.observe(statsSection);
    }
}

// Add CSS for animations
const animationStyles = document.createElement('style');
animationStyles.textContent = `
    .aos-init {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .aos-init.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    [data-aos="fade-right"].aos-init {
        transform: translateX(-30px);
    }
    
    [data-aos="fade-right"].animate-in {
        transform: translateX(0);
    }
    
    [data-aos="fade-left"].aos-init {
        transform: translateX(30px);
    }
    
    [data-aos="fade-left"].animate-in {
        transform: translateX(0);
    }
    
    [data-aos="zoom-in"].aos-init {
        transform: scale(0.8);
    }
    
    [data-aos="zoom-in"].animate-in {
        transform: scale(1);
    }
    
    [data-aos-delay="100"] { transition-delay: 0.1s; }
    [data-aos-delay="200"] { transition-delay: 0.2s; }
    [data-aos-delay="300"] { transition-delay: 0.3s; }
    [data-aos-delay="400"] { transition-delay: 0.4s; }
    [data-aos-delay="500"] { transition-delay: 0.5s; }
`;
document.head.appendChild(animationStyles);

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
} else {
    initScrollAnimations();
}
