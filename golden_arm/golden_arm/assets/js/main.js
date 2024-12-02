(function() {
  "use strict";

  let location = window.location

  const page = location.pathname.split('/').reverse()[0];

  $('#navmenu a').removeClass('active');
  $(`#navmenu .item-${page}`).addClass('active');

  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function (e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }

  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }

  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    let heightOfAboutSection = $('#about').height()
    document.querySelectorAll(".init-swiper").forEach(function (swiperElement) {
      let config =
        {
          "loop": true,
          "speed": 600,
          "autoplay": {
            "delay": 3000
          },
          "slidesPerView": "auto",
          "pagination": {
            "el": ".swiper-pagination",
            "type": "bullets"
          },
          "height": heightOfAboutSection
        }

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll('.isotope-layout').forEach(function (isotopeItem) {
    let layout = isotopeItem.getAttribute('data-layout') ?? 'masonry';
    let filter = isotopeItem.getAttribute('data-default-filter') ?? '*';
    let sort = isotopeItem.getAttribute('data-sort') ?? 'original-order';

    let initIsotope;
    imagesLoaded(isotopeItem.querySelector('.isotope-container'), function () {
      initIsotope = new Isotope(isotopeItem.querySelector('.isotope-container'), {
        itemSelector: '.isotope-item',
        layoutMode: layout,
        filter: filter,
        sortBy: sort
      });
    });

    isotopeItem.querySelectorAll('.isotope-filters li').forEach(function (filters) {
      filters.addEventListener('click', function () {
        isotopeItem.querySelector('.isotope-filters .filter-active').classList.remove('filter-active');
        this.classList.add('filter-active');
        initIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        if (typeof aosInit === 'function') {
          aosInit();
        }
      }, false);
    });

  });

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      $('.faq-item').removeClass('faq-active');
      faqItem.parentNode.classList.add('faq-active');
    });
  });

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function (e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }

  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);
  
  if(page === 'pickup') {
    //$('.cost .card-text').hide();
    $('.cost button').show();
    $('#cart-icon').show();
  } else {
    //$('.cost .card-text').show();
    $('.cost button').hide();
    $('#cart-icon').hide();
  }


  let cart = localStorage.getItem('cart') || '{ "items": [], "totalPrice": 0 }';
  let cartParsed = JSON.parse(cart).items;

  if(cartParsed.length) {
    $('#cart-counter').show().text(cartParsed.length)
  } else {
    $('#cart-counter').hide().text(0)
  }

  $('#cart-icon').off('click').on('click', () => {
    $('#checkout').hide();
    $('.cart-container').show();
    $('#checkout-button').show();
    $('#order-proceed-button').hide();

    let cart = localStorage.getItem('cart') || '{ "items": [], "totalPrice": 0 }';
    let cartParsed = JSON.parse(cart).items;

    $('.cart-container tbody').empty()

    cartParsed.forEach(item => {
      $('.cart-container tbody').append(`
          <tr>
            <td>
              <img src="images/${item.dish_image}" alt="${item.dish_name}" class="product-image">
              ${item.dish_name}
            </td>
            <td>€${item.dish_price}</td>
            <td>
              <div class="quantity-selector">
                <button id="decrement-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity - 1}}, 'update')">-</button>
                <input type="text" value="${item.quantity}">
                <button id="increment-${item.dish_id}" onclick="cartHandler({dish_id: '${item.dish_id}', quantity: ${item.quantity + 1}}, 'update')">+</button>
              </div>
            </td>
            <td>€${item.total}</td>
            <td><button id="remove-${item.dish_id}" class="remove-item" onclick="cartHandler({dish_id: '${item.dish_id}'}, 'delete')">X</button></td>
          </tr>
        `);
    })

    let totalPrice = cartParsed.reduce((sum, item) => {
      return sum + (parseFloat(item.dish_price) * item.quantity);
    }, 0);

    if(totalPrice) {
      $('#checkout-button').show();
    } else {
      $('#checkout-button').hide();
    }

    $('#totalPrice span').text(totalPrice);
    $('#addToCart').modal('show');
  })
})();