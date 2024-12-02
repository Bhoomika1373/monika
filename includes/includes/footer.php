  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-5 footer-about">
          <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/main-logo.png" alt="">
          </a>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href="https://www.facebook.com/profile.php?id=61565495452915" target="_blank"><i class="bi bi-facebook"></i></a>
          </div>
        </div>

        <div class="col-lg-7 col-md-7 footer-links">
          <div class="row">
            <div class="col-lg-7 col-md-7 footer-links">
              <div class="row">
                <div class="col-lg-6 col-md-6 footer-links">
                  <h4>Restaurant</h4>
                  <ul>
                    <li><a href="/menu">Menu</a></li>
                    <li><a href="/pickup">Pickup</a></li>
                    <li><a href="/reservation">Reservation</a></li>
                  </ul>
                </div>

                <div class="col-lg-6 col-md-6 footer-links">
                  <h4>Quick Links</h4>
                  <ul>
                    <li><a href="/privacy_policy" id="privacyPolicyQuickLink" translate="no">Loading...</a></li>
                    <li><a href="/imprint" id="imprintQuickLink" translate="no">Loading...</a></li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-lg-5 col-md-5 footer-links">
              <h4>Contact</h4>
              <div class="footer-contact  ">
                <p><i class="bi bi-telephone-fill"></i>&nbsp;&nbsp;Tel: 02921 559 55 28</p>
                <p><i class="bi bi-envelope-fill"></i>&nbsp;&nbsp;goldenarm59494@gmail.com</p>
                <p><i class="bi bi-house-fill"></i>&nbsp;&nbsp;Bruderstraße 58 59494 Soest</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Goldenarm</strong> <span>All Rights Reserved</span></p>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        includedLanguages: localStorage.getItem('googtrans').split('/').filter(function(e){return e}).join(',')
      }, 'google_translate_element');
    }

    function setCookie(key, value, expiry) {
      var expires = new Date();
      expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
      document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    }
  </script>
  <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

  <script>
    if (localStorage.getItem('googtrans')) {
      setCookie('googtrans', localStorage.getItem('googtrans'), 1);

      if(localStorage.getItem('googtrans') === '/en/en/') {
        $('.language-selector img').removeClass('german-selected');
        $('.language-selector img').addClass('english-selected');
        $('.language-selector img').attr('src', 'assets/img/lang/english.png');
      } else {
        $('.language-selector img').removeClass('english-selected');
        $('.language-selector img').addClass('german-selected');
        $('.language-selector img').attr('src', 'assets/img/lang/german.png');
      }
    } else {
      setCookie('googtrans', '/en/de/', 1);
      localStorage.setItem('googtrans', '/en/de/');
    }


    $('.language-selector').off('click').on('click', () => {
      if($('.language-selector img').hasClass('german-selected')) {
        $('.language-selector img').removeClass('german-selected');
        $('.language-selector img').addClass('english-selected');
        $('.language-selector img').attr('src', 'assets/img/lang/english.png');
        localStorage.setItem('googtrans', '/en/en/');
      } else {
        $('.language-selector img').removeClass('english-selected');
        $('.language-selector img').addClass('german-selected');
        $('.language-selector img').attr('src', 'assets/img/lang/german.png');
        localStorage.setItem('googtrans', '/en/de/');
      }

      location.reload();
    });


    if (localStorage.getItem('googtrans') === '/en/de/') {
      $('#privacyPolicyQuickLink').text(`Datenschutzerklärung`);
      $('#imprintQuickLink').text(`Impressum`);
    } else {
      $('#privacyPolicyQuickLink').text(`Privacy Policy`);
      $('#imprintQuickLink').text(`Imprint`);
    }
  </script>
</body>
</html>
