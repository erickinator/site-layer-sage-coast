// =============================================================================
// SAGE COAST REALTY - MASTER JAVASCRIPT FILE
// Consolidated custom scripts for all site functionality
// =============================================================================

document.addEventListener('DOMContentLoaded', function() {

    // =========================================================================
    // HELPER: Check if element is inside Buying Buddy UI
    // =========================================================================
    
    function isInsideBuyingBuddy(node){
      return !!(node && node.closest && node.closest('my-buying-buddy, .view-property-wrapper, .bb, .bfg, [data-bb], [class*="bfg-"], [id^="MBB"]'));
    }

    // =========================================================================
    // SECTION 1: VALUE CARDS ANIMATION
    // Purpose: Animate value item cards on scroll (About/Home page)
    // Selector: .tmsc-module .value-item
    // =========================================================================
    
    function animateValues() {
        document.querySelectorAll('.tmsc-module .value-item').forEach(function(item) {
            const rect = item.getBoundingClientRect();
            if (rect.top < window.innerHeight - 60) {
                item.classList.add('visible');
            }
        });
    }
    
    animateValues();
    window.addEventListener('scroll', animateValues);

    // =========================================================================
    // SECTION 2: SMOOTH SCROLL ANCHOR LINKS
    // Purpose: Smooth scroll to anchors within .tmsc-module sections only
    // Scope: Only affects links inside .tmsc-module containers
    // =========================================================================
    
    document.querySelectorAll('.tmsc-module a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(event) {
            if (isInsideBuyingBuddy(this)) return;
            
            const module = anchor.closest('.tmsc-module');
            if (!module) return;
            
            const href = this.getAttribute('href');
            if (!href || href === '#') return;

            const target = module.querySelector(href);
            if (!target) return;

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // =========================================================================
    // SECTION 3: BUYING BUDDY GALLERY IMAGE FIX
    // Purpose: Fix malformed background-image URLs in BB property thumbnails
    // Issue: API returns: background-image:url('background-image:url('https://...')');
    // Solution: Extract correct URL from data-img attribute and apply via style
    // =========================================================================
    
    function fixBBGalleryImages() {
        document.querySelectorAll('.bfg-gallery-thumb[data-img]').forEach(function(thumb) {
            const dataImg = thumb.getAttribute('data-img');
            if (dataImg) {
                const urlMatch = dataImg.match(/url\('([^']+)'\)/);
                if (urlMatch && urlMatch[1]) {
                    thumb.style.backgroundImage = `url('${urlMatch[1]}')`;
                }
            }
        });
    }

    fixBBGalleryImages();

    // Watch for dynamically added Buying Buddy content
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    fixBBGalleryImages();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // =========================================================================
    // SECTION 4: ZILLOW REVIEWS CAROUSEL
    // Purpose: Display Zillow reviews in an auto-scrolling carousel
    // Elements: #sageZScroller (carousel container)
    //           #sageZPrev (previous button)
    //           #sageZNext (next button)
    // Features: Auto-advance every 9 seconds, pause on hover, manual controls
    // =========================================================================

    const zillowProfileUrl = "https://www.zillow.com/profile/Jillsellsrealestate";
    const reviews = [
      {
        stars: 5,
        type: "Sold • Single Family",
        location: "Carlsbad",
        text: "I have had the pleasure of working with Jill on three separate real estate transactions. Her experience, professionalism, and problem-solving brought stellar results each time."
      },
      {
        stars: 5,
        type: "Bought • Multi-unit",
        location: "Vista",
        text: "We had a complicated purchase in a 1031 exchange. Working with Jill and Erick every step ensured we had no surprises and always received the best advice."
      },
      {
        stars: 5,
        type: "Bought • Single Family",
        location: "Ramona",
        text: "Buying in an aggressive market is stressful unless you have Jill and her team representing you. She was amazing to work with, and it felt like having someone truly on our side."
      },
      {
        stars: 5,
        type: "Sold • Multiple Occupancy",
        location: "San Diego",
        text: "Jill was great to work with. She sold my place quickly and negotiated a higher price. She was responsive, knowledgeable, and walked me through the process clearly."
      },
      {
        stars: 5,
        type: "Sold • Single Family",
        location: "Escondido",
        text: "We sold during an unpredictable stretch of the market with false starts and frenzied buyers. Jill was steady through all of it and kept the process on track."
      },
      {
        stars: 5,
        type: "Sold • Single Family",
        location: "Escondido",
        text: "Jill provided thorough, knowledgeable analysis of the neighborhood to arrive at the right price. We had a deal within a week of being on market."
      }
    ];

    // Target carousel elements
    const scroller = document.getElementById("sageZScroller");
    const prevBtn = document.getElementById("sageZPrev");
    const nextBtn = document.getElementById("sageZNext");

    // Safety check: only run if carousel exists on this page
    if (scroller && prevBtn && nextBtn) {
        
        // Helper: Render star rating as string
        const starRow = (n) => "★".repeat(n) + "☆".repeat(5 - n);

        // Render all review cards
        scroller.innerHTML = reviews.map(r => `
          <article class="sage-zillow__card">
            <blockquote class="sage-zillow__quote">"${r.text}"</blockquote>
            <div class="sage-zillow__meta">
              <span class="sage-zillow__pill" style="color:#cbaa67;">${starRow(r.stars)}</span>
              <span class="sage-zillow__pill">${r.type}</span>
              <span class="sage-zillow__pill">${r.location}</span>
            </div>
          </article>
        `).join("");

        // Scroll helper function: advance carousel by one card + gap
        const scrollByCards = (dir) => {
            const card = scroller.querySelector(".sage-zillow__card");
            if (!card) return;
            
            // CSS gap is 20px - must match this value
            const gap = 20;
            const step = card.getBoundingClientRect().width + gap;
            
            scroller.scrollBy({ left: dir * step, behavior: "smooth" });
        };

        // Manual navigation: Previous button
        prevBtn.addEventListener("click", () => scrollByCards(-1));

        // Manual navigation: Next button
        nextBtn.addEventListener("click", () => scrollByCards(1));

        // Auto-advance timer
        let autoScroll = setInterval(() => {
            const nearEnd = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 10;
            if (nearEnd) {
                // Loop back to start
                scroller.scrollTo({ left: 0, behavior: "smooth" });
            } else {
                scrollByCards(1);
            }
        }, 9000); // Advance every 9 seconds

        // Pause auto-scroll on hover
        scroller.addEventListener("mouseenter", () => {
            clearInterval(autoScroll);
        });

        // Resume auto-scroll on mouse leave
        scroller.addEventListener("mouseleave", () => {
            autoScroll = setInterval(() => {
                const nearEnd = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - 10;
                if (nearEnd) {
                    scroller.scrollTo({ left: 0, behavior: "smooth" });
                } else {
                    scrollByCards(1);
                }
            }, 9000);
        });

    }

    // =========================================================================
    // SECTION 5: BUYERS GUIDE SMOOTH SCROLL & ANIMATIONS
    // Purpose: Add smooth scroll and scroll-triggered animations to Buyers Guide
    // Selector: .sage-buyers-guide
    // Features: Smooth anchor links, staggered fade-in animations on scroll
    // =========================================================================

    // Only run on Buyers Guide pages
    if (document.querySelector('.sage-buyers-guide')) {

        // Smooth scroll for internal anchor links (Buyers Guide only)
        document.querySelectorAll('.sage-buyers-guide a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                if (isInsideBuyingBuddy(this)) return;
                
                const href = this.getAttribute('href');
                if (!href || href === '#') return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // Scroll-triggered fade-in animations with stagger effect
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Helper: Observe elements with staggered delay
        function observeWithStagger(selector, delayStep) {
            const elements = document.querySelectorAll(selector);
            elements.forEach(function(el, index) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease ' + (index * delayStep) + 's';
                observer.observe(el);
            });
        }

        // Apply staggered animations to different element types
        observeWithStagger('.sage-buyers-guide .journey-step', 0.15);
        observeWithStagger('.sage-buyers-guide .buyer-type-card', 0.1);
        observeWithStagger('.sage-buyers-guide .resource-card', 0.1);

    }

    // =========================================================================
    // SECTION 6: WHY LIST WITH US PAGE ANIMATIONS
    // Purpose: Scroll-triggered animations for Why List With Us page elements
    // Selector: .sage-why-list
    // Features: Fade-in animations for timeline items, benefit cards, and stat boxes
    // =========================================================================

    if (document.querySelector('.sage-why-list')) {
        
        const observerOptionsWhy = { 
            threshold: 0.1, 
            rootMargin: '0px 0px -100px 0px' 
        };

        const observerWhy = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptionsWhy);

        // Animate timeline items in process section
        const timelineItems = document.querySelectorAll('.process-section.sage-why-list .timeline-item');
        timelineItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            item.style.transition = 'all 0.6s ease';
            observerWhy.observe(item);
        });

        // Animate benefit cards with stagger effect
        const benefitCards = document.querySelectorAll('.benefits-section.sage-why-list .benefit-card');
        benefitCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observerWhy.observe(card);
        });

        // Animate stat boxes with stagger effect
        const statBoxes = document.querySelectorAll('.stats-section.sage-why-list .stat-box');
        statBoxes.forEach((box, index) => {
            box.style.opacity = '0';
            box.style.transform = 'translateY(30px)';
            box.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observerWhy.observe(box);
        });

    }

    // =========================================================================
    // SECTION 7: HOME ABOUT PAGE VALUE ITEMS ANIMATION
    // Purpose: Staggered fade-in animations for home about section value items
    // Selector: .sage-home-about .value-item
    // Features: IntersectionObserver with fallback, stagger delay, prevents re-reveals
    // =========================================================================

    const aboutRoot = document.querySelector('.sage-home-about');
    if (aboutRoot) {
        
        const valueItems = Array.from(aboutRoot.querySelectorAll('.value-item'));
        
        if (valueItems.length) {
            
            const revealItem = (item, delayMs) => {
                if (item.dataset.revealed === '1') return;
                item.dataset.revealed = '1';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, delayMs);
            };

            // Modern browsers: Use IntersectionObserver
            if ('IntersectionObserver' in window) {
                const observerAbout = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        const item = entry.target;
                        const index = valueItems.indexOf(item);
                        revealItem(item, Math.max(0, index) * 100);
                        observerAbout.unobserve(item);
                    });
                }, { threshold: 0.15 });

                valueItems.forEach((item) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(30px)';
                    item.style.transition = 'all 0.6s ease';
                    observerAbout.observe(item);
                });
            } else {
                // Fallback for older browsers: scroll-based reveal
                const onScroll = () => {
                    const screenPosition = window.innerHeight / 1.2;

                    valueItems.forEach((item, index) => {
                        if (item.dataset.revealed === '1') return;
                        const itemPosition = item.getBoundingClientRect().top;
                        if (itemPosition < screenPosition) {
                            revealItem(item, index * 100);
                        }
                    });

                    if (valueItems.every((i) => i.dataset.revealed === '1')) {
                        window.removeEventListener('scroll', onScroll);
                    }
                };

                valueItems.forEach((item) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(30px)';
                    item.style.transition = 'all 0.6s ease';
                });

                onScroll();
                window.addEventListener('scroll', onScroll, { passive: true });
            }
        }
    }

    // =========================================================================
    // Buying Buddy: prevent default "#" hash changes inside BB UI
    // =========================================================================
    
    document.addEventListener('click', function(e){
      const a = e.target.closest('a');
      if(!a) return;
      if(a.getAttribute('href') !== '#') return;
      if(isInsideBuyingBuddy(a)) e.preventDefault();
    }, true);

    // =========================================================================
    // SECTION 8: DARK/LIGHT MODE TOGGLE & GLOBAL SMOOTH SCROLL
    // Purpose: Toggle dark mode and smooth scroll to anchor links globally
    // Elements: #modeToggle (toggle button)
    //           #lightIcon, #darkIcon (icon displays)
    // Features: Dark mode toggle with icon swap, smooth scroll with header offset
    // =========================================================================

    // Dark/Light Mode Toggle
    const modeToggle = document.getElementById('modeToggle');
    if (modeToggle) {
        const lightIcon = document.getElementById('lightIcon');
        const darkIcon = document.getElementById('darkIcon');
        
        modeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            if (document.body.classList.contains('dark-mode')) {
                if (lightIcon) lightIcon.style.display = 'none';
                if (darkIcon) darkIcon.style.display = 'block';
            } else {
                if (lightIcon) lightIcon.style.display = 'block';
                if (darkIcon) darkIcon.style.display = 'none';
            }
        });
    }
    
    // Global Smooth Scrolling for Anchor Links (excludes section-specific handlers)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (isInsideBuyingBuddy(this)) return;
            
            // Skip if already handled by section-specific handlers
            const module = this.closest('.tmsc-module');
            const buyersGuide = this.closest('.sage-buyers-guide');
            if (module || buyersGuide) return;
            
            const targetId = this.getAttribute('href');
            if (!targetId || targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;

            e.preventDefault();
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        });
    });

    // =========================================================================
    // ADD NEW SECTIONS BELOW THIS LINE
    // =========================================================================
    // Template for new features:
    // 
    // // SECTION N: FEATURE NAME
    // // Purpose: What this does
    // // Selector: .your-selector
    // 
    // function yourFunction() {
    //     // code here
    // }
    // 
    // yourFunction();
    // window.addEventListener('scroll', yourFunction); // if needed
    // =========================================================================

});

// =============================================================================
// JQUERY SECTION: BACK TO ROSTER LINK FIX
// Purpose: Hijack Buying Buddy "Back to Roster" link and redirect to team page
// Selector: a[href*="/team/office/id/"]
// =============================================================================

jQuery(document).ready(function($) {
    
    /* START: Back Button Fix */
    var backLink = $('a[href*="/team/office/id/"]');
    
    if (backLink.length > 0) {
        // Change the href to point to team directory
        backLink.attr('href', '/meet-our-team');
        
        // Update text while preserving the <i> icon
        var iconElement = backLink.find('i');
        
        if (iconElement.length > 0) {
            // Icon exists - preserve it and update the text
            // Get the icon's HTML to preserve all attributes
            var iconHtml = iconElement[0].outerHTML;
            
            // Set the link content to icon + new text
            backLink.html(iconHtml + ' Back to Team');
        } else {
            // No icon exists - just replace all text
            backLink.text(' Back to Team');
        }
    }
    /* END: Back Button Fix */
    
});