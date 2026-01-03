g_initialize();

function g_initialize() {
    const mobileElements = document.querySelectorAll("[data-mobile]");

    for (const element of mobileElements) {
        element.dataset.original = element.style.cssText;
    }

    window.onresize = () => {
        if (window.innerHeight > window.innerWidth) {
            for (const element of mobileElements) {
                element.style.cssText += element.dataset.mobile;
            }
        } else {
            for (const element of mobileElements) {
                element.style.cssText = element.dataset.original;
            }
        }
    }

    window.onresize();
}