@extends('layouts.vendor')

@section('title', 'Categories')
@section('page_title', 'Categories & Subcategories')
@section('page_subtitle', 'Full control over the product taxonomy')

@section('content')
    <div class="sa-page-grid">
        <div class="sa-card">
            <div class="sa-card-head">
                <div>
                    <h3><i class="fa-solid fa-layer-group"></i> Categories</h3>
                    <p>Organize your catalog at the top level</p>
                </div>
                <button type="button" class="sa-btn sa-btn-primary" data-sa-modal="#saCategoryModal">
                    <i class="fa-solid fa-plus"></i> New Category
                </button>
            </div>

            <div class="sa-table-wrap">
                <table class="sa-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Subcategories</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $category)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->subcategories_count }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    <span class="sa-pill {{ $category->status ? 'ok' : 'muted' }}">
                                        {{ $category->status ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="sa-actions">
                                    @php $catJson = json_encode(['id' => $category->id, 'name' => $category->name, 'status' => (bool) $category->status]); @endphp
                                    <button class="sa-btn sa-btn-ghost"
                                        data-sa-modal="#saCategoryModal"
                                        data-edit-category="{{ $catJson }}"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('vendor.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category and all its subcategories?');" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="sa-btn sa-btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="sa-empty">No categories yet. Create one to get started.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-head">
                <div>
                    <h3><i class="fa-solid fa-sitemap"></i> Subcategories</h3>
                    <p>Refine the catalog with a second level</p>
                </div>
                <button type="button" class="sa-btn sa-btn-primary" data-sa-modal="#saSubcategoryModal" @disabled($categories->isEmpty())>
                    <i class="fa-solid fa-plus"></i> New Subcategory
                </button>
            </div>

            <div class="sa-table-wrap">
                <table class="sa-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $allSubs = $categories->flatMap->subcategories; @endphp
                        @forelse($allSubs as $i => $sub)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $sub->name }}</strong></td>
                                <td>{{ optional($categories->firstWhere('id', $sub->category_id))->name ?? '—' }}</td>
                                <td><code>{{ $sub->slug }}</code></td>
                                <td>
                                    <span class="sa-pill {{ $sub->status ? 'ok' : 'muted' }}">
                                        {{ $sub->status ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="sa-actions">
                                    @php $subJson = json_encode(['id' => $sub->id, 'name' => $sub->name, 'category_id' => $sub->category_id, 'status' => (bool) $sub->status]); @endphp
                                    <button class="sa-btn sa-btn-ghost"
                                        data-sa-modal="#saSubcategoryModal"
                                        data-edit-subcategory="{{ $subJson }}"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('vendor.subcategories.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Delete this subcategory?');" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="sa-btn sa-btn-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="sa-empty">No subcategories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Category modal --}}
    <div class="sa-modal" id="saCategoryModal">
        <div class="sa-modal-dialog">
            <form id="saCategoryForm" method="POST" action="{{ route('vendor.categories.store') }}">
                @csrf
                <div class="sa-modal-head">
                    <h3 id="saCategoryModalTitle">New Category</h3>
                    <button type="button" class="sa-modal-close" data-sa-close>&times;</button>
                </div>
                <div class="sa-modal-body">
                    <label>Name</label>
                    <input type="text" name="name" class="sa-input" required>

                    <label class="sa-switch-row">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" checked>
                        <span>Visible to customers</span>
                    </label>
                </div>
                <div class="sa-modal-foot">
                    <button type="button" class="sa-btn sa-btn-ghost" data-sa-close>Cancel</button>
                    <button type="submit" class="sa-btn sa-btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Subcategory modal --}}
    <div class="sa-modal" id="saSubcategoryModal">
        <div class="sa-modal-dialog">
            <form id="saSubcategoryForm" method="POST" action="{{ route('vendor.subcategories.store') }}">
                @csrf
                <div class="sa-modal-head">
                    <h3 id="saSubcategoryModalTitle">New Subcategory</h3>
                    <button type="button" class="sa-modal-close" data-sa-close>&times;</button>
                </div>
                <div class="sa-modal-body">
                    <label>Parent Category</label>
                    <select name="category_id" class="sa-input" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <label>Name</label>
                    <input type="text" name="name" class="sa-input" required>

                    <label class="sa-switch-row">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" checked>
                        <span>Visible to customers</span>
                    </label>
                </div>
                <div class="sa-modal-foot">
                    <button type="button" class="sa-btn sa-btn-ghost" data-sa-close>Cancel</button>
                    <button type="submit" class="sa-btn sa-btn-primary">Save Subcategory</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/vendor/super-admin.js') }}"></script>
<script>
    (function () {
        document.querySelectorAll('[data-edit-category]').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.editCategory);
                const form = document.getElementById('saCategoryForm');
                form.action = `{{ url('vendor/categories') }}/${data.id}`;
                if (!form.querySelector('input[name="_method"]')) {
                    const m = document.createElement('input');
                    m.type = 'hidden'; m.name = '_method'; m.value = 'PUT';
                    form.appendChild(m);
                }
                form.querySelector('[name="name"]').value = data.name;
                form.querySelector('[name="status"][type="checkbox"]').checked = data.status;
                document.getElementById('saCategoryModalTitle').textContent = 'Edit Category';
            });
        });

        document.querySelectorAll('[data-sa-modal="#saCategoryModal"]:not([data-edit-category])').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = document.getElementById('saCategoryForm');
                form.reset();
                form.action = `{{ route('vendor.categories.store') }}`;
                const m = form.querySelector('input[name="_method"]');
                if (m) m.remove();
                document.getElementById('saCategoryModalTitle').textContent = 'New Category';
            });
        });

        document.querySelectorAll('[data-edit-subcategory]').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.editSubcategory);
                const form = document.getElementById('saSubcategoryForm');
                form.action = `{{ url('vendor/subcategories') }}/${data.id}`;
                if (!form.querySelector('input[name="_method"]')) {
                    const m = document.createElement('input');
                    m.type = 'hidden'; m.name = '_method'; m.value = 'PUT';
                    form.appendChild(m);
                }
                form.querySelector('[name="name"]').value = data.name;
                form.querySelector('[name="category_id"]').value = data.category_id;
                form.querySelector('[name="status"][type="checkbox"]').checked = data.status;
                document.getElementById('saSubcategoryModalTitle').textContent = 'Edit Subcategory';
            });
        });

        document.querySelectorAll('[data-sa-modal="#saSubcategoryModal"]:not([data-edit-subcategory])').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = document.getElementById('saSubcategoryForm');
                form.reset();
                form.action = `{{ route('vendor.subcategories.store') }}`;
                const m = form.querySelector('input[name="_method"]');
                if (m) m.remove();
                document.getElementById('saSubcategoryModalTitle').textContent = 'New Subcategory';
            });
        });
    })();
</script>
@endpush
