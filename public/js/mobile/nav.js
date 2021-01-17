const overlay = document.querySelector("#sidenav-overlay");
const nav = document.querySelector("#slide-out");
const cart = document.querySelector(".header__cart");
const search = document.querySelector(".header__search");
const searchNav = document.querySelector("#search-nav");
const shareNav = document.querySelector("#share-nav");
const btnUrlCopy = document.querySelector("#btn-url-copy");

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
const showUrl = function() {
    visibilityUser(0);
    shareNav.style.display = "block";
};
const changeUrl = function(event, ths) {
    if (ths.value != "")
        Array.prototype.forEach.call(ths.closest("form").querySelectorAll("button"), b => b.removeAttribute("disabled"));
    return /^[A-Za-z_0-9]+$/.test(event.key);
};
const saveUrl = function(t) {
    let url = t.elements[1].value;
    axios.post(t.action, {
        url
    })
    .then(function (res) {
        if (res.data.error == 0) 
            Toast.fire({
                icon: 'success',
                title: res.data.mssg
            });
        else
            Toast.fire({
                icon: 'error',
                title: res.data.mssg
            });
    });
};
const copyUrl = function(t) {
    let value = document.querySelector("#url-share-ventor").value;
    if (value == "") {
        Toast.fire({
            icon: 'error',
            title: 'Debe completar el campo'
        });
        return;
    }
    let url = document.querySelector('meta[name="url"]').content + "/link/" + value;
    const temp = document.createElement("input");
    temp.setAttribute("value", url);
    document.querySelector("body").appendChild(temp);
    temp.select()
    document.execCommand("copy");
    temp.remove();
    Toast.fire({
        icon: 'success',
        title: 'Url copiada'
    });
};
btnUrlCopy.addEventListener("click", copyUrl);
$(".nav__mobile--share .close").click(function() {
    shareNav.style.display = "none";
});
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