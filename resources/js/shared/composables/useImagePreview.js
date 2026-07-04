import { onBeforeUnmount, ref } from 'vue';

export function useImagePreview(onFileSelected) {
    const previewImage = ref(null);
    const fileInput = ref(null);
    let objectUrl = null;

    function revokePreview() {
        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }
    }

    function handleImage(event) {
        fileInput.value = event.target;
        const file = fileInput.value?.files?.[0];
        if (!file) return;
        onFileSelected(file);
        revokePreview();
        objectUrl = URL.createObjectURL(file);
        previewImage.value = objectUrl;
    }

    function resetImage() {
        previewImage.value = null;
        revokePreview();
        if (fileInput.value) fileInput.value.value = '';
    }

    onBeforeUnmount(revokePreview);

    return { previewImage, handleImage, resetImage };
}
