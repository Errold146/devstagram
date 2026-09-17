document.addEventListener('submit', handleCommentFormSubmit);
document.addEventListener('submit', handleCommentDeleteSubmit);

async function handleCommentDeleteSubmit(event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('[data-delete-form]') || event.defaultPrevented) {
        return;
    }

    event.preventDefault();

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

        const { id, comments_count: commentsCount } = await response.json();
        document.getElementById(`comment-${id}`)?.remove();
        updateCommentsCount(commentsCount);
        showEmptyCommentsState();
    } catch (error) {
        console.error('No se pudo eliminar el comentario:', error);
        alert('No se pudo eliminar el comentario. Inténtalo de nuevo.');
    } finally {
        submitButton?.removeAttribute('disabled');
    }
}

async function handleCommentFormSubmit(event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('.js-comment-form')) {
        return;
    }

    event.preventDefault();

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

        clearFormErrors(form);

        if (response.status === 422) {
            const { errors } = await response.json();
            showFormErrors(form, errors);
            return;
        }

        if (!response.ok) {
            throw new Error(`Respuesta inesperada del servidor: ${response.status}`);
        }

        const { id, html, is_reply: isReply, root_id: rootId, comments_count: commentsCount } = await response.json();

        insertComment(html, isReply, rootId);
        setLastSeenId(id);
        updateCommentsCount(commentsCount);
        form.reset();

        if (isReply) {
            form.classList.add('hidden');
        }
    } catch (error) {
        console.error('No se pudo enviar el comentario:', error);
        alert('No se pudo enviar tu comentario. Inténtalo de nuevo.');
    } finally {
        submitButton?.removeAttribute('disabled');
    }
}

function insertComment(html, isReply, rootId) {
    if (isReply) {
        const repliesContainer = document.getElementById(`replies-${rootId}`);
        repliesContainer?.classList.remove('hidden');
        repliesContainer?.insertAdjacentHTML('beforeend', html);
        return;
    }

    document.getElementById('comments-empty-state')?.remove();
    document.getElementById('comments-list')?.insertAdjacentHTML('afterbegin', html);
}

function updateCommentsCount(count) {
    const badge = document.getElementById('comments-count');
    if (badge && typeof count === 'number') {
        badge.textContent = count;
    }
}

function showEmptyCommentsState() {
    const commentsList = document.getElementById('comments-list');

    if (!commentsList || commentsList.querySelector('[id^="comment-"]') || document.getElementById('comments-empty-state')) {
        return;
    }

    commentsList.insertAdjacentHTML('beforeend', `
        <div id="comments-empty-state" class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 bg-gray-50/60 py-8 text-center">
            <p class="text-sm font-bold text-gray-800">No hay comentarios aún</p>
            <p class="mt-1 max-w-50 text-xs text-gray-500">Sé el primero en expresar tu opinión sobre este post.</p>
        </div>
    `);
}

function getLastSeenId() {
    return Number(document.getElementById('comments-list')?.dataset.lastId ?? 0);
}

function setLastSeenId(id) {
    const commentsList = document.getElementById('comments-list');
    if (commentsList && id > getLastSeenId()) {
        commentsList.dataset.lastId = String(id);
    }
}

const COMMENTS_POLL_INTERVAL_MS = 5000;

function startCommentsPolling() {
    const commentsList = document.getElementById('comments-list');
    if (!commentsList?.dataset.pollUrl) {
        return;
    }

    setInterval(() => pollForNewComments(commentsList.dataset.pollUrl), COMMENTS_POLL_INTERVAL_MS);
}

async function pollForNewComments(pollUrl) {
    try {
        const response = await fetch(`${pollUrl}?after=${getLastSeenId()}`, {
            headers: { Accept: 'application/json' },
        });

        if (!response.ok) {
            return;
        }

        const { items, comments_count: commentsCount, active_ids: activeIds } = await response.json();

        items.forEach((item) => {
            insertComment(item.html, item.is_reply, item.root_id);
            setLastSeenId(item.id);
        });

        removeDeletedComments(activeIds);
        updateCommentsCount(commentsCount);
        showEmptyCommentsState();
    } catch (error) {
        console.error('No se pudieron cargar nuevos comentarios:', error);
    }
}

function removeDeletedComments(activeIds = []) {
    const activeIdSet = new Set(activeIds.map((id) => String(id)));

    document.querySelectorAll('#comments-list [id^="comment-"]').forEach((commentElement) => {
        const commentId = commentElement.id.replace('comment-', '');

        if (!activeIdSet.has(commentId)) {
            commentElement.remove();
        }
    });
}

startCommentsPolling();

function clearFormErrors(form) {
    form.querySelectorAll('[data-error-for]').forEach((element) => element.remove());
    form.querySelectorAll('.border-red-500').forEach((element) => element.classList.remove('border-red-500'));
}

function showFormErrors(form, errors = {}) {
    Object.entries(errors).forEach(([field, messages]) => {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input) {
            return;
        }

        input.classList.add('border-red-500');

        const message = document.createElement('p');
        message.dataset.errorFor = field;
        message.className = 'mt-2 text-xs font-bold text-red-500';
        message.textContent = messages[0];
        input.insertAdjacentElement('afterend', message);
    });
}
