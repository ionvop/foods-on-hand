const g_panelHeader = document.getElementById("g_panelHeader");
const g_imgLogo = document.getElementById("g_imgLogo");
const g_panelBackground = document.getElementById("g_panelBackground");
const g_panelTitle = document.getElementById("g_panelTitle");
const g_panelRecipe = document.querySelectorAll(".g_panelRecipe");
const g_panelTab = document.querySelectorAll(".g_panelTab");

g_initialize();

function g_initialize() {
    const g_panelHeaderOriginalStyles = g_panelHeader.style.cssText;
    const g_imgLogoOriginalStyles = g_imgLogo.style.cssText;
    const g_panelBackgroundOriginalStyles = g_panelBackground.style.cssText;
    const g_panelTitleOriginalStyles = g_panelTitle.style.cssText;
    const g_panelRecipeOriginalStyles = Array.from(g_panelRecipe).map((g_panelRecipeElement) => g_panelRecipeElement.style.cssText);
    const g_panelTabOriginalStyles = Array.from(g_panelTab).map((g_panelTabElement) => g_panelTabElement.style.cssText);

    window.onresize = () => {
        if (window.innerHeight > window.innerWidth) {
            g_panelHeader.style.gridTemplateColumns = "1fr";
            g_panelHeader.style.textAlign = "center";
            g_imgLogo.style.display = "none";
            g_panelBackground.style.height = "40rem";
            g_panelBackground.style.backgroundAttachment = "scroll";
            g_panelBackground.style.backgroundSize = "cover";
            g_panelTitle.style.paddingTop = "5rem";


            for (const g_panelRecipeElement of g_panelRecipe) {
                g_panelRecipeElement.style.gridTemplateColumns = "1fr";
            }

            for (const g_panelTabElement of g_panelTab) {
                g_panelTabElement.style.padding = "2rem";
            }
        } else {
            g_panelHeader.style.cssText = g_panelHeaderOriginalStyles;
            g_imgLogo.style.cssText = g_imgLogoOriginalStyles;
            g_panelBackground.style.cssText = g_panelBackgroundOriginalStyles;
            g_panelTitle.style.cssText = g_panelTitleOriginalStyles;

            for (const g_panelRecipeElement of g_panelRecipe) {
                g_panelRecipeElement.style.cssText = g_panelRecipeOriginalStyles[g_panelRecipe.indexOf(g_panelRecipeElement)];
            }

            for (const g_panelTabElement of g_panelTab) {
                g_panelTabElement.style.cssText = g_panelTabOriginalStyles[g_panelTab.indexOf(g_panelTabElement)];
            }
        }
    }

    window.onresize();
}