const overlay = document.querySelector("#sidenav-overlay");
const nav = document.querySelector("#slide-out");
const cart = document.querySelector(".header__cart");
const search = document.querySelector(".header__search");
const searchNav = document.querySelector("#search-nav");

const visibilityNav = function(open = 1) {
    let duration = 600;
    if (open) {
        if ($(overlay).is(":hidden")) {
            window.navActive = nav;
            nav.animate([
                { transform: 'translateX(-105%)' },
                { transform: 'translateX(0%)' }
                ], {
                    fill: "forwards",
                    duration: duration
                }
            );
            overlay.style.display = "block";
            overlay.style.opacity = 1;
        }
    } else {
        if ($(overlay).is(":visible")) {
            delete window.navActive;
            nav.animate([
                { transform: 'translateX(0%)' },
                { transform: 'translateX(-105%)' }
                ], {
                    fill: "forwards",
                    duration: duration
                }
            );
            overlay.style.display = "none";
            overlay.style.opacity = 0;
        }
    }
};
const showCart = function(evt) {
    if (this.dataset.user == "USR") {
        location.href = document.querySelector('meta[name="checkout"]').content;
        return;
    }
    if (!(typeof showNotification === 'function')) {
        location.href = document.querySelector('meta[name="order"]').content;
        return;
    }
    showNotification();
    axios.post(document.querySelector('meta[name="cart-show"]').content)
    .then(function (res) {
        $("#menu-cart--confirm").prop("disabled", false);
        $(".menu-cart").addClass("expanded");
        $(".menu-cart .menu-cart-list").html(res.data.html);
        $(".menu-cart-price").data("price", res.data.total);
        $(".menu-cart-price").text(formatter.format(res.data.total));
        if (res.data.total == 0)
            $("#menu-cart--confirm").prop("disabled", true);
        hideNotification();
        overlay.style.display = "block";
        overlay.style.opacity = 1;
    });
};
const showSearch = function(evt) {
    searchNav.style.display = "block";
    searchNav.querySelector("input[type=search]").focus()
};
search.addEventListener("click", showSearch);
$(".nav__mobile--search .close").click(function() {
    searchNav.style.display = "none";
});
if (cart)
    cart.addEventListener("click", showCart);
if (document.querySelector(".table-responsive")) {
    document.querySelector(".table-responsive").addEventListener('swiped-right', function(e) {
        window.noSwiped = 1;
    });
    document.querySelector(".table-responsive").addEventListener('swiped-left', function(e) {
        window.noSwiped = 1;
    });
}
document.addEventListener('swiped-right', function(e) {
    if (window.noSwiped === undefined) {
        if (!$(".menu-cart.expanded").length) {
            if (navUser === window.navActive) {
                visibilityUser(0);
            } else if (window.navActive === undefined) { 
                visibilityNav(1);
            }
        }
    } else 
        delete window.noSwiped;
});
document.addEventListener('swiped-left', function(e) {
    if (window.noSwiped === undefined) {
        if (!$(".menu-cart.expanded").length) {
            if (nav === window.navActive) {
                visibilityNav(0);
            } else if (window.navActive === undefined) {
                visibilityUser(1);
            }
        }
    } else 
        delete window.noSwiped;
});
overlay.addEventListener('click', e => {
    if (nav === window.navActive) {
        visibilityNav(0);
    }
    if (navUser === window.navActive) {
        visibilityUser(0);
    }
    if ($(".menu-cart.expanded").length) {
        $(".menu-cart").removeClass("expanded");
        overlay.style.display = "none";
        overlay.style.opacity = 0;
    }
});