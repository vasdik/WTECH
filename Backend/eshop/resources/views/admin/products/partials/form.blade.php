<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Product Name</label>
        <input
            type="text"
            name="name"
            class="form-control input-rounded @error('name') is-invalid @enderror"
            value="{{ old('name', $product->name) }}"
            required
        >
        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Brand</label>
        <input
            type="text"
            name="brand"
            class="form-control input-rounded @error('brand') is-invalid @enderror"
            value="{{ old('brand', $product->brand) }}"
            required
        >
        @error('brand')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Product Code</label>
        <input
            type="text"
            name="slug"
            class="form-control input-rounded @error('slug') is-invalid @enderror"
            value="{{ old('slug', $product->slug) }}"
            placeholder="auto-generated if empty"
        >
        @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-select input-rounded @error('category_id') is-invalid @enderror" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">Price</label>
        <input
            type="number"
            step="0.01"
            min="0"
            name="price_gross"
            class="form-control input-rounded @error('price_gross') is-invalid @enderror"
            value="{{ old('price_gross', $product->price_gross) }}"
            required
        >
        @error('price_gross')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Color</label>
        <select name="color_id" class="form-select input-rounded @error('color_id') is-invalid @enderror">
            <option value="">No color</option>
            @foreach ($colors as $color)
                <option value="{{ $color->id }}" @selected((string) old('color_id', $product->color_id) === (string) $color->id)>
                    {{ $color->name }}
                </option>
            @endforeach
        </select>
        @error('color_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">Stock Qty</label>
        <input
            type="number"
            min="0"
            name="stock_qty"
            class="form-control input-rounded @error('stock_qty') is-invalid @enderror"
            value="{{ old('stock_qty', $product->stock_qty ?? 0) }}"
            required
        >
        @error('stock_qty')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Weight</label>
        <select name="weight_id" class="form-select input-rounded @error('weight_id') is-invalid @enderror">
            <option value="">No weight</option>
            @foreach ($weights as $weight)
                <option value="{{ $weight->id }}" @selected((string) old('weight_id', $product->weight_id) === (string) $weight->id)>
                    {{ $weight->label }}
                </option>
            @endforeach
        </select>
        @error('weight_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Diameter</label>
        <select name="diameter_id" class="form-select input-rounded @error('diameter_id') is-invalid @enderror">
            <option value="">No diameter</option>
            @foreach ($diameters as $diameter)
                <option value="{{ $diameter->id }}" @selected((string) old('diameter_id', $product->diameter_id) === (string) $diameter->id)>
                    {{ $diameter->label }}
                </option>
            @endforeach
        </select>
        @error('diameter_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Tax rate</label>
        <input
            type="number"
            step="0.01"
            min="0"
            name="tax_rate"
            class="form-control input-rounded @error('tax_rate') is-invalid @enderror"
            value="{{ old('tax_rate', $product->tax_rate ?? 23) }}"
            required
        >
        @error('tax_rate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="is_active"
                id="is_active"
                value="1"
                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="is_active">
                Active product
            </label>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Short Description</label>
    <textarea
        name="short_description"
        class="form-control @error('short_description') is-invalid @enderror"
        rows="3"
    >{{ old('short_description', $product->short_description) }}</textarea>
    @error('short_description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="mb-4">
    <label class="form-label">Description</label>
    <textarea
        name="description"
        id="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="6"
    >{{ old('description', $product->description) }}</textarea>
    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label small text-muted mb-1">Upload images</label>
    <input
        type="file"
        name="images[]"
        accept="image/*"
        multiple
        class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
    >

    <div class="form-text">
        Add at least two product photos.
    </div>

    @error('images')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @error('images.*')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>