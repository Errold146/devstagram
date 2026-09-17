import Dropzone from "dropzone";
import "./comments";
import "./likes";

Dropzone.autoDiscover = false;

const userSearch = document.querySelector('#user-search');

if (userSearch) {
    const searchInput = userSearch.querySelector('#user-search-input');
    const resultsList = userSearch.querySelector('#user-search-results');
    const searchUrl = userSearch.dataset.searchUrl;
    let debounceTimer = null;
    let abortController = null;

    const hideResults = () => {
        resultsList.classList.add('hidden');
        resultsList.innerHTML = '';
    };

    const escapeHtml = (value) => value.replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
    })[char]);

    const renderResults = (users) => {
        if (!users.length) {
            resultsList.innerHTML = '<li class="px-4 py-3 text-xs font-medium text-gray-400">Sin resultados</li>';
            resultsList.classList.remove('hidden');
            return;
        }

        resultsList.innerHTML = users.map((user) => `
            <li>
                <a href="${encodeURI(user.url)}" class="flex items-center gap-3 px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-sky-50 hover:text-sky-700">
                    <span class="h-8 w-8 shrink-0 overflow-hidden rounded-full bg-gray-100">
                        <img src="${encodeURI(user.avatar)}" alt="Avatar de ${escapeHtml(user.username)}" class="h-full w-full object-cover" />
                    </span>
                    <span class="truncate">${escapeHtml(user.username)}</span>
                </a>
            </li>
        `).join('');
        resultsList.classList.remove('hidden');
    };

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();

        clearTimeout(debounceTimer);

        if (query === '') {
            hideResults();
            return;
        }

        debounceTimer = setTimeout(() => {
            abortController?.abort();
            abortController = new AbortController();

            fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, { signal: abortController.signal })
                .then((response) => response.json())
                .then(renderResults)
                .catch((error) => {
                    if (error.name !== 'AbortError') {
                        console.error('Error al buscar usuarios:', error);
                    }
                });
        }, 250);
    });

    document.addEventListener('click', (event) => {
        if (!userSearch.contains(event.target)) {
            hideResults();
        }
    });

    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            hideResults();
            searchInput.blur();
        }
    });
}

const deleteModal = document.querySelector('#delete-modal');

if (deleteModal) {
    let pendingDeleteForm = null;
    const cancelButtons = deleteModal.querySelectorAll('[data-delete-modal-cancel]');
    const confirmButton = deleteModal.querySelector('[data-delete-modal-confirm]');
    const title = deleteModal.querySelector('#delete-modal-title');
    const description = deleteModal.querySelector('#delete-modal-description');

    const closeDeleteModal = () => {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        pendingDeleteForm = null;
    };

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.matches('[data-delete-form]')) {
            return;
        }

        if (form.dataset.deleteConfirmed === 'true') {
            delete form.dataset.deleteConfirmed;
            return;
        }

        event.preventDefault();
        pendingDeleteForm = form;
        title.textContent = form.dataset.deleteTitle ?? '¿Eliminar elemento?';
        description.textContent = form.dataset.deleteDescription ?? 'Esta acción no se puede deshacer.';
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        confirmButton?.focus();
    }, true);

    cancelButtons.forEach((button) => button.addEventListener('click', closeDeleteModal));

    confirmButton?.addEventListener('click', () => {
        if (!pendingDeleteForm) {
            return;
        }

        pendingDeleteForm.dataset.deleteConfirmed = 'true';
        pendingDeleteForm.requestSubmit();
        closeDeleteModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    });
}

if (document.querySelector('#dropzone')) {
    const dropzone = new Dropzone('#dropzone', {
        dictDefaultMessage: 'Sube tu Imagen Aquí',
        acceptedFiles: ".png, .jpg, .jpeg, .gif, .webp",
        addRemoveLinks: true,
        dictRemoveFile: 'Borrar Archivo',
        maxFiles: 1,
        uploadMultiple: false,

        init: function () {
            const imagenInput = document.querySelector('[name="image"]');
            if (imagenInput && imagenInput.value.trim()) {
                const imagenPublicada = {
                    size: 1234,
                    name: imagenInput.value
                };

                this.options.addedfile.call(this, imagenPublicada);
                this.options.thumbnail.call(this, imagenPublicada, `/uploads/${imagenPublicada.name}`);

                imagenPublicada.previewElement.classList.add('dz-success', 'dz-complete');
            }
        }
    });

    dropzone.on('success', (file, response) => {
        const imageInput = document.querySelector('[name="image"]');
        if (imageInput) {
            imageInput.value = response.image;
        }
    });

    dropzone.on('error', (file, message) => {
        console.error('Error al subir la imagen:', message);
    });

    dropzone.on('removedfile', () => {
        const imagenInput = document.querySelector('[name="image"]');
        if (imagenInput) {
            imagenInput.value = '';
        }
    });
}

function setupToggleModal(modalSelector, toggleSelector, closeSelector) {
    const modal = document.querySelector(modalSelector);
    const toggle = document.querySelector(toggleSelector);

    if (!modal || !toggle) {
        return;
    }

    const close = modal.querySelector(closeSelector);

    const openModal = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    };

    toggle.addEventListener('click', openModal);
    close?.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
}

setupToggleModal('#profile-edit-modal', '#profile-edit-toggle', '#profile-edit-close');
setupToggleModal('#post-details-modal', '#post-details-toggle', '#post-details-close');
setupToggleModal('#followers-modal', '#followers-toggle', '#followers-close');
setupToggleModal('#following-modal', '#following-toggle', '#following-close');

if (document.querySelector('#profile-dropzone')) {
    const profileDropzone = new Dropzone('#profile-dropzone', {
        url: document.querySelector('#profile-dropzone').action,
        acceptedFiles: '.png,.jpg,.jpeg,.gif,.webp',
        maxFiles: null,
        maxFilesize: 5,
        uploadMultiple: false,
        addRemoveLinks: false,
        dictDefaultMessage: 'Arrastra una imagen o haz clic para cambiarla',
        headers: { 'X-CSRF-TOKEN': document.querySelector('#profile-dropzone input[name="_token"]').value },
        init: function () {
            const currentAvatar = document.querySelector('[data-user-avatar]')?.src;
            if (currentAvatar && !currentAvatar.endsWith('/usuario.svg')) {
                this.displayExistingFile({ name: 'Imagen actual', size: 1234 }, currentAvatar);
            }
        },
    });

    // Solo debe existir un archivo en cola: el recién añadido reemplaza cualquier vista previa anterior.
    profileDropzone.on('addedfile', (file) => {
        profileDropzone.files
            .filter((existingFile) => existingFile !== file)
            .forEach((existingFile) => profileDropzone.removeFile(existingFile));
    });

    profileDropzone.on('success', (file, response) => {
        document.querySelectorAll('[data-user-avatar]').forEach((avatar) => {
            avatar.src = response.url;
        });
    });

    profileDropzone.on('error', (file, message) => {
        console.error('No se pudo actualizar la imagen de perfil:', message);
        profileDropzone.removeFile(file);
    });
}
