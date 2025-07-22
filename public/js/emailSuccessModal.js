class EmailSuccessModal {
    constructor() {
        console.log("[EmailSuccessModal] Initializing...");

        this.modal = document.getElementById("emailSuccessPopup");
        if (!this.modal) {
            console.log("[EmailSuccessModal] Modal element not found");
            return;
        }

        this.baseUrl = this.detectBaseUrl();
        this.initElements();
        this.initEvents();
        this.open();
    }

    detectBaseUrl() {
        const pathParts = window.location.pathname.split("/");
        if (pathParts.includes("waza")) return "/waza/";
        if (pathParts.includes("public")) return "/public/";
        return "/";
    }

    initElements() {
        this.content = this.modal.querySelector(".popup-content");
        this.closeBtns = this.modal.querySelectorAll(".popup-x, .popup-secondary");
        this.cartBtn = this.modal.querySelector("#emailCartBtn");

        this.focusableElements = this.modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        this.firstFocusableElement = this.focusableElements[0];
        this.lastFocusableElement = this.focusableElements[this.focusableElements.length - 1];
    }

    initEvents() {
        this.closeBtns.forEach(btn => {
            btn.addEventListener("click", () => this.close());
        });

        this.modal.addEventListener("click", (e) => {
            if (e.target === this.modal) this.close();
        });

        if (this.cartBtn) {
            this.cartBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.close();
                window.location.href = `${this.baseUrl}public/index.php?page=cart`;
            });
        }

        document.addEventListener("keydown", (e) => this.handleKeyDown(e));
    }

    handleKeyDown(e) {
        if (e.key === "Escape" && this.modal.classList.contains("active")) {
            this.close();
        }

        if (e.key === "Tab" && this.modal.classList.contains("active")) {
            this.trapFocus(e);
        }
    }

    trapFocus(e) {
        if (e.shiftKey && document.activeElement === this.firstFocusableElement) {
            e.preventDefault();
            this.lastFocusableElement.focus();
        } else if (!e.shiftKey && document.activeElement === this.lastFocusableElement) {
            e.preventDefault();
            this.firstFocusableElement.focus();
        }
    }

    open() {
        console.log("[EmailSuccessModal] Opening modal");

        this.scrollPosition = window.pageYOffset;
        this.previousActiveElement = document.activeElement;

        document.documentElement.classList.add("popup-open");
        document.body.style.top = `-${this.scrollPosition}px`;
        document.body.style.position = "fixed";

        this.modal.classList.add("active");
        this.content.classList.add("active");

        setTimeout(() => {
            this.firstFocusableElement?.focus();
        }, 10);

        this.clearSessionFlag();
    }

    close() {
        console.log("[EmailSuccessModal] Closing modal");

        this.content.classList.remove("active");
        setTimeout(() => {
            this.modal.classList.remove("active");
            document.documentElement.classList.remove("popup-open");
            document.body.style.position = "";
            document.body.style.top = "";
            window.scrollTo(0, this.scrollPosition);
            this.previousActiveElement?.focus();
        }, 300);
    }

    async clearSessionFlag() {
        try {
            const response = await fetch(`${this.baseUrl}database/email-verification-flag.php`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ action: "clear_flag" })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            if (data.status !== "success") {
                throw new Error(data.message || "Failed to clear flag");
            }

            console.log("[EmailSuccessModal] Session flag cleared successfully");
        } catch (error) {
            console.error("[EmailSuccessModal] Error clearing session flag:", error);
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("emailSuccessPopup")) {
        new EmailSuccessModal();
    }
});