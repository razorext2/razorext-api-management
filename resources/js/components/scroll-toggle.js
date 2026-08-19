/** Goal: Alpine.js component for smooth scroll toggle (scroll-to-top / scroll-to-bottom), Caller: floating-actions.blade.php, Deps: Alpine.js */
document.addEventListener("alpine:init", () => {
    Alpine.data("scrollToggle", () => ({
        atTop: true,
        atBottom: false,

        init() {
            this.onScroll();
            window.addEventListener("scroll", () => this.onScroll());
        },

        onScroll() {
            const bottomOffset =
                document.documentElement.scrollHeight - window.innerHeight;

            this.atTop = window.scrollY <= 10;
            this.atBottom = window.scrollY >= bottomOffset - 10;
        },

        handleScroll() {
            if (this.atTop) {
                window.scrollTo({
                    top: document.documentElement.scrollHeight,
                    behavior: "smooth",
                });
            } else {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });
            }
        },
    }));
});
