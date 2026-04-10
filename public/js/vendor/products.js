/* =========================
   ADD PRODUCT MODAL
========================= */
const productModal        = document.getElementById('productModal');
const openProductModal    = document.getElementById('openProductModal');
const closeProductModal   = document.getElementById('closeProductModal');
const cancelProductModal  = document.getElementById('cancelProductModal');

function openModal() {
    if (productModal) {
        productModal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

function closeModal() {
    if (productModal) {
        productModal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

if (openProductModal)   openProductModal.addEventListener('click', openModal);
if (closeProductModal)  closeProductModal.addEventListener('click', closeModal);
if (cancelProductModal) cancelProductModal.addEventListener('click', closeModal);

if (productModal) {
    productModal.addEventListener('click', function (e) {
        if (e.target === productModal) closeModal();
    });
}

/* =========================
   ADD PRODUCT FILE INPUT
========================= */
const imageInput = document.getElementById('image');
const fileName   = document.getElementById('fileName');

if (imageInput && fileName) {
    imageInput.addEventListener('change', function () {
        fileName.textContent = this.files.length ? this.files[0].name : 'No file chosen';
    });
}

/* =========================
   EDIT PRODUCT MODAL
========================= */
const editProductModal      = document.getElementById('editProductModal');
const editProductForm       = document.getElementById('editProductForm');
const closeEditProductModal = document.getElementById('closeEditProductModal');
const cancelEditProductModal= document.getElementById('cancelEditProductModal');
const currentImagePreview   = document.getElementById('currentImagePreview');
const editImageInput        = document.getElementById('edit_image');
const editFileName          = document.getElementById('editFileName');

function openEditModal(button) {
    const id              = button.getAttribute('data-id');
    const name            = button.getAttribute('data-name');
    const description     = button.getAttribute('data-description');
    const fullDescription = button.getAttribute('data-full_description');
    const sku             = button.getAttribute('data-sku');
    const brand           = button.getAttribute('data-brand');
    const stock           = button.getAttribute('data-stock');
    const price           = button.getAttribute('data-price');
    const salePrice       = button.getAttribute('data-sale_price');
    const image           = button.getAttribute('data-image');
    const shippingCharge  = button.getAttribute('data-shipping_charge');
    const categoryId      = button.getAttribute('data-category_id');
    const subcategoryId   = button.getAttribute('data-subcategory_id');

    if (editProductForm) {
        editProductForm.action = '/vendor/products/' + id + '/edit';
    }

    document.getElementById('edit_name').value             = name ?? '';
    document.getElementById('edit_description').value      = description ?? '';
    document.getElementById('edit_full_description').value = fullDescription ?? '';
    document.getElementById('edit_sku').value              = sku ?? '';
    document.getElementById('edit_brand').value            = brand ?? '';
    document.getElementById('edit_stock').value            = stock ?? '';
    document.getElementById('edit_price').value            = price ?? '';
    document.getElementById('edit_sale_price').value       = salePrice ?? '';
    document.getElementById('edit_shipping_charge').value  = shippingCharge ?? '0';
    document.getElementById('edit_category_id').value      = categoryId ?? '';
    loadSubcategories(categoryId, document.getElementById('edit_subcategory_id'), subcategoryId);

    if (currentImagePreview) {
        currentImagePreview.innerHTML = image
            ? '<img src="' + image + '" alt="Product Image">'
            : '<span>No image</span>';
    }

    if (editImageInput)  editImageInput.value = '';
    if (editFileName)    editFileName.textContent = 'No file chosen';

    if (editProductModal) {
        editProductModal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

function closeEditModal() {
    if (editProductModal) {
        editProductModal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

if (closeEditProductModal)  closeEditProductModal.addEventListener('click', closeEditModal);
if (cancelEditProductModal) cancelEditProductModal.addEventListener('click', closeEditModal);

if (editProductModal) {
    editProductModal.addEventListener('click', function (e) {
        if (e.target === editProductModal) closeEditModal();
    });
}

/* =========================
   EDIT PRODUCT FILE INPUT
========================= */
if (editImageInput && editFileName) {
    editImageInput.addEventListener('change', function () {
        if (this.files.length) {
            editFileName.textContent = this.files[0].name;
            const reader = new FileReader();
            reader.onload = function (e) {
                if (currentImagePreview) {
                    currentImagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                }
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            editFileName.textContent = 'No file chosen';
        }
    });
}

/* =========================
   SUBCATEGORY LOADING
========================= */
const categorySelect      = document.getElementById('category_id');
const subcategorySelect   = document.getElementById('subcategory_id');
const editCategorySelect  = document.getElementById('edit_category_id');
const editSubcategorySelect = document.getElementById('edit_subcategory_id');

const subcategoryBaseUrl = (categorySelect || editCategorySelect)
    ? (document.getElementById('subcategory-base-url') || { dataset: { url: '/vendor/subcategories' } }).dataset.url
    : '/vendor/subcategories';

function loadSubcategories(categoryId, targetSelect, selectedSubcategoryId = null) {
    if (!categoryId) {
        targetSelect.innerHTML = '<option value="">Select Subcategory</option>';
        return;
    }

    targetSelect.innerHTML = '<option value="">Loading...</option>';

    fetch(subcategoryBaseUrl + '/' + categoryId)
        .then(response => response.json())
        .then(data => {
            targetSelect.innerHTML = '<option value="">Select Subcategory</option>';
            if (data.status && data.subcategories.length > 0) {
                data.subcategories.forEach(subcategory => {
                    const selected = selectedSubcategoryId == subcategory.id ? 'selected' : '';
                    targetSelect.innerHTML += '<option value="' + subcategory.id + '" ' + selected + '>' + subcategory.name + '</option>';
                });
            }
        })
        .catch(error => {
            console.error('Subcategory fetch error:', error);
            targetSelect.innerHTML = '<option value="">Select Subcategory</option>';
        });
}

if (categorySelect) {
    categorySelect.addEventListener('change', function () {
        loadSubcategories(this.value, subcategorySelect);
    });
}

if (editCategorySelect) {
    editCategorySelect.addEventListener('change', function () {
        loadSubcategories(this.value, editSubcategorySelect);
    });
}
