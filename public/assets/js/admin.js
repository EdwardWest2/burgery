/* JavaScript panel admin Burgery: editor rich-text, konfirmasi hapus, slug, pratinjau gambar. */
(function () {
  'use strict';

  /* Konfirmasi sebelum aksi berbahaya: <form data-confirm="Pesan..."> */
  document.addEventListener('submit', function (e) {
    var msg = e.target.getAttribute && e.target.getAttribute('data-confirm');
    if (msg && !window.confirm(msg)) e.preventDefault();
  });

  /* Sidebar mobile */
  var burger = document.getElementById('admBurger');
  var side = document.getElementById('admSide');
  if (burger && side) burger.addEventListener('click', function () { side.classList.toggle('open'); });

  /* Pratinjau gambar yang baru dipilih */
  document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var target = document.getElementById(input.getAttribute('data-preview'));
      if (!target || !input.files || !input.files[0]) return;
      target.src = URL.createObjectURL(input.files[0]);
      target.style.display = 'block';
    });
  });

  /* Slug otomatis dari judul selama slug belum diubah manual */
  var title = document.querySelector('[data-slug-source]');
  var slug = document.querySelector('[data-slug-target]');
  if (title && slug) {
    var touched = slug.value.trim() !== '';
    slug.addEventListener('input', function () { touched = slug.value.trim() !== ''; });
    title.addEventListener('input', function () {
      if (touched) return;
      slug.value = title.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 120);
    });
  }

  /* Penghitung karakter */
  document.querySelectorAll('[data-counter]').forEach(function (el) {
    var out = document.getElementById(el.getAttribute('data-counter'));
    if (!out) return;
    function upd() { out.textContent = el.value.length + ' karakter'; }
    el.addEventListener('input', upd);
    upd();
  });

  /* ---------- Editor rich-text (contenteditable) ---------- */
  function isSafeUrl(url) {
    return /^(https?:\/\/|mailto:|tel:|\/|#)/i.test(url.trim());
  }

  document.querySelectorAll('[data-rte]').forEach(function (wrap) {
    var textarea = wrap.querySelector('textarea');
    var area = wrap.querySelector('.rte-area');
    var bar = wrap.querySelector('.rte-bar');
    var status = wrap.querySelector('.rte-status');
    var uploadUrl = wrap.getAttribute('data-upload-url');
    var csrf = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    area.innerHTML = textarea.value;
    try { document.execCommand('defaultParagraphSeparator', false, 'p'); } catch (e) {}

    function sync() { textarea.value = area.innerHTML; }
    function say(text) { if (status) status.textContent = text; }
    area.addEventListener('input', sync);
    var form = wrap.closest('form');
    if (form) form.addEventListener('submit', sync);

    function exec(cmd, val) {
      area.focus();
      document.execCommand(cmd, false, val || null);
      sync();
      refresh();
    }

    function refresh() {
      bar.querySelectorAll('[data-cmd]').forEach(function (b) {
        var c = b.getAttribute('data-cmd');
        if (['bold', 'italic', 'underline', 'insertUnorderedList', 'insertOrderedList'].indexOf(c) > -1) {
          try { b.classList.toggle('on', document.queryCommandState(c)); } catch (e) {}
        }
      });
    }
    area.addEventListener('keyup', refresh);
    area.addEventListener('mouseup', refresh);

    /* Tempel sebagai teks polos agar tidak membawa gaya/HTML sampah */
    area.addEventListener('paste', function (e) {
      e.preventDefault();
      var text = (e.clipboardData || window.clipboardData).getData('text/plain');
      document.execCommand('insertText', false, text);
    });

    bar.addEventListener('mousedown', function (e) {
      if (e.target.closest('button')) e.preventDefault(); // pertahankan seleksi
    });

    bar.addEventListener('click', function (e) {
      var btn = e.target.closest('button');
      if (!btn) return;
      var cmd = btn.getAttribute('data-cmd');
      if (cmd === 'h2' || cmd === 'h3') return exec('formatBlock', '<' + cmd + '>');
      if (cmd === 'p') return exec('formatBlock', '<p>');
      if (cmd === 'quote') return exec('formatBlock', '<blockquote>');
      if (cmd === 'link') {
        var url = window.prompt('Alamat tautan (https://... atau mailto:...)', 'https://');
        if (!url) return;
        if (!isSafeUrl(url)) { window.alert('Tautan tidak valid. Gunakan http://, https://, mailto: atau tel:.'); return; }
        return exec('createLink', url.trim());
      }
      if (cmd === 'unlink') return exec('unlink');
      if (cmd === 'image') return wrap.querySelector('input[type=file]').click();
      if (cmd === 'clear') { exec('removeFormat'); return exec('formatBlock', '<p>'); }
      if (cmd) exec(cmd);
    });

    var fileInput = wrap.querySelector('input[type=file]');
    if (fileInput && uploadUrl) {
      fileInput.addEventListener('change', function () {
        var file = fileInput.files[0];
        if (!file) return;
        var data = new FormData();
        data.append('image', file);
        say('Mengunggah gambar...');
        fetch(uploadUrl, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
          body: data,
          credentials: 'same-origin'
        }).then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
          .then(function (res) {
            fileInput.value = '';
            if (!res.ok) {
              var errs = res.body && res.body.errors ? Object.values(res.body.errors)[0][0] : 'Gagal mengunggah gambar.';
              say(errs); window.alert(errs); return;
            }
            exec('insertImage', res.body.url);
            say('Gambar disisipkan.');
          }).catch(function () { say('Gagal mengunggah gambar.'); window.alert('Gagal mengunggah gambar.'); });
      });
    }
  });
})();
