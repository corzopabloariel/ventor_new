const buttonNav = document.querySelector("#button--nav");
const buttonUser = document.querySelector("#button--user");
const navUser = document.querySelector("#slide-user");

buttonNav.addEventListener("click", e => visibilityNav(1));
buttonUser.addEventListener("click", e => visibilityUser(1));

const visibilityUser = function(open = 1) {
    let duration = 600;
    if (open) {
        if ($(overlay).is(":hidden")) {
            window.navActive = navUser;
            navUser.animate([
                { transform: 'translateX(205%)' },
                { transform: 'translateX(' + (window.outerWidth - 300) + 'px)' }
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
            delete window.navActive
            navUser.animate([
                { transform: 'translateX(' + (window.outerWidth - 300) + 'px)' },
                { transform: 'translateX(205%)' }
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