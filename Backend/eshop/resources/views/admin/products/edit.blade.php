@extends('layouts.shop')

@section('title', 'Admin - Edit Product')

@section('content')
    <main class="flex-grow-1">
        <div class="container px-3 py-4">
            <h2 class="fw-bold mb-4">Admin Panel</h2>

            <div class="mx-auto" style="max-width: 700px;">
                {{-- HLAVNÝ FORM NA UPDATE PRODUKTU --}}
                <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @include('admin.products.form')

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-custom">Save Edits</button>
                    </div>
                </form>

                {{-- EXISTUJÚCE OBRÁZKY - MIMO HLAVNÉHO FORMU --}}
                <div class="mb-4 mt-4">
                    <p class="small text-muted mb-2">Uploaded:</p>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @forelse ($product->images as $image)
                            <div class="uploaded-thumb">
                                <img
                                    src="{{ asset($image->path) }}"
                                    alt="{{ $image->alt_text ?? $product->name }}"
                                    style="width:70px;height:70px;object-fit:cover;"
                                >

                                <form
                                    method="POST"
                                    action="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                                    onsubmit="return confirm('Delete this image?')"
                                    class="m-0"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn">&#x2715;</button>
                                </form>
                            </div>
                        @empty
                            <div class="uploaded-thumb">
                                <div class="img-placeholder" style="width:70px;height:70px;">img</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- DELETE CELÉHO PRODUKTU - TIEŽ MIMO HLAVNÉHO FORMU --}}
                <div class="text-center mt-4">
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-custom">Remove</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection