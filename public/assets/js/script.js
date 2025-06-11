document.addEventListener('DOMContentLoaded', () => {
  // === Login/Register forms ===
  const loginBtn = document.getElementById('btn-login');
  const registerBtn = document.getElementById('btn-register');
  const loginForm = document.getElementById('form-login');
  const registerForm = document.getElementById('form-register');

  function activate(form) {
    loginForm?.classList.remove('active');
    registerForm?.classList.remove('active');
    loginBtn?.classList.remove('active');
    registerBtn?.classList.remove('active');

    if (form === 'login') {
      loginForm?.classList.add('active');
      loginBtn?.classList.add('active');
    } else {
      registerForm?.classList.add('active');
      registerBtn?.classList.add('active');
    }
  }

  loginBtn?.addEventListener('click', () => activate('login'));
  registerBtn?.addEventListener('click', () => activate('register'));
  activate('login');

  // === Téléphone formaté ===
  const telInput = document.querySelector('input[name="telephone"]');
  telInput?.addEventListener('input', function () {
    let numbers = this.value.replace(/\D/g, '').substring(0, 10);
    let formatted = '';
    for (let i = 0; i < numbers.length; i += 2) {
      if (i > 0) formatted += ' ';
      formatted += numbers.substr(i, 2);
    }
    this.value = formatted;
  });

  // === Panier panel ===
  const openBtn = document.getElementById('openPanier');
  const closeBtn = document.getElementById('closePanier');
  const panel = document.getElementById('panierPanel');
  const overlay = document.getElementById('panierOverlay');

  if (openBtn && closeBtn && panel && overlay) {
    openBtn.addEventListener('click', (e) => {
      e.preventDefault();
      panel.classList.add('open');
      overlay.classList.add('show');
    });

    const closePanel = () => {
      panel.classList.remove('open');
      overlay.classList.remove('show');
    };

    closeBtn.addEventListener('click', closePanel);
    overlay.addEventListener('click', closePanel);
  }

  function renderPanier(data) {
    const container = document.querySelector('.panier-body');
    if (!container) return;

    if (!data.panier.length) {
      container.innerHTML = '<p class="text-muted">Votre panier est vide.</p>';
      return;
    }

    const itemsHtml = data.panier.map(item => `
      <div class="panier-item d-flex align-items-center mb-3">
        <img src="/${item.image}" alt="${item.nom}" class="img-fluid panier-thumb me-3">
        <div>
          <strong>${item.nom}</strong><br>
          <div class="d-flex align-items-center gap-2">
            <a href="#" class="btn btn-sm btn-outline-secondary px-2" data-panier-action="diminuer" data-panier-id="${item.panier_id}">−</a>
            <span>${item.quantite}</span>
            <a href="#" class="btn btn-sm btn-outline-secondary px-2" data-panier-action="augmenter" data-panier-id="${item.panier_id}">+</a>
          </div>
          <small class="text-muted">${item.prix} € / unité</small><br>
          <a href="#" class="text-danger small" data-panier-action="supprimer" data-panier-id="${item.panier_id}">Supprimer</a>
        </div>
      </div>
    `).join('');

    const totalHtml = `<div class="panier-total mt-4">
      <strong>Total : ${data.total} €</strong>
      <a href="?page=valider" class="btn btn-primary w-100 mt-3">Valider la commande</a>
    </div>`;

    container.innerHTML = itemsHtml + totalHtml;
    attachPanierActions();
  }

  function attachPanierActions() {
    document.querySelectorAll('[data-panier-action]').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();

        const action = btn.dataset.panierAction;
        const id = btn.dataset.panierId;

        if (!action || !id) return;

        fetch('?page=panier_ajax', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({ action, id })
        })
        .then(async res => {
          const text = await res.text();
          console.log("🔍 Réponse brute du serveur :", text);
          const data = JSON.parse(text);

          if (data.success === false) {
            console.error("❌ Erreur serveur :", data.error || data.message);
          } else {
            console.log("✅ Panier mis à jour dynamiquement !");
            renderPanier(data);
          }
        })
        .catch(err => {
          console.error("❌ Erreur réseau :", err);
        });
      });
    });
  }

// === Format automatique carte de paiement fluide ===
const carteInput = document.getElementById('carte');
carteInput?.addEventListener('input', function () {
  const rawValue = this.value.replace(/\D/g, '').substring(0, 16);
  const formattedValue = rawValue.match(/.{1,4}/g)?.join(' ') || '';
  const cursorPosition = this.selectionStart;
  const previousLength = this.value.length;

  this.value = formattedValue;

  const diff = this.value.length - previousLength;
  const newCursor = cursorPosition + diff;
  this.setSelectionRange(newCursor, newCursor);
});

// === Format automatique date expiration MM/AA fluide ===
const expirationInput = document.getElementById('expiration');
expirationInput?.addEventListener('input', function () {
  let raw = this.value.replace(/\D/g, '').substring(0, 4);
  if (raw.length >= 3) {
    this.value = raw.slice(0, 2) + '/' + raw.slice(2);
  } else {
    this.value = raw;
  }
});


  // === Limitation CVV à 3 chiffres ===
  const cvvInput = document.getElementById('cvv');
  cvvInput?.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').substring(0, 3);
  });


  attachPanierActions();

  console.log("✅ JS chargé avec succès");
});
