/* Burgery - berbasis main.js Sarab.
   Fitur keranjang, reservasi, newsletter, countdown, dan popup menu
   dihapus karena Burgery tidak memiliki pemesanan online. */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (window.AOS) {
    AOS.init({ duration: 680, once: true, offset: 55, disable: reduceMotion });
  }

  var nav = document.getElementById('nav');
  var btt = document.getElementById('btt');

  /* Navbar & tombol kembali ke atas */
  function onScroll() {
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 60);
    if (btt) btt.classList.toggle('show', window.scrollY > 300);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (btt) {
    btt.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* Tutup navbar mobile setelah klik tautan anchor */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      var href = this.getAttribute('href');
      if (!href || href === '#') return;
      var target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      var collapse = document.getElementById('navmenu');
      if (collapse && collapse.classList.contains('show') && window.bootstrap) {
        var inst = bootstrap.Collapse.getInstance(collapse);
        if (inst) inst.hide();
      }
      setTimeout(function () {
        window.scrollTo({ top: target.offsetTop - 78, behavior: reduceMotion ? 'auto' : 'smooth' });
      }, 50);
    });
  });

  /* Overlay pencarian (form GET sungguhan ke halaman menu) */
  var searchOv = document.getElementById('searchOv');
  var searchBtn = document.getElementById('navSearchBtn');
  function closeSearch() {
    if (!searchOv) return;
    searchOv.classList.remove('open');
    document.body.style.overflow = '';
  }
  if (searchOv && searchBtn) {
    searchBtn.addEventListener('click', function () {
      searchOv.classList.add('open');
      document.body.style.overflow = 'hidden';
      setTimeout(function () {
        var input = document.getElementById('searchInput');
        if (input) input.focus();
      }, 220);
    });
    var closeBtn = document.getElementById('searchClose');
    if (closeBtn) closeBtn.addEventListener('click', closeSearch);
    searchOv.addEventListener('click', function (e) {
      if (e.target === searchOv) closeSearch();
    });
  }

  /* Video hero (hanya jika tautan video diisi di pengaturan) */
  if (window.jQuery && jQuery.fn.magnificPopup) {
    jQuery('.magnific_popup').magnificPopup({
      type: 'iframe',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
      disableOn: 300
    });
  }

  /* Popup galeri (markup dari Sarab) */
  var galPop = document.getElementById('galPop');
  var galData = [];
  var galIdx = 0;
  function openGal(i) {
    galIdx = i;
    var g = galData[i];
    if (!g) return;
    document.getElementById('gpImg').setAttribute('src', g.img);
    document.getElementById('gpImg').setAttribute('alt', g.desc || g.title || '');
    document.getElementById('gpTitle').textContent = g.title || '';
    document.getElementById('gpDesc').textContent = g.desc || '';
    galPop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeGal() {
    if (!galPop) return;
    galPop.classList.remove('open');
    document.body.style.overflow = '';
  }
  if (galPop) {
    document.querySelectorAll('.gitem').forEach(function (item, idx) {
      galData.push({
        img: item.getAttribute('data-gimg'),
        title: item.getAttribute('data-gtitle'),
        desc: item.getAttribute('data-gdesc')
      });
      item.addEventListener('click', function () { openGal(idx); });
    });
    document.getElementById('gpClose').addEventListener('click', closeGal);
    galPop.addEventListener('click', function (e) { if (e.target === galPop) closeGal(); });
    document.getElementById('gpPrev').addEventListener('click', function () {
      openGal((galIdx - 1 + galData.length) % galData.length);
    });
    document.getElementById('gpNext').addEventListener('click', function () {
      openGal((galIdx + 1) % galData.length);
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSearch();
      closeGal();
      if (window.jQuery && jQuery.magnificPopup) jQuery.magnificPopup.close();
    }
    if (galPop && galPop.classList.contains('open')) {
      if (e.key === 'ArrowLeft') document.getElementById('gpPrev').click();
      if (e.key === 'ArrowRight') document.getElementById('gpNext').click();
    }
  });

  /* Slider testimoni */
  if (window.Swiper && document.querySelector('.tesSwiper')) {
    var count = document.querySelectorAll('.tesSwiper .swiper-slide').length;
    new Swiper('.tesSwiper', {
      slidesPerView: 1,
      spaceBetween: 22,
      loop: count > 3,
      autoplay: reduceMotion ? false : { delay: 4000, disableOnInteraction: false },
      pagination: { el: '.swiper-pagination', clickable: true },
      breakpoints: { 640: { slidesPerView: Math.min(2, count) }, 1024: { slidesPerView: Math.min(3, count) } }
    });
  }

  /* Animasi angka statistik hero */
  var counted = false;
  function runCounters() {
    if (counted) return;
    counted = true;
    document.querySelectorAll('.snum').forEach(function (el) {
      var txt = el.textContent.trim();
      var num = parseInt(txt, 10);
      if (isNaN(num) || reduceMotion) return;
      var suffix = txt.replace(/[0-9]/g, '');
      var step = Math.max(1, Math.ceil(num / 40));
      var current = 0;
      var iv = setInterval(function () {
        current += step;
        if (current >= num) { current = num; clearInterval(iv); }
        el.textContent = current + suffix;
      }, 35);
    });
  }
  if (document.getElementById('hero')) {
    setTimeout(runCounters, 400);
  }
})();
