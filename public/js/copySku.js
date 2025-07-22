document.addEventListener('DOMContentLoaded', function() {
    const copyToClipboard = async (text) => {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();
            const result = document.execCommand('copy');
            document.body.removeChild(textarea);
            return result;
        }
    };

const handleCopyClick = async (event) => {
    const skuElement = event.currentTarget;
    const skuText = skuElement.dataset.sku || skuElement.textContent.trim();
    const copyIcon = skuElement.querySelector('.copy-icon');
    const checkIcon = skuElement.querySelector('.check-icon');
    const tooltip = skuElement.querySelector('.tooltip-sku');

    event.preventDefault();
    event.stopPropagation();

    const success = await copyToClipboard(skuText);

    if (success) {
        if (copyIcon) copyIcon.style.display = 'none';
        if (checkIcon) checkIcon.style.display = 'inline-block';

        if (tooltip) {
            tooltip.textContent = 'Скопировано';
            tooltip.style.opacity = '1';
            tooltip.style.visibility = 'visible';
        }

        setTimeout(() => {
            if (copyIcon) copyIcon.style.display = 'inline-block';
            if (checkIcon) checkIcon.style.display = 'none';

            if (tooltip) {
                tooltip.textContent = 'Скопировать'; // Только меняем текст
                // Тултип не скрываем — он исчезнет на mouseleave
            }
        }, 2000);
    }
};


    const handleMouseEnter = (event) => {
        const span = event.currentTarget;
        const tooltip = span.querySelector('.tooltip-sku');
        const checkIcon = span.querySelector('.check-icon');

        if (tooltip) {
            tooltip.textContent = checkIcon?.style.display === 'inline-block' ? 'Скопировано' : 'Скопировать';
            tooltip.style.opacity = '1';
            tooltip.style.visibility = 'visible';
        }
    };

    const handleMouseLeave = (event) => {
        const tooltip = event.currentTarget.querySelector('.tooltip-sku');
        if (tooltip) {
            tooltip.style.opacity = '0';
            tooltip.style.visibility = 'hidden';
        }
    };

    const initCopyButtons = () => {
        document.querySelectorAll('.copy-sku').forEach(element => {
            element.removeEventListener('click', handleCopyClick);
            element.removeEventListener('mouseenter', handleMouseEnter);
            element.removeEventListener('mouseleave', handleMouseLeave);

            element.addEventListener('click', handleCopyClick);
            element.addEventListener('mouseenter', handleMouseEnter);
            element.addEventListener('mouseleave', handleMouseLeave);

            element.setAttribute('role', 'button');;
            element.style.cursor = 'pointer';
        });
    };

    initCopyButtons();
    $(document).on('ajaxComplete', initCopyButtons);
});
