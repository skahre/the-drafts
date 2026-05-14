function initImageUpload({
    zoneId       = 'drop-zone',
    inputId      = 'image-input',
    previewId    = 'file-preview',
    errorId      = 'image-error',
    errorTextId  = 'error-text',
    previewNameId = 'preview-name',
    previewSizeId = 'preview-size',
    removeId     = 'remove-file',
    maxMB        = 3,
    allowedTypes = ['image/jpeg', 'image/png'],
} = {}) {
    const zone        = document.getElementById(zoneId);
    const input       = document.getElementById(inputId);
    const filePreview = document.getElementById(previewId);
    const errorEl     = document.getElementById(errorId);
    const errorText   = document.getElementById(errorTextId);
    const previewName = document.getElementById(previewNameId);
    const previewSize = document.getElementById(previewSizeId);
    const removeBtn   = document.getElementById(removeId);
    const maxBytes    = maxMB * 1024 * 1024;

    function validateFile(file) {
        if (!file) return null;
        if (file.size > maxBytes) return `Image must be under ${maxMB} MB.`;
        if (!allowedTypes.includes(file.type)) return 'Only JPG and PNG images are allowed.';
        return null;
    }

    function formatSize(bytes) {
        if (bytes >= 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        return Math.round(bytes / 1024) + ' KB';
    }

    function setFile(file) {
        const error = file ? validateFile(file) : null;
        filePreview.classList.toggle('hidden', !file);
        filePreview.classList.toggle('flex', !!file);
        filePreview.classList.toggle('border-error', !!error);
        filePreview.classList.toggle('border-gray', !error);
        if (file) {
            previewName.textContent = file.name;
            previewSize.textContent = formatSize(file.size);
        }
        errorEl.classList.toggle('hidden', !error);
        errorEl.classList.toggle('flex', !!error);
        errorText.textContent = error ?? '';
    }

    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-primary', 'bg-white'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('border-primary', 'bg-white'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('border-primary', 'bg-white');
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        setFile(file);
    });

    input.addEventListener('change', () => setFile(input.files[0]));
    removeBtn.addEventListener('click', () => { input.value = ''; setFile(null); });
    document.querySelector('form').addEventListener('submit', e => {
        const error = validateFile(input.files[0]);
        if (error) { e.preventDefault(); setFile(input.files[0]); }
    });
}
