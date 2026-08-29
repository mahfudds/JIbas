// JIBAS Portal - mobile-app shell: render apps + central login modal
(function () {
  "use strict";

  var APPS = (window.JBS_APPS || []);
  var overlay = document.getElementById("jbsModalOverlay");
  var mIcon = document.getElementById("jbsModalIcon");
  var mName = document.getElementById("jbsModalName");
  var mSub = document.getElementById("jbsModalSub");
  var mForm = document.getElementById("jbsModalForm");
  var mUser = document.getElementById("jbsUser");
  var mPass = document.getElementById("jbsPass");
  var mErr = document.getElementById("jbsModalError");
  var mBtn = document.getElementById("jbsModalBtn");

  var current = null;
  var CARD_INDEX = [];

  // ---- render ----
  function esc(s) {
    var d = document.createElement("div");
    d.textContent = s || "";
    return d.innerHTML;
  }

  function render() {
    var grid = document.getElementById("jbsServiceGrid");
    var lfrag = document.createDocumentFragment();
    CARD_INDEX = [];

    APPS.forEach(function (a, i) {
      var row = document.createElement("a");
      row.className = "service-card";
      row.href = a.url;
      row.style.setProperty("--sc", a.color);
      // Buka app internal di tab sama via modal; URL eksternal dibuka di tab baru.
      var external = /^https?:\/\//i.test(a.url || "");
      if (external && !a.action) {
        row.setAttribute("target", "_blank");
        row.setAttribute("rel", "noopener");
      }
      row.innerHTML =
        '<span class="sc-icon">' + a.icon + "</span>" +
        '<span class="sc-body"><span class="sc-name">' + esc(a.name) + "</span>" +
        '<span class="sc-desc">' + esc(a.desc) + "</span></span>" +
        '<span class="sc-foot">' +
          '<span class="sc-badge ' + (a.lock ? "lock" : "open") + '">' + (a.lock ? "🔒 Login" : "Buka") + "</span>" +
          '<span class="sc-chev">&rsaquo;</span>' +
        "</span>";
      row.addEventListener("click", function (e) {
        if (a.action) { e.preventDefault(); onApp(a); }
      });
      CARD_INDEX.push({ el: row, name: (a.name || "").toLowerCase(), desc: (a.desc || "").toLowerCase() });
      lfrag.appendChild(row);
    });

    grid.appendChild(lfrag);
  }

  // Live filter by keyword across tile name + description
  function wireSearch() {
    var box = document.getElementById("jbsSearch");
    var clear = document.getElementById("jbsSearchClear");
    var empty = document.getElementById("jbsSearchEmpty");
    if (!box) return;

    function apply(q) {
      q = (q || "").trim().toLowerCase();
      var any = false;
      CARD_INDEX.forEach(function (c) {
        var hit = !q || c.name.indexOf(q) !== -1 || c.desc.indexOf(q) !== -1;
        c.el.style.display = hit ? "" : "none";
        if (hit) any = true;
      });
      if (empty) empty.style.display = any ? "none" : "block";
      if (clear) clear.classList.toggle("show", !!q);
    }

    box.addEventListener("input", function () { apply(box.value); });
    if (clear) clear.addEventListener("click", function () { box.value = ""; apply(""); box.focus(); });
  }

  function onApp(a) {
    if (a.action) {
      openModal(a);
    } else {
      window.location.href = a.url;
    }
  }

  // ---- modal ----
  function openModal(a) {
    mIcon.textContent = a.icon;
    mIcon.style.background = a.color;
    mName.textContent = a.name;
    mSub.textContent = a.desc || "Masuk menggunakan akun sistem Anda";
    mErr.classList.remove("show");
    mErr.textContent = "";
    mUser.value = "";
    mPass.value = "";
    current = a;
    overlay.classList.add("open");
    setTimeout(function () { mUser.focus(); }, 60);
  }

  function closeModal() {
    overlay.classList.remove("open");
    current = null;
  }

  function showError(msg) {
    mErr.textContent = msg;
    mErr.classList.add("show");
  }

  // POST to the module login endpoint.
  //   mode "redirect" (default): success emits top.location.href, failure alert().
  //   mode "json": success returns [1,"OK"], failure [-1,"msg"].
  function submit() {
    if (!current) return;
    var action = current.action;
    var user = mUser.value.trim();
    var pass = mPass.value;

    if (!user || !pass) { showError("Isi username dan password terlebih dahulu."); return; }

    mBtn.disabled = true;
    mBtn.textContent = "Memproses...";

    var body = new URLSearchParams();
    body.set("username", user);
    body.set("password", pass);
    if (current.mode === "json") {
      body.set("op", "login");
      body.set("login", user);
    }

    fetch(action, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body.toString(),
      credentials: "same-origin"
    })
      .then(function (r) { return r.text(); })
      .then(function (html) {
        if (current.mode === "json") {
          var res = null;
          try { res = JSON.parse(html); } catch (e) { res = null; }
          if (res && parseInt(res[0]) === 1) {
            window.location.href = current.url;
            return;
          }
          showError(res && res[1] ? res[1] : "Login gagal.");
          mBtn.disabled = false;
          mBtn.textContent = "Masuk";
          mPass.value = "";
          mPass.focus();
          return;
        }
        var okMatch = html.match(/top\.location\.href\s*=\s*["']([^"']+)["']/i);
        if (okMatch) {
          window.location.href = current.url;
          return;
        }
        var errMatch = html.match(/alert\(\s*["']([^"']+)["']\s*\)/i);
        if (errMatch) {
          showError(errMatch[1]);
        } else {
          showError("Pesan tidak dikenali. Coba lagi.");
        }
        mBtn.disabled = false;
        mBtn.textContent = "Masuk";
        mPass.value = "";
        mPass.focus();
      })
      .catch(function () {
        showError("Gagal terhubung ke server.");
        mBtn.disabled = false;
        mBtn.textContent = "Masuk";
      });
  }

  // ---- wire ----
  render();
  wireSearch();

  overlay.addEventListener("click", function (e) { if (e.target === overlay) closeModal(); });
  document.getElementById("jbsModalClose").addEventListener("click", closeModal);
  mForm.addEventListener("submit", function (e) { e.preventDefault(); submit(); });
})();
