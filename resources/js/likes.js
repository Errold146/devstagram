document.addEventListener('submit', handleLikeFormSubmit);

async function handleLikeFormSubmit(event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('.js-like-form') || event.defaultPrevented) {
        return;
    }

    event.preventDefault();

    const widget = form.closest('[data-like-widget]');
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton?.setAttribute('disabled', 'disabled');

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error(`Respuesta inesperada del servidor: ${response.status}`);
        }

        const { liked, likes_count: likesCount } = await response.json();

        if (widget) {
            widget.querySelectorAll('.js-like-form').forEach((likeForm) => {
                likeForm.classList.toggle('hidden', (likeForm.dataset.variant === 'liked') !== liked);
            });

            widget.querySelectorAll('[data-like-count]').forEach((el) => {
                el.textContent = `${likesCount} Likes`;
            });
        }
    } catch (error) {
        console.error('No se pudo actualizar el me gusta:', error);
        alert('No se pudo actualizar el me gusta. Inténtalo de nuevo.');
    } finally {
        submitButton?.removeAttribute('disabled');
    }
}
