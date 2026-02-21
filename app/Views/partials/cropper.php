<!-- Modal do Cropper -->
<div id="cropperModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm animate-fade-in">
    <div class="bg-white rounded-[2.5rem] p-6 md:p-8 w-full max-w-2xl shadow-2xl flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-display font-black text-slate-900 leading-tight">Ajustar Imagem</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Recorte Circular Premium
                    (1000x1000)</p>
            </div>
            <button type="button" onclick="closeCropperModal()"
                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div
            class="w-full h-[400px] bg-slate-50 rounded-[2rem] overflow-hidden border border-slate-100 flex items-center justify-center">
            <img id="cropperImage" src="" alt="Para Recortar" class="max-w-full max-h-full">
        </div>

        <div class="flex items-center justify-end gap-4 mt-2">
            <button type="button" onclick="closeCropperModal()"
                class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest px-4">Descartar</button>
            <button type="button" onclick="applyCrop()"
                class="bg-blue-600 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.1em] shadow-xl shadow-blue-600/20 active:scale-95 transition-all">
                Aplicar Recorte
            </button>
        </div>
    </div>
</div>

<style>
    /* Força o cropper a mostrar a área de corte como um círculo */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }

    /* Estilização da linha do cropper pro modo clean */
    .cropper-line,
    .cropper-point {
        background-color: #3b82f6 !important;
    }
</style>

<script>
    let cropper = null;
    let currentFileInput = null;
    let currentPreviewImg = null;
    let currentPlaceholderDiv = null;

    // Função que deve ser chamada no onchange do <input type="file">
    // Exemplo: onchange="initCropper(this, 'photo-preview', 'photo-placeholder')"
    window.initCropper = function (input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            currentFileInput = input;
            currentPreviewImg = document.getElementById(previewId);
            currentPlaceholderDiv = document.getElementById(placeholderId);

            const file = input.files[0];

            // Só inicializa se for imagem
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('cropperImage').src = e.target.result;
                document.getElementById('cropperModal').classList.remove('hidden');
                document.getElementById('cropperModal').classList.add('flex');

                if (cropper) {
                    cropper.destroy();
                }

                // Inicializa o cropper com proporção 1:1
                cropper = new Cropper(document.getElementById('cropperImage'), {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    background: false,
                    autoCropArea: 0.9,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                });
            };
            reader.readAsDataURL(file);
        }
    };

    window.closeCropperModal = function () {
        document.getElementById('cropperModal').classList.add('hidden');
        document.getElementById('cropperModal').classList.remove('flex');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // Se cancelou, limpa o input para não enviar a foto sem crop no submit
        // Se quisermos manter a imagem atual sem crop, precisaríamos de uma lógica mais complexa.
        // Aqui limpamos.
        if (currentFileInput && !currentPreviewImg.src.startsWith('blob:')) {
            currentFileInput.value = '';
        }
    };

    window.applyCrop = function () {
        if (!cropper) return;

        cropper.getCroppedCanvas({
            width: 1000,
            height: 1000,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob((blob) => {
            // Cria arquivo a partir do blob
            const ext = 'jpeg'; // ou 'webp' etc., manteremos coerência ao jpeg ou png
            const file = new File([blob], `profile_cropped.${ext}`, {
                type: `image/${ext}`,
                lastModified: new Date().getTime()
            });

            // Adiciona de volta ao form original pra ser enviado como multipart/form-data
            const container = new DataTransfer();
            container.items.add(file);
            currentFileInput.files = container.files;

            // Atualiza o preview visual do card
            const objectUrl = URL.createObjectURL(blob);
            currentPreviewImg.src = objectUrl;
            currentPreviewImg.classList.remove('hidden');
            if (currentPlaceholderDiv) currentPlaceholderDiv.classList.add('hidden');

            closeCropperModal();
        }, 'image/jpeg', 0.90);
    };
</script>