const overlay = document.querySelector("#sidenav-overlay");
const nav = document.querySelector("#slide-out");
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

document.addEventListener('swiped-right', function(e) {
    if (navUser === window.navActive) {
        visibilityUser(0);
    } else if (window.navActive === undefined) { 
        visibilityNav(1);
    }
});
document.addEventListener('swiped-left', function(e) {
    if (nav === window.navActive) {
        visibilityNav(0);
    } else if (window.navActive === undefined) {
        visibilityUser(1);
    }
});
overlay.addEventListener('click', e => {
    if (nav === window.navActive) {
        visibilityNav(0);
    }
    if (navUser === window.navActive) {
        visibilityUser(0);
    }
});