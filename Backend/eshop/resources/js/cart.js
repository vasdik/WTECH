function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? "";
}

function renderCartControl(wrapper, quantity) {
    const addUrl = wrapper.dataset.addUrl;
    const incrementUrl = wrapper.dataset.incrementUrl;
    const decrementUrl = wrapper.dataset.decrementUrl;
    const isFullWidth = wrapper.dataset.fullWidth === "1";
    const widthClass = isFullWidth ? "w-100" : "";

    if (quantity > 0) {
        wrapper.innerHTML = `
            <div class="qty-widget ${widthClass}">
                <form method="POST" action="${decrementUrl}" class="m-0 cart-action-form">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <button type="submit" class="qty-btn btn btn-sm">−</button>
                </form>

                <span class="qty-label">${quantity} in cart</span>

                <form method="POST" action="${incrementUrl}" class="m-0 cart-action-form">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <button type="submit" class="qty-btn btn btn-sm">+</button>
                </form>
            </div>
        `;
    } else {
        wrapper.innerHTML = `
            <form method="POST" action="${addUrl}" class="m-0 ${widthClass} cart-action-form">
                <input type="hidden" name="_token" value="${getCsrfToken()}">
                <button type="submit" class="btn btn-custom btn-sm ${widthClass}">
                    Add to Cart
                </button>
            </form>
        `;
    }
}

function updateHeaderCartCount(cartCount) {
    const target = document.getElementById("header-cart-count");
    if (!target) return;
    target.textContent = cartCount > 0 ? ` (${cartCount})` : "";
}

async function submitCartForm(form) {
    const action = form.getAttribute("action");
    const method = (form.getAttribute("method") || "POST").toUpperCase();

    const response = await fetch(action, {
        method,
        headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
        },
        body: new FormData(form),
    });

    if (!response.ok) {
        throw new Error(`Cart request failed: ${response.status}`);
    }

    return await response.json();
}

async function refreshCartPageContent() {
    const wrapper = document.getElementById("cart-page-content");
    if (!wrapper) return;

    const response = await fetch("/cart", {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "text/html",
        },
    });

    if (!response.ok) {
        throw new Error(`Cart page refresh failed: ${response.status}`);
    }

    const html = await response.text();
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");
    const newWrapper = doc.getElementById("cart-page-content");

    if (!newWrapper) {
        throw new Error("New cart page content not found");
    }

    wrapper.innerHTML = newWrapper.innerHTML;
}

document.addEventListener("submit", async (event) => {
    const form = event.target.closest(".cart-action-form");
    if (!form) return;

    event.preventDefault();

    try {
        const data = await submitCartForm(form);
        updateHeaderCartCount(data.cart_count);

        const isCartPage = !!document.getElementById("cart-page-content");

        if (isCartPage) {
            await refreshCartPageContent();
            return;
        }

        const productId = String(data.product_id);

        document.querySelectorAll(`.cart-control[data-product-id="${productId}"]`).forEach((wrapper) => {
            renderCartControl(wrapper, data.quantity);
        });
    } catch (error) {
        console.error("Cart AJAX failed:", error);
        alert("Cart AJAX failed. Pozri Console / Network.");
    }
});