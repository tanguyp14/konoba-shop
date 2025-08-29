(function ($) {
  $(window).on("load", function () {
    AOS.init();
  });
  $("document").ready(function () {
    document.querySelectorAll(".custom-description").forEach((container) => {
      let brs = container.querySelectorAll("br");
      for (let i = brs.length - 1; i > 0; i--) {
        if (brs[i].previousSibling && brs[i].previousSibling.nodeName === "BR") {
          brs[i].remove();
        }
      }
    });

    $(".button_menu_mobile").on("click", function () {
      $(this).toggleClass("open");
      $("menu-header").toggleClass("open");
      $("#content").toggleClass("open");
    });

    function toggleHeaderMenus() {
      if ($(window).width() < 1324) {
        $(".site-header-mobile").removeClass("hidden");
        $(".site-header").addClass("hidden");
        $("sub-header").removeClass("hidden");
      } else {
        $(".site-header-mobile").addClass("hidden");
        $(".site-header").removeClass("hidden");
        $("sub-header").addClass("hidden");
      }
    }

    // Appel initial
    toggleHeaderMenus();

    // Mise à jour lors du redimensionnement de la fenêtre
    $(window).on("resize", function () {
      toggleHeaderMenus();
    });
  });

  $("document").ready(function () {
    $(".search_icon").on("click", function (e) {
      e.stopPropagation(); // Empêche la propagation du clic vers le document
      $(".search_wrapper").toggleClass("active");
      $(".search_form_wrapper").toggleClass("active");

      if ($(".search_form_wrapper").hasClass("active")) {
        $(".search_form_wrapper").find('input[type="search"], input[type="text"]').focus();
      }
    });

    // Clic sur la recherche elle-même ne ferme pas
    $(".search_form_wrapper").on("click", function (e) {
      e.stopPropagation();
    });

    // Clic n'importe où ailleurs ferme la recherche
    $(document).on("click", function () {
      $(".search_form_wrapper").removeClass("active");
      $(".search_wrapper").removeClass("active");
    });

    $(".close_icon").on("click", function () {
      $(".search_form_wrapper").removeClass("active");
      $(".search_wrapper").removeClass("active");
    });
  });

  let lastScrollTop = 0;

  function handleScroll() {
    const st = $(window).scrollTop();
    const triggerHeight = 0.2 * $(window).height();

    if ((st < lastScrollTop && st >= triggerHeight) || st === 0) {
      // Scroll vers le haut ou tout en haut → affiche le header
      $("header").addClass("stick").css({
        transform: "translateY(0)",
        transition: "transform 0.3s ease",
      });
    } else if (st >= triggerHeight) {
      // Scroll vers le bas ET on a dépassé 20vh → cache le header
      $("header")
        .css({
          transform: "translateY(-100%)",
          transition: "transform 0.3s ease",
        })
        .removeClass("stick");
    } else {
      // En dessous de 20vh, on laisse le header visible (par exemple au tout début)
      $("header")
        .css({
          transform: "translateY(0)",
          transition: "transform 0.3s ease",
        })
        .addClass("stick");
    }

    lastScrollTop = st;
  }

  $(document).ready(function () {
    // Position initiale : visible si en haut, sinon caché
    if ($(window).scrollTop() < 0.2 * $(window).height()) {
      $("header")
        .css({
          transform: "translateY(0)",
          transition: "transform 0.3s ease",
        })
        .addClass("stick");
    } else {
      $("header")
        .css({
          transform: "translateY(-100%)",
          transition: "transform 0.3s ease",
        })
        .removeClass("stick");
    }

    handleScroll();
    $(window).on("scroll", handleScroll);

    // Création de la carte centrée sur ta position
    var map = L.map("map-footer", {
      attributionControl: false,
      zoomControl: false,
    }).setView([49.445168627342966, 1.089532605182312], 17);

    // Fond de carte (OpenStreetMap)
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      // pas d'attribution affichée
    }).addTo(map);

    // Ajout du marker
    L.marker([49.445168627342966, 1.089532605182312]).addTo(map).bindPopup("Konoba Cards").openPopup();

    // Désactiver toutes les interactions
    map.dragging.disable();
    map.doubleClickZoom.disable();
    map.keyboard.disable();
  });

  /// ELDAR TCG LIKE

  // // Fonction pour mettre à jour la barre en fonction du prix total du panier
  // function updateShippingBar() {
  //     // Récupérer le prix total du panier depuis WooCommerce
  //     var totalPrice = parseFloat($('span.woocommerce-Price-amount.amount').first().text().replace(/[^0-9\.,]/g, '').replace(',', '.')); // Modifiez le sélecteur en fonction de votre structure HTML

  //     // Sélectionnez la balise où vous voulez afficher la barre
  //     var shippingBar = document.getElementById("shipping-bar");

  //     // Seuil pour la livraison gratuite
  //     var freeShippingThreshold = 100;

  //     // Calculez le montant restant pour bénéficier de la livraison gratuite
  //     var remainingForFreeShipping = freeShippingThreshold - totalPrice;

  //     // Vérifiez si le montant total du panier atteint le seuil de livraison gratuite
  //     if (totalPrice >= freeShippingThreshold) {
  //         shippingBar.innerHTML = "Vous bénéficiez de la livraison gratuite !";
  //     } else {
  //         shippingBar.innerHTML = "Plus que <span>" + remainingForFreeShipping.toFixed(1) + "€</span> avant la livraison gratuite !";
  //     }
  // }

  // // Écoutez l'événement de mise à jour du panier de WooCommerce
  // $(document).ready(function () {
  //     // Mise à jour initiale de la barre de livraison
  //     updateShippingBar();

  //     $(document.body).on('updated_cart_totals', function () {
  //         // Mise à jour de la barre de livraison après la mise à jour du panier
  //         setTimeout(function () {
  //             // Mise à jour de la barre de livraison après la mise à jour du panier
  //             updateShippingBar();
  //         }, 500); // 100 millisecondes de délai, ajustez si nécessaire
  //     });

  //     // Écoutez l'événement de clic sur le bouton "Ajouter au panier"
  //     $(document.body).on('click', '.add_to_cart_button', function () {
  //         setTimeout(function () {
  //             // Mise à jour de la barre de livraison après la mise à jour du panier
  //             updateShippingBar();
  //         }, 500); // 100 millisecondes de délai, ajustez si nécessaire
  //     });
  //     $(document.body).on('click', '.remove_from_cart_button', function () {
  //         setTimeout(function () {
  //             // Mise à jour de la barre de livraison après la mise à jour du panier
  //             updateShippingBar();
  //         }, 500); // 100 millisecondes de délai, ajustez si nécessaire
  //     });

  // });

  // // Fonction pour gérer le clic sur le bouton "Ajouter au panier" bug mondial relay
  // document.addEventListener('DOMContentLoaded', () => {
  //     const orderButton = document.querySelector('.wc-block-components-checkout-place-order-button');
  //     const radioButtons = document.querySelectorAll('input[name="radio-control-0"]'); // Tous les boutons radio de livraison
  //     const modalLink = document.querySelector('#modaal_link'); // Lien pour choisir le point relais
  //     const alertMessage = document.querySelector('#mondial_relay_alert'); // Message d'alerte pour choisir un point relais

  //     // Fonction pour activer/désactiver le bouton "Commander"
  //     function toggleOrderButton() {
  //         let isPointRelaySelected = false;

  //         // Vérifier si un point relais a été sélectionné
  //         radioButtons.forEach(radio => {
  //             if (radio.checked && radio.value.includes('mondialrelay_official_shipping')) {
  //                 isPointRelaySelected = true;
  //             }
  //         });

  //         if (orderButton) {
  //             if (isPointRelaySelected) {
  //                 orderButton.removeAttribute('disabled');
  //                 orderButton.innerHTML = '<span class="wc-block-components-button__text"><div aria-hidden="false" class="wc-block-components-checkout-place-order-button__text">Commander</div></span>';
  //                 if (alertMessage) {
  //                     alertMessage.style.display = 'none'; // Masquer l'alerte si un point relais est choisi
  //                 }
  //             } else {
  //                 orderButton.setAttribute('disabled', 'disabled');
  //                 orderButton.innerHTML = '<span class="wc-block-components-button__text">Veuillez choisir un point relais</span>';
  //                 if (alertMessage) {
  //                     alertMessage.style.display = 'block'; // Afficher l'alerte si aucun point relais n'est choisi
  //                 }
  //             }
  //         }
  //     }

  //     // Observer les boutons radio
  //     radioButtons.forEach(radio => {
  //         radio.addEventListener('change', toggleOrderButton);
  //     });

  //     // Observer le modal pour choisir un point relais
  //     if (modalLink) {
  //         modalLink.addEventListener('click', () => {
  //             toggleOrderButton(); // Vérifier l'état du bouton lors de l'ouverture du modal
  //         });
  //     }

  //     // Initialisation au cas où les boutons sont déjà sélectionnés
  //     toggleOrderButton();
  // });
})(jQuery);
