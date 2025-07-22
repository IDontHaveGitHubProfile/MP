document.addEventListener("DOMContentLoaded", function () {
    const minInput = document.getElementById("min-price");
    const maxInput = document.getElementById("max-price");
    const minRange = document.getElementById("min-range");
    const maxRange = document.getElementById("max-range");
    const sliderTrack = document.querySelector(".slider-track");

    const minLimit = parseInt(minRange.min);
    const maxLimit = parseInt(maxRange.max);

    function formatNumber(value) {
        let cleaned = value.replace(/\D/g, "").slice(0, 9);
        return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    function unformatNumber(value) {
        return value.replace(/\s/g, "");
    }

    window.updateSliderTrack = function () {
        const minVal = parseInt(minRange.value);
        const maxVal = parseInt(maxRange.value);
        const minPercent = ((minVal - minLimit) / (maxLimit - minLimit)) * 100;
        const maxPercent = ((maxVal - minLimit) / (maxLimit - minLimit)) * 100;

        sliderTrack.style.background =
            `linear-gradient(to right, white ${minPercent}%, var(--accent-blue) ${minPercent}%, var(--accent-blue) ${maxPercent}%, white ${maxPercent}%)`;
    };

    function validateAndFormatInput(inputEl, rangeEl, isMin) {
        const raw = unformatNumber(inputEl.value);
        if (!raw) return;
        let value = parseInt(raw);
        if (isNaN(value)) return;

        if (isMin) {
            value = Math.min(Math.max(value, minLimit), parseInt(maxRange.value) - 1);
            minRange.value = value;
        } else {
            value = Math.max(Math.min(value, maxLimit), parseInt(minRange.value) + 1);
            maxRange.value = value;
        }

        inputEl.value = formatNumber(value.toString());
        updateSliderTrack();
    }

    function attachInputSync(inputEl, rangeEl, isMin) {
        inputEl.addEventListener("input", () => {
            const raw = unformatNumber(inputEl.value);
            const formatted = formatNumber(raw);
            const pos = inputEl.selectionStart;
            inputEl.value = formatted;
            const diff = formatted.length - raw.length;
            inputEl.setSelectionRange(pos + diff, pos + diff);
        });

        inputEl.addEventListener("blur", () => {
            validateAndFormatInput(inputEl, rangeEl, isMin);
        });
    }

    minRange.addEventListener("input", () => {
        minInput.value = formatNumber(minRange.value);
        updateSliderTrack();
    });

    maxRange.addEventListener("input", () => {
        maxInput.value = formatNumber(maxRange.value);
        updateSliderTrack();
    });

    attachInputSync(minInput, minRange, true);
    attachInputSync(maxInput, maxRange, false);

    updateSliderTrack();
});
