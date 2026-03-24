/* ====================
   Cart quantity manager:
   transforms "Add to Cart" button into a quantity 'widget' (on product page) on click
   works for both the main product button and carousel cards
   ==================== */

(function () {

    /*-- build the quantity widget --*/

    function makeQtyWidget(qty, onMinus, onPlus) {
        const wrap = document.createElement('div');
        wrap.className = 'qty-widget d-flex align-items-center';

        const minusBtn = document.createElement('button');
        minusBtn.className = 'btn btn-outline-secondary btn-sm qty-btn';
        minusBtn.textContent = '−';
        minusBtn.addEventListener('click', onMinus);

        const label = document.createElement('span');
        label.className = 'qty-label px-2 text-center';
        label.textContent = qty + ' in cart';

        const plusBtn = document.createElement('button');
        plusBtn.className = 'btn btn-outline-secondary btn-sm qty-btn';
        plusBtn.textContent = '+';
        plusBtn.addEventListener('click', onPlus);

        wrap.appendChild(minusBtn);
        wrap.appendChild(label);
        wrap.appendChild(plusBtn);

        return { wrap, label };
    }

    /*-- wire up a single Add to Cart button --*/

    function initAddToCartBtn(btn) {
        if (btn.dataset.cartInit) return; // already wired up
        btn.dataset.cartInit = 'true';

        btn.addEventListener('click', function () {
            let qty = 1;

            const { wrap, label } = makeQtyWidget(
                qty,
                /* minus */ function () {
                    qty--;
                    if (qty <= 0) {
                        // remove qty widget and restore original button
                        wrap.replaceWith(btn);
                    } else {
                        label.textContent = qty + ' in cart';
                    }
                },
                /* plus */ function () {
                    qty++;
                    label.textContent = qty + ' in cart';
                }
            );

            btn.replaceWith(wrap);
        });
    }

    /*-- init all buttons on the page --*/

    function initAll() {
        document.querySelectorAll('.add-to-cart-btn').forEach(initAddToCartBtn);
    }

    document.addEventListener('DOMContentLoaded', initAll);

})();
